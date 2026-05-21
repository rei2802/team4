document.addEventListener("DOMContentLoaded", function () {

  let allBookings  = [];
  let cancelTarget = null;

  // ── SESSION CHECK ──────────────────────────────────────────────
  fetch('whoami.php')
    .then(r => r.json())
    .then(data => {
      if (!data.logged_in) {
        window.location.href = 'user_login.php';
        return Promise.reject('not logged in');
      }
      const welcomeEl = document.getElementById('mybookingsUser');
      if (welcomeEl) welcomeEl.textContent = 'Logged in as ' + data.name;
      return fetch('get_my_bookings.php');
    })
    .then(r => r.json())
    .then(data => {
      if (!data.success) { window.location.href = 'user_login.php'; return; }

      allBookings = data.bookings.map(b => ({
        id:                   'BK-' + b.booking_id,
        db_id:                b.booking_id,
        pickup:               b.pickup_location   || '—',
        delivery:             b.delivery_location || '—',
        truck:                b.truck_type        || '—',
        distance:             b.distance          || '—',
        weight:               b.cargo_weight      || '—',
        weightUnit:           b.weight_unit       || 'kg',
        frequency:            b.frequency         || 'once',
        startDate:            b.start_date        || '—',
        endDate:              b.end_date          || '—',
        special:              b.special_transport || 'None',
        details:              b.details           || '',
        date:                 b.created_at ? new Date(b.created_at).toLocaleDateString() : '—',
        // Normalise status to lowercase for consistent tab matching
        status:               (b.status || b.booking_status || 'pending').toLowerCase(),
        disapproval_reason:   b.disapproval_reason  || null,
        disapproval_message:  b.disapproval_message || null,
        confirmation_sent_at: b.confirmation_sent_at || null,
        final_price:          b.final_price          || null,
        downpayment:          b.downpayment          || null,
        payment_method:       b.payment_method       || null,
        payment_deadline:     b.payment_deadline     || null,
        service_type:         b.service_type         || null,
        fleet_assigned:       b.fleet_assigned       || null,
        terms:                b.terms                || null,
        admin_notes:          b.admin_notes          || null,
        progress_updates:     b.progress_updates     || null,
      }));

      renderBookings(allBookings, 'all');
    })
    .catch(err => {
      if (err !== 'not logged in')
        document.getElementById('emptyState').style.display = 'block';
    });

  // ── FILTER TABS ────────────────────────────────────────────────
  // Tab keys:
  //   all | pending | approved | rejected | completed
  //   "rejected" covers both disapproved + cancelled
  window.filterBookings = function (tabKey, btn) {
    document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    renderBookings(allBookings, tabKey);
  };

  // ── RENDER ─────────────────────────────────────────────────────
  function renderBookings(bookings, tabKey) {
    const list  = document.getElementById('bookingsList');
    const empty = document.getElementById('emptyState');
    list.innerHTML = '';

    const filtered = bookings.filter(b => {
      if (tabKey === 'all')      return true;
      if (tabKey === 'pending')  return b.status === 'pending';
      if (tabKey === 'approved') return ['approved', 'active', 'in negotiation', 'inactive', 'signed'].includes(b.status);
      // "rejected" tab = disapproved OR cancelled
      if (tabKey === 'rejected') return ['disapproved', 'cancelled'].includes(b.status);
      if (tabKey === 'completed') return b.status === 'completed';
      return true;
    });

    if (filtered.length === 0) { empty.style.display = 'block'; return; }
    empty.style.display = 'none';

    filtered.forEach(b => {
      const card = document.createElement('div');
      card.className = `booking-card status-${b.status}`;

      // Badge label
      let badgeLabel = capitalize(b.status);
      if (b.status === 'disapproved' || b.status === 'cancelled') badgeLabel = capitalize(b.status);

      card.innerHTML = `
        <div class="booking-card-top">
          <div>
            <div class="booking-card-id">Booking ID: ${b.id}</div>
            <div class="booking-card-route">
              ${b.pickup} <span>→</span> ${b.delivery}
            </div>
          </div>
          <span class="status-badge ${b.status}">${badgeLabel}</span>
        </div>

        <div class="booking-card-details">
          <div class="booking-detail-item">
            <span class="booking-detail-label">DATE BOOKED</span>
            <span class="booking-detail-value">${b.date}</span>
          </div>
          <div class="booking-detail-item">
            <span class="booking-detail-label">TRUCK TYPE</span>
            <span class="booking-detail-value">${b.truck}</span>
          </div>
          <div class="booking-detail-item">
            <span class="booking-detail-label">DISTANCE</span>
            <span class="booking-detail-value">${b.distance} km (one-way)</span>
          </div>
          <div class="booking-detail-item">
            <span class="booking-detail-label">CARGO WEIGHT</span>
            <span class="booking-detail-value">${b.weight} ${b.weightUnit}</span>
          </div>
          <div class="booking-detail-item">
            <span class="booking-detail-label">FREQUENCY</span>
            <span class="booking-detail-value">${capitalize(b.frequency)}</span>
          </div>
          <div class="booking-detail-item">
            <span class="booking-detail-label">START DATE</span>
            <span class="booking-detail-value">${b.startDate}</span>
          </div>
          <div class="booking-detail-item">
            <span class="booking-detail-label">END DATE</span>
            <span class="booking-detail-value">${b.endDate}</span>
          </div>
          <div class="booking-detail-item">
            <span class="booking-detail-label">SPECIAL TRANSPORT</span>
            <span class="booking-detail-value">${b.special || 'None'}</span>
          </div>
          ${b.details ? `
          <div class="booking-detail-item" style="grid-column:1/-1;">
            <span class="booking-detail-label">SPECIAL INSTRUCTIONS</span>
            <span class="booking-detail-value">${b.details}</span>
          </div>` : ''}
        </div>

        <div class="booking-card-actions">
          ${b.status === 'pending' ? `
            <button class="cancel-booking-btn" onclick="openModal(${b.db_id})">✕ Cancel Booking</button>
          ` : ''}
          ${b.status === 'completed' ? `
            <span class="booking-completed-badge">✔ Service Completed</span>
          ` : ''}
        </div>
      `;

      // ── DISAPPROVAL / CANCELLED NOTICE ────────────────────────
      if ((b.status === 'disapproved' || b.status === 'cancelled') && (b.disapproval_reason || b.status === 'cancelled')) {
        const noticeDiv = document.createElement('div');
        noticeDiv.className = 'booking-disapproval-box';

        const isDisapproved = b.status === 'disapproved';
        noticeDiv.innerHTML = `
          <div class="bdb-header">
            <span class="bdb-icon">✕</span>
            <span class="bdb-title">Booking ${isDisapproved ? 'Disapproved' : 'Cancelled'}</span>
          </div>
          <div class="bdb-body">
            ${b.disapproval_reason ? `
            <div class="bdb-row">
              <span class="bdb-label">Reason</span>
              <span class="bdb-value bdb-reason">${b.disapproval_reason}</span>
            </div>` : ''}
            ${b.disapproval_message ? `
            <div class="bdb-row">
              <span class="bdb-label">Message from Admin</span>
              <span class="bdb-value">${b.disapproval_message}</span>
            </div>` : ''}
          </div>
          <div class="bdb-footer">
            You may submit a new booking request or contact us for assistance.
          </div>
        `;
        card.appendChild(noticeDiv);
      }

      // ── COMPLETED NOTICE ──────────────────────────────────────
      if (b.status === 'completed') {
        const completedDiv = document.createElement('div');
        completedDiv.className = 'booking-completed-box';
        completedDiv.innerHTML = `
          <div class="bcomplete-header">
            <span class="bcomplete-icon">✔</span>
            <span class="bcomplete-title">Service Completed</span>
          </div>
          <div class="bcomplete-body">
            Thank you for choosing 2K2J Trucking Services. Your booking has been successfully completed.
          </div>
        `;
        card.appendChild(completedDiv);
      }

      // ── CONFIRMATION BOX ──────────────────────────────────────
      if (b.confirmation_sent_at) {
        const fmt = n => '₱' + parseFloat(n).toLocaleString('en-PH', {
          minimumFractionDigits: 2, maximumFractionDigits: 2
        });

        let progressHTML = '';
        if (b.progress_updates && b.progress_updates.trim()) {
          const rows = b.progress_updates.split('\n').filter(l => l.trim()).map(line => {
            const idx = line.lastIndexOf('|');
            let msg, time;
            if (idx > -1) { msg = line.substring(0, idx).trim(); time = line.substring(idx + 1).trim(); }
            else {
              const m = line.trim().match(/^(.*?)\s+((?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)\s+\d{1,2},\s+\d{4}\s+\d{1,2}:\d{2}\s+[AP]M)$/);
              msg = m ? m[1].trim() : line.trim(); time = m ? m[2].trim() : '';
            }
            if (!msg) return '';
            return `<div class="bcb-progress-row"><span class="bcb-progress-msg">${msg}</span><span class="bcb-progress-time">${time}</span></div>`;
          }).join('');
          if (rows) progressHTML = `<div class="bcb-progress"><div class="bcb-progress-title">🚚 Progress Updates</div>${rows}</div>`;
        }

        const confirmDiv = document.createElement('div');
        confirmDiv.className = 'booking-confirmation-box';
        confirmDiv.innerHTML = `
          <div class="bcb-header">
            <span class="bcb-badge">📋 Booking Confirmation</span>
            <span class="bcb-date">Sent: ${new Date(b.confirmation_sent_at).toLocaleDateString('en-PH', { year:'numeric', month:'short', day:'numeric' })}</span>
          </div>
          <div class="bcb-grid">
            <div class="bcb-row"><span class="bcb-label">Service Type</span><span class="bcb-value">${b.service_type || '—'}</span></div>
            <div class="bcb-row"><span class="bcb-label">Fleet Assigned</span><span class="bcb-value">${b.fleet_assigned || '—'}</span></div>
            <div class="bcb-row"><span class="bcb-label">Final Price</span><span class="bcb-value bcb-price">${fmt(b.final_price)}</span></div>
            <div class="bcb-row bcb-highlight"><span class="bcb-label">30% Downpayment Due</span><span class="bcb-value bcb-dp">${fmt(b.downpayment)}</span></div>
            <div class="bcb-row"><span class="bcb-label">Payment Method</span><span class="bcb-value">${b.payment_method || '—'}</span></div>
            <div class="bcb-row bcb-urgent"><span class="bcb-label">⏰ Payment Deadline</span><span class="bcb-value">${new Date(b.payment_deadline).toLocaleDateString('en-PH', { year:'numeric', month:'long', day:'numeric' })}</span></div>
          </div>
          <div class="bcb-terms">
            <div class="bcb-terms-label">Terms & Conditions</div>
            <pre class="bcb-terms-text">${b.terms || ''}</pre>
          </div>
          ${b.admin_notes ? `<div class="bcb-notes"><strong>📌 Admin Notes:</strong> ${b.admin_notes}</div>` : ''}
          ${progressHTML}
        `;
        card.appendChild(confirmDiv);
      }

      list.appendChild(card);
    });
  }

  // ── LOGOUT ─────────────────────────────────────────────────────
  window.logoutUser = function () {
    if (!confirm("Are you sure you want to logout?")) return;
    fetch('logout.php').then(() => { localStorage.clear(); window.location.href = 'user_login.php'; });
  };

  // ── CANCEL MODAL ───────────────────────────────────────────────
  window.openModal = function (id) {
    cancelTarget = id;
    document.getElementById('cancelModal').style.display = 'flex';
  };
  window.closeModal = function () {
    cancelTarget = null;
    document.getElementById('cancelModal').style.display = 'none';
  };
  window.confirmCancel = function () {
    if (!cancelTarget) return;
    fetch('cancel_booking.php', {
      method: 'POST', headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ booking_id: cancelTarget })
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) { closeModal(); location.reload(); }
      else alert('❌ ' + (data.message || 'Could not cancel'));
    });
  };

  function capitalize(str) {
    if (!str) return '—';
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

});