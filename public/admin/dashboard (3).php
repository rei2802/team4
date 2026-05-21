<?php
session_start();
include '../config.php';

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header('Location: ../user_login.php?error=unauthorized');
    exit;
}
$adminRole = strtolower($_SESSION['adminRole'] ?? 'admin');
$isOwner   = ($adminRole === 'owner');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2K2J Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="assets/logo.jpg">
</head>

<body class="dashboard-page">

<!-- NAVBAR -->
<header class="navbar">
    <div class="nav-container">
        <div class="logo">
            <a href="../index.php"><img src="assets/logo.jpg" alt="2K2J Logo"></a>
        </div>
        <div class="hamburger" id="hamburger">
            <span></span><span></span><span></span>
        </div>
        <nav class="nav-links">
            <a href="../index.php">Home</a>
            <a href="../index.php#about">About Us</a>
            <a href="../index.php#services">Services</a>
            <a href="../fleets.php">Fleet</a>
            <a href="../contact.php">Contact Us</a>
            <a href="#" class="nav-username-btn" style="pointer-events:none;">
                <?php echo htmlspecialchars($_SESSION['adminName']); ?>
            </a>
            <button class="login-btn" onclick="adminLogout()">LOG OUT</button>
        </nav>
    </div>
</header>

<!-- MAIN WRAPPER — always wraps everything below navbar -->
<main class="dashboard-wrapper">

    <header class="dashboard-header">
        <h1><?php echo $isOwner ? 'Owner Dashboard' : 'Admin Dashboard'; ?></h1>
        <p>Welcome, <?php echo htmlspecialchars($_SESSION['adminName']); ?></p>
    </header>

    <?php
    $total_query   = "SELECT COUNT(*) as total FROM bookings WHERE booking_status NOT IN ('Pending','Signed','Disapproved','Cancelled')";
    $total_res     = mysqli_fetch_assoc(mysqli_query($conn, $total_query));
    $pending_query = "SELECT COUNT(*) as total FROM bookings WHERE booking_status = 'Pending'";
    $pending_res   = mysqli_fetch_assoc(mysqli_query($conn, $pending_query));
    $signed_query  = "SELECT COUNT(*) as total FROM bookings WHERE booking_status IN ('Signed','Completed','Active','In Negotiation','Inactive')";
    $signed_res    = mysqli_fetch_assoc(mysqli_query($conn, $signed_query));
    ?>

    <?php if ($isOwner): ?>
    <section class="metrics-grid">
        <div class="card">
            <img src="assets/truck-sticker.png" class="card-icon-img" alt="Truck">
            <div class="card-content">
                <span class="label">Total Clients</span>
                <span class="value" id="totalClientsCount"><?php echo $total_res['total']; ?></span>
            </div>
        </div>
        <div class="card">
            <img src="assets/clock.png" class="card-icon-img" alt="Pending">
            <div class="card-content">
                <span class="label">Pending Approval</span>
                <span class="value" id="pendingApprovalCount"><?php echo $pending_res['total']; ?></span>
            </div>
        </div>
        <div class="card">
            <img src="assets/agree.png" class="card-icon-img" alt="Agreements">
            <div class="card-content">
                <span class="label">Signed Agreements</span>
                <span class="value" id="signedAgreementsCount"><?php echo $signed_res['total']; ?></span>
            </div>
        </div>
        <div class="card">
            <img src="assets/dwheel.png" class="card-icon-img" alt="Fleet">
            <div class="card-content">
                <span class="label">Partner Fleet Units</span>
                <span class="value">9</span>
                <span class="trend positive">+1 6 Wheeler</span>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- ACTIVE PARTNERSHIPS TABLE -->
    <section class="table-container">
        <div class="table-controls">
            <h3>Active Partnerships</h3>
            <div class="table-actions">
                <div class="search-wrapper">
                    <img src="assets/search.png" class="search-icon-img" alt="Search">
                    <input type="text" id="clientSearch" placeholder="Search..." onkeyup="filterTable()">
                </div>
                <div class="dropdown">
                    <button class="filter-btn" onclick="toggleDropdown('filterMenu')">▽ Filter</button>
                    <div id="filterMenu" class="dropdown-content">
                        <a href="#" onclick="filterByStatus('all')">All Statuses</a>
                        <a href="#" onclick="filterByStatus('Active')">Active</a>
                        <a href="#" onclick="filterByStatus('In Negotiation')">In Negotiation</a>
                        <a href="#" onclick="filterByStatus('Inactive')">Inactive</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-wrapper">
            <table id="clientTable">
                <thead>
                    <tr>
                        <th>Client Company Name</th>
                        <th>Current Contract Phase</th>
                        <th>Primary Contact</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
