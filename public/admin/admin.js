document.addEventListener('DOMContentLoaded', () => {

    /*================= NAVBAR =================*/
    const hamburger = document.getElementById('hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');
        });
    }

    const links = document.querySelectorAll('.nav-links a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            if (hamburger) hamburger.classList.remove('active');
        });
    });

    /*================= STATUS DROPDOWN COLORS ON LOAD =================*/
    document.querySelectorAll(".status-dropdown").forEach(select => {
        applyStatusColor(select);
    });

    /*================= FILTER TABLE =================*/
    const searchInput = document.getElementById("clientSearch");
    if (searchInput) {
        searchInput.addEventListener("keyup", filterTable);
    }

});

// ==================== TOAST ====================
function showToast(message) {
    const toast = document.getElementById("toast");
    if (!toast) return;
    toast.innerText = message;
    toast.className = "show";
    setTimeout(() => {
        toast.className = toast.className.replace("show", "");
    }, 3000);
}

// ==================== LOGOUT ====================
function adminLogout() {
    if (!confirm('Are you sure you want to logout?')) return;
    fetch('../logout.php').then(() => {
        sessionStorage.clear();
        window.location.href = '../index.php';
    });
}

// ==================== ACCEPT REQUEST (from pending cards) ====================
function acceptRequest(requestId, companyName) {
    fetch(`update_booking.php?id=${requestId}&status=Active`)
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                showToast(`${companyName} accepted! ✅`);

                const card = document.querySelector(
                    `button[onclick*="acceptRequest(${requestId},"]`
                )?.closest('.request-card');
                if (card) card.remove();

                const pending = document.getElementById("pendingApprovalCount");
                if (pending) pending.innerText = Math.max(0, parseInt(pending.innerText) - 1);

                const total = document.getElementById("totalClientsCount");
                if (total) total.innerText = parseInt(total.innerText) + 1;

                const tbody = document.querySelector("#clientTable tbody");
                const noRecords = tbody.querySelector("td[colspan]");
                if (noRecords) noRecords.closest("tr").remove();

                const newRow = document.createElement("tr");
                newRow.setAttribute("data-id", requestId);
                newRow.innerHTML = `
                    <td>${companyName}</td>
                    <td>Signed</td>
                    <td>—</td>
                    <td>
                        <select class="status-dropdown status-active"
                            onchange="changeStatus(this, ${requestId})">
                            <option value="Active" selected>Active</option>
                            <option value="In Negotiation">In Negotiation</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </td>
                    <td class="action-cell">
                        <a href="view_booking.php?id=${requestId}">
                            <img src="assets/view.png" class="action-icon-img" title="View">
                        </a>
                        <img src="assets/check.png" class="action-icon-img" title="Mark Completed"
                            style="cursor:pointer;"
                            onclick="markCompleted(${requestId}, '${companyName.replace(/'/g, "\\'")}')">
                        <button class="btn-send-confirm"
                            onclick="openConfirmPanel(${requestId}, '${companyName.replace(/'/g, "\\'")}', '', '')">
                            📋 Send Confirmation
                        </button>
                    </td>
                `;
                tbody.prepend(newRow);

            } else {
                showToast("Error: " + data);
            }
        })
        .catch(error => {
            console.error(error);
            showToast("Fetch failed ❌");
        });
}

// ==================== APPROVE ICON (from table) ====================
function approveBooking(requestId, companyName) {
    fetch(`update_booking.php?id=${requestId}&status=Signed`)
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                showToast(`${companyName} signed! ✅`);

                const btn = document.querySelector(`img[onclick*="approveBooking(${requestId},"]`);
                if (btn) {
                    const row = btn.closest('tr');
                    if (row) row.remove();
                }

                const signed = document.getElementById("signedAgreementsCount");
                if (signed) signed.innerText = parseInt(signed.innerText) + 1;

                const total = document.getElementById("totalClientsCount");
                if (total) total.innerText = parseInt(total.innerText) - 1;

            } else {
                showToast("Error: " + data);
            }
        })
        .catch(error => {
            console.error(error);
            showToast("Fetch failed ❌");
        });
}

// ==================== CHANGE STATUS ====================
function applyStatusColor(select) {
    select.className = "status-dropdown";
    if (select.value === "Active")              select.classList.add("status-active");
    else if (select.value === "In Negotiation") select.classList.add("status-negotiation");
    else if (select.value === "Inactive")       select.classList.add("status-inactive");
}

function changeStatus(select, id) {
    applyStatusColor(select);
    if (id === undefined) return;
    const status = select.value;
    fetch(`update_booking.php?id=${id}&status=${encodeURIComponent(status)}`)
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                showToast(`Status updated to "${status}" ✅`);
            } else {
                showToast("Error: " + data);
            }
        })
        .catch(error => {
            console.error(error);
            showToast("Server error ❌");
        });
}

// ==================== DROPDOWN ====================
function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    document.querySelectorAll(".dropdown-content").forEach(menu => {
        if (menu.id !== id) menu.style.display = "none";
    });
    dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}

window.onclick = function(e) {
    if (e.target.closest('.confirm-panel')    || e.target.closest('.confirm-overlay'))    return;
    if (e.target.closest('.disapprove-panel') || e.target.closest('.disapprove-overlay')) return;
    if (!e.target.matches('.filter-btn') && !e.target.closest('.dropdown')) {
        document.querySelectorAll(".dropdown-content").forEach(menu => {
            menu.style.display = "none";
        });
    }
};

// ==================== FILTER TABLE ====================
function filterTable() {
    const input = document.getElementById("clientSearch").value.toLowerCase();
    const rows  = document.querySelectorAll("#clientTable tbody tr");
    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(input) ? "" : "none";
    });
}

// ==================== FILTER BY STATUS ====================
function filterByStatus(status) {
    const rows = document.querySelectorAll("#clientTable tbody tr");
    rows.forEach(row => {
        const cellText = row.cells[3]?.innerText.trim();
        row.style.display = (status === "all" || cellText?.includes(status)) ? "" : "none";
    });
    document.getElementById("filterMenu").style.display = "none";
}

// ==================== CONFIRMATION PANEL ====================
function openConfirmPanel(bookingId, companyName, truckType, transportType) {
    document.getElementById('cfBookingId').value              = bookingId;
    document.getElementById('confirmCompanyName').textContent = 'Booking #' + bookingId + ' — ' + companyName;
    document.getElementById('cfServiceType').value            = transportType || '';
    document.getElementById('cfFleetAssigned').value          = truckType || '';
    document.getElementById('cfFinalPrice').value             = '';
    document.getElementById('cfPaymentMethod').value          = '';
    document.getElementById('cfAdminNotes').value             = '';
    document.getElementById('cfSuccessMsg').style.display     = 'none';
    document.getElementById('cfSubmitBtn').disabled           = false;
    document.getElementById('cfSubmitBtn').textContent        = '✉️ Send Confirmation to Client';
    document.getElementById('downpaymentPreview').style.display = 'none';

    const d = new Date();
    d.setDate(d.getDate() + 3);
    document.getElementById('cfPaymentDeadline').value = d.toISOString().split('T')[0];

    document.getElementById('cfTerms').value =
        '1. A 30% downpayment is required to confirm the booking.\n' +
        '2. The downpayment must be settled within the payment deadline.\n' +
        '3. The remaining balance is due upon completion of service.\n' +
        '4. Cancellations made after downpayment will not be refunded.\n' +
        '5. 2K2J Services reserves the right to reschedule due to force majeure.';

    document.getElementById('confirmOverlay').classList.add('open');
}

function closeConfirmPanel() {
    document.getElementById('confirmOverlay').classList.remove('open');
}

function handleOverlayClick(e) {
    if (e.target === document.getElementById('confirmOverlay')) closeConfirmPanel();
}

function updateDownpayment() {
    const price   = parseFloat(document.getElementById('cfFinalPrice').value);
    const preview = document.getElementById('downpaymentPreview');
    if (!price || price <= 0) { preview.style.display = 'none'; return; }
    document.getElementById('downpaymentAmount').textContent =
        '₱' + (price * 0.30).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    preview.style.display = 'block';
}