<?php
$query = "SELECT b.*,
            COALESCE(b.contact_person, u.contact_person) AS display_contact,
            COALESCE(b.contact_number, u.contact_number) AS display_phone,
            COALESCE(b.company_name,  u.company_name)    AS display_company,
            (SELECT id FROM booking_confirmations WHERE booking_id = b.booking_id LIMIT 1) AS has_confirmation
          FROM bookings b
          LEFT JOIN users u ON u.user_id = b.user_id
          WHERE b.booking_status IN ('Active','In Negotiation','Inactive')
          ORDER BY b.booking_id DESC";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $hasSent = !empty($row['has_confirmation']);
?>
<tr>
    <td><?php echo htmlspecialchars($row['display_company'] ?? '—'); ?></td>
    <td>Signed</td>
    <td>
        <?php echo htmlspecialchars($row['display_contact'] ?? '—'); ?><br>
        <small><?php echo htmlspecialchars($row['display_phone'] ?? '—'); ?></small>
    </td>
    <td>
        <select class="status-dropdown" onchange="changeStatus(this, <?php echo $row['booking_id']; ?>)">
            <?php foreach (['Active', 'In Negotiation', 'Inactive'] as $s): ?>
                <option value="<?php echo $s; ?>" <?php echo ($row['booking_status'] === $s) ? 'selected' : ''; ?>>
                    <?php echo $s; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </td>
    <td class="action-cell">
        <a href="view_booking.php?id=<?php echo $row['booking_id']; ?>">
            <img src="assets/view.png" class="action-icon-img" title="View">
        </a>
        <img src="assets/check.png" class="action-icon-img" title="Mark Completed"
             style="cursor:pointer;"
             onclick="markCompleted(<?php echo $row['booking_id']; ?>, '<?php echo addslashes($row['display_company'] ?? ''); ?>')">
        <button class="btn-send-confirm <?php echo $hasSent ? 'sent' : ''; ?>"
                onclick="<?php echo $hasSent
                    ? "openUpdatePanel({$row['booking_id']}, '" . addslashes($row['display_company'] ?? '') . "')"
                    : "openConfirmPanel({$row['booking_id']}, '" . addslashes($row['display_company'] ?? '') . "', '" . addslashes($row['truck_type'] ?? '') . "', '" . addslashes($row['special_transport'] ?? '') . "')"
                ?>">
            <?php echo $hasSent ? '✓ Update' : '📋 Send Confirmation'; ?>
        </button>
    </td>
</tr>
<?php }} else { echo "<tr><td colspan='5'>No active records found.</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- PENDING REQUESTS — includes guest bookings via guest_bookings join -->
    <section class="requests-container">
        <h3>Pending Approval Requests</h3>
        <div id="pendingRequestsContainer" class="requests-grid">
<?php
$query = "SELECT b.*,
            COALESCE(b.company_name,  u.company_name,  gb.company_name)   AS display_company,
            COALESCE(b.contact_person,u.contact_person,gb.contact_person)  AS display_contact,
            COALESCE(b.contact_number,u.contact_number,gb.contact_number)  AS display_phone,
            (SELECT id FROM booking_confirmations WHERE booking_id = b.booking_id LIMIT 1) AS has_confirmation,
            CASE WHEN b.user_id IS NULL THEN 1 ELSE 0 END AS is_guest
          FROM bookings b
          LEFT JOIN users u         ON u.user_id       = b.user_id
          LEFT JOIN guest_bookings gb ON gb.booking_id = b.booking_id
          WHERE b.booking_status = 'Pending'
          ORDER BY b.booking_id DESC";
$result = mysqli_query($conn, $query);
if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $hasSent  = !empty($row['has_confirmation']);
        $isGuest  = !empty($row['is_guest']);
?>
<div class="request-card" id="req-card-<?php echo $row['booking_id']; ?>">
    <h4>
        <?php echo htmlspecialchars($row['display_company'] ?? '—'); ?>
        <?php if ($isGuest): ?>
            <span style="font-size:11px;font-weight:600;color:#f7931e;background:#fff3e0;padding:2px 8px;border-radius:99px;margin-left:6px;">Guest</span>
        <?php endif; ?>
    </h4>
    <div class="request-info">
        <p><strong>Contact:</strong> <span><?php echo htmlspecialchars($row['display_contact'] ?? '—'); ?></span></p>
        <p><strong>Phone:</strong> <span><?php echo htmlspecialchars($row['display_phone'] ?? '—'); ?></span></p>
    </div>
    <div class="card-actions">
        <a href="view_booking.php?id=<?php echo $row['booking_id']; ?>" class="btn-view">View</a>
        <button class="btn-accept"
            onclick="acceptRequest(<?php echo $row['booking_id']; ?>, '<?php echo addslashes($row['display_company'] ?? ''); ?>')">
            Accept
        </button>
        <button class="btn-send-confirm <?php echo $hasSent ? 'sent' : ''; ?>"
                onclick="openConfirmPanel(<?php echo $row['booking_id']; ?>, '<?php echo addslashes($row['display_company'] ?? ''); ?>', '<?php echo addslashes($row['truck_type'] ?? ''); ?>', '<?php echo addslashes($row['special_transport'] ?? ''); ?>')">
            <?php echo $hasSent ? '✓ Resend' : '📋 Send Confirmation'; ?>
        </button>
        <button class="btn-disapprove"
                onclick="openDisapprovePanel(<?php echo $row['booking_id']; ?>, '<?php echo addslashes($row['display_company'] ?? ''); ?>')">
            ✕ Disapprove
        </button>
    </div>
</div>
<?php }} else { echo "<p>No pending requests found.</p>"; } ?>
        </div>
    </section>