async function submitConfirmation(e) {
    e.preventDefault();
    const btn = document.getElementById('cfSubmitBtn');
    btn.disabled    = true;
    btn.textContent = 'Sending...';

    const bookingId = parseInt(document.getElementById('cfBookingId').value);
    const payload = {
        booking_id:       bookingId,
        final_price:      parseFloat(document.getElementById('cfFinalPrice').value),
        payment_method:   document.getElementById('cfPaymentMethod').value,
        payment_deadline: document.getElementById('cfPaymentDeadline').value,
        service_type:     document.getElementById('cfServiceType').value,
        fleet_assigned:   document.getElementById('cfFleetAssigned').value,
        terms:            document.getElementById('cfTerms').value,
        admin_notes:      document.getElementById('cfAdminNotes').value,
    };

    try {
        const res  = await fetch('confirmation.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(payload)
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById('cfSuccessMsg').style.display = 'block';
            btn.disabled    = false;
            btn.textContent = '✉️ Send Confirmation to Client';

            document.querySelectorAll('.btn-send-confirm').forEach(b => {
                const oc = b.getAttribute('onclick') || '';
                if (oc.includes('openConfirmPanel(' + bookingId + ',')) {
                    const companyName = document.getElementById('confirmCompanyName')
                        .textContent.replace('Booking #' + bookingId + ' — ', '');
                    b.textContent = '✓ Update';
                    b.classList.add('sent');
                    b.setAttribute('onclick',
                        `openUpdatePanel(${bookingId}, '${companyName.replace(/'/g, "\\'")}')`
                    );
                }
            });

            setTimeout(() => closeConfirmPanel(), 1500);
        } else {
            alert('❌ ' + (data.message || 'Failed to send confirmation.'));
            btn.disabled    = false;
            btn.textContent = '✉️ Send Confirmation to Client';
        }
    } catch (err) {
        alert('❌ Server error. Please try again.');
        btn.disabled    = false;
        btn.textContent = '✉️ Send Confirmation to Client';
    }
}

// ==================== UPDATE NOTE PANEL ====================
function openUpdatePanel(bookingId, companyName) {
    document.getElementById('upBookingId').value             = bookingId;
    document.getElementById('updateCompanyName').textContent = 'Booking #' + bookingId + ' — ' + companyName;
    document.getElementById('upAdminNotes').value            = '';
    document.getElementById('upSuccessMsg').style.display    = 'none';
    document.getElementById('upSubmitBtn').disabled          = false;
    document.getElementById('upSubmitBtn').textContent       = '✉️ Send Update';
    document.getElementById('updateOverlay').classList.add('open');
}

function closeUpdatePanel() {
    document.getElementById('updateOverlay').classList.remove('open');
}

async function submitUpdate(e) {
    e.preventDefault();
    const btn = document.getElementById('upSubmitBtn');
    btn.disabled    = true;
    btn.textContent = 'Sending...';

    try {
        const res  = await fetch('confirmation.php', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify({
                booking_id:  parseInt(document.getElementById('upBookingId').value),
                admin_notes: document.getElementById('upAdminNotes').value,
                update_only: true
            })
        });
        const data = await res.json();

        if (data.success) {
            document.getElementById('upSuccessMsg').style.display = 'block';
            btn.disabled    = false;
            btn.textContent = '✉️ Send Update';
            setTimeout(() => closeUpdatePanel(), 1500);
        } else {
            alert('❌ ' + (data.message || 'Failed to send update.'));
            btn.disabled    = false;
            btn.textContent = '✉️ Send Update';
        }
    } catch (err) {
        alert('❌ Server error. Please try again.');
        btn.disabled    = false;
        btn.textContent = '✉️ Send Update';
    }
}

// ==================== MARK COMPLETED ====================
async function markCompleted(bookingId, companyName) {
    if (!confirm('Mark booking #' + bookingId + ' for "' + companyName + '" as Completed?')) return;
    try {
        const res  = await fetch('complete_booking.php?id=' + bookingId);
        const text = await res.text();
        if (text.trim() === 'success') {
            showToast('✅ Booking marked as Completed!');
            setTimeout(() => location.reload(), 1200);
        } else {
            alert('❌ ' + text);
        }
    } catch (err) {
        alert('❌ Server error. Please try again.');
    }
}