</main>
<!-- END MAIN WRAPPER -->

<!-- CONFIRMATION PANEL -->
<div class="confirm-overlay" id="confirmOverlay" onclick="handleOverlayClick(event)">
    <div class="confirm-panel" id="confirmPanel">
        <button class="confirm-close" onclick="closeConfirmPanel()">×</button>
        <h2>📋 Send Booking Confirmation</h2>
        <p class="confirm-subtitle" id="confirmCompanyName">—</p>
        <form class="confirm-form" id="confirmForm" onsubmit="submitConfirmation(event)">
            <input type="hidden" id="cfBookingId" value="">
            <label>Final Agreed Price (₱) *</label>
            <input type="number" id="cfFinalPrice" placeholder="e.g. 15000" min="1" step="0.01" oninput="updateDownpayment()" required>
            <div class="downpayment-preview" id="downpaymentPreview" style="display:none;">
                30% Downpayment Required:
                <strong id="downpaymentAmount">₱0.00</strong>
                <span style="font-size:12px;color:#a06030;">Due within 3 days of confirmation</span>
            </div>
            <hr class="confirm-divider">
            <label>Payment Method *</label>
            <select id="cfPaymentMethod" required>
                <option value="">Select payment method</option>
                <option value="GCash">GCash</option>
                <option value="PayPal">PayPal</option>
                <option value="Maya">Maya</option>
            </select>
            <label>Payment Deadline *</label>
            <input type="date" id="cfPaymentDeadline" required>
            <hr class="confirm-divider">
            <label>Service Type *</label>
            <input type="text" id="cfServiceType" placeholder="e.g. Freight Delivery, Hauling" required>
            <label>Fleet / Truck Assigned *</label>
            <input type="text" id="cfFleetAssigned" placeholder="e.g. 6-Wheeler Unit 3" required>
            <hr class="confirm-divider">
            <label>Terms & Conditions *</label>
            <textarea id="cfTerms" required></textarea>
            <label>Admin Notes <span style="font-weight:400;text-transform:none;">(optional)</span></label>
            <textarea id="cfAdminNotes" style="min-height:60px;"></textarea>
            <button type="submit" class="confirm-submit-btn" id="cfSubmitBtn">✉️ Send Confirmation to Client</button>
            <div class="confirm-success-msg" id="cfSuccessMsg">
                ✅ Confirmation sent! The client can now see it in their My Bookings page.
            </div>
        </form>
    </div>
</div>

<!-- UPDATE NOTE PANEL -->
<div class="confirm-overlay" id="updateOverlay" onclick="if(event.target===this)closeUpdatePanel()">
    <div class="confirm-panel" id="updatePanel">
        <button class="confirm-close" onclick="closeUpdatePanel()">×</button>
        <h2>📝 Send Update to Client</h2>
        <p class="confirm-subtitle" id="updateCompanyName">—</p>
        <form class="confirm-form" onsubmit="submitUpdate(event)">
            <input type="hidden" id="upBookingId" value="">
            <label>Message / Note <span style="font-weight:400;text-transform:none;">(visible to client)</span></label>
            <textarea id="upAdminNotes" placeholder="e.g. Your truck has been dispatched..." style="min-height:160px;" required></textarea>
            <button type="submit" class="confirm-submit-btn" id="upSubmitBtn">✉️ Send Update</button>
            <div class="confirm-success-msg" id="upSuccessMsg">✅ Update sent!</div>
        </form>
    </div>
</div>

<!-- DISAPPROVAL PANEL -->
<div class="disapprove-overlay" id="disapproveOverlay" onclick="if(event.target===this)closeDisapprovePanel()">
    <div class="disapprove-panel">
        <button class="dp-close" onclick="closeDisapprovePanel()">×</button>
        <h2>Disapprove Booking</h2>
        <p class="dp-subtitle" id="dpSubtitle">Booking — Company Name</p>
        <input type="hidden" id="dpBookingId" value="">
        <div class="dp-chips">
            <span class="dp-chip" onclick="selectChip(this, 'Out of available fleet units')">Out of fleet units</span>
            <span class="dp-chip" onclick="selectChip(this, 'Service not available in this area')">Service not available</span>
            <span class="dp-chip" onclick="selectChip(this, 'Route not serviceable')">Route not serviceable</span>
            <span class="dp-chip" onclick="selectChip(this, 'Schedule conflict')">Schedule conflict</span>
            <span class="dp-chip" onclick="selectChip(this, 'Cargo type not allowed')">Cargo not allowed</span>
            <span class="dp-chip" onclick="selectChip(this, 'Other')">Other</span>
        </div>
        <form class="dp-form" id="disapproveForm" onsubmit="submitDisapproval(event)">
            <div class="dp-field">
                <label>Reason for Disapproval *</label>
                <select id="dpReason" onchange="syncSelectToChips(this.value)" required>
                    <option value="">— Select a reason —</option>
                    <option value="Out of available fleet units">Out of available fleet units</option>
                    <option value="Service not available in this area">Service not available in this area</option>
                    <option value="Route not serviceable">Route not serviceable</option>
                    <option value="Schedule conflict">Schedule conflict</option>
                    <option value="Cargo type not allowed">Cargo type not allowed</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="dp-field">
                <label>Message to Client <span style="font-weight:400;text-transform:none;">(optional)</span></label>
                <textarea id="dpMessage" placeholder="e.g. All our 6-wheeler units are currently booked..."></textarea>
            </div>
            <hr class="dp-divider">
            <div class="dp-warning">
                <strong>⚠️ This action cannot be undone.</strong>
                The booking will be marked as Disapproved and the client will see the reason in My Bookings.
            </div>
            <button type="submit" class="dp-submit-btn" id="dpSubmitBtn">Confirm Disapproval</button>
            <div class="dp-success-msg" id="dpSuccessMsg">✅ Booking disapproved. Client has been notified.</div>
        </form>
    </div>
</div>

<div id="toast"></div>
<script src="admin.js"></script>
<script>
function openDisapprovePanel(bookingId, companyName) {
    document.getElementById('dpBookingId').value       = bookingId;
    document.getElementById('dpSubtitle').textContent  = 'Booking #' + bookingId + ' — ' + companyName;
    document.getElementById('dpReason').value          = '';
    document.getElementById('dpMessage').value         = '';
    document.getElementById('dpSubmitBtn').disabled    = false;
    document.getElementById('dpSubmitBtn').textContent = 'Confirm Disapproval';
    document.getElementById('dpSuccessMsg').style.display = 'none';
    document.querySelectorAll('.dp-chip').forEach(c => c.classList.remove('selected'));
    document.getElementById('disapproveOverlay').classList.add('open');
}
function closeDisapprovePanel() { document.getElementById('disapproveOverlay').classList.remove('open'); }
function selectChip(chip, value) {
    document.querySelectorAll('.dp-chip').forEach(c => c.classList.remove('selected'));
    chip.classList.add('selected');
    document.getElementById('dpReason').value = value;
}
function syncSelectToChips(value) {
    document.querySelectorAll('.dp-chip').forEach(c => {
        c.classList.toggle('selected', c.getAttribute('onclick').includes("'" + value + "'"));
    });
}
async function submitDisapproval(e) {
    e.preventDefault();
    const btn     = document.getElementById('dpSubmitBtn');
    const id      = parseInt(document.getElementById('dpBookingId').value);
    const reason  = document.getElementById('dpReason').value.trim();
    const message = document.getElementById('dpMessage').value.trim();
    if (!reason) { document.getElementById('dpReason').focus(); return; }
    btn.disabled = true; btn.textContent = 'Processing...';
    try {
        const res  = await fetch('disapprove_booking.php', {
            method: 'POST', headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ booking_id: id, disapproval_reason: reason, disapproval_message: message })
        });
        const data = await res.json();
        if (data.success) {
            document.getElementById('dpSuccessMsg').style.display = 'block';
            btn.textContent = '✓ Disapproved';
            setTimeout(() => {
                const card = document.getElementById('req-card-' + id);
                if (card) {
                    card.style.transition = 'opacity 0.4s, transform 0.4s';
                    card.style.opacity = '0'; card.style.transform = 'scale(0.95)';
                    setTimeout(() => card.remove(), 400);
                }
                closeDisapprovePanel();
                const badge = document.getElementById('pendingApprovalCount');
                if (badge) badge.textContent = Math.max(0, parseInt(badge.textContent) - 1);
            }, 1400);
        } else {
            alert('❌ ' + (data.message || 'Could not disapprove.')); btn.disabled = false;
            btn.textContent = 'Confirm Disapproval';
        }
    } catch (err) { alert('❌ Server error.'); btn.disabled = false; btn.textContent = 'Confirm Disapproval'; }
}
</script>
</body>
</html>