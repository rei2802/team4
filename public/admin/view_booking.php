<?php
session_start();
include '../config.php';

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header('Location: ../user_login.php?error=unauthorized');
    exit;
}

$id = intval($_GET['id'] ?? 0);
if (!$id) {
    header('Location: dashboard.php');
    exit;
}

$stmt = mysqli_prepare($conn,
    "SELECT b.*,
        COALESCE(b.company_name,  u.company_name)   AS display_company,
        COALESCE(b.contact_person,u.contact_person)  AS display_contact,
        COALESCE(b.contact_number,u.contact_number)  AS display_phone,
        COALESCE(b.email,         u.email)           AS display_email,
        COALESCE(b.special_transport, b.transport_type) AS display_transport
     FROM bookings b
     LEFT JOIN users u ON u.user_id = b.user_id
     WHERE b.booking_id = ?"
);
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$row) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details | 2K2J</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/jpg" href="assets/logo.jpg">
    <style>
        body { background: #f0f2f5; font-family: 'Roboto', sans-serif; }

        .view-wrapper {
            max-width: 900px;
            margin: 40px auto;
            background: #fff;
            border-radius: 16px;
            padding: 48px 56px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        }

        .view-wrapper h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 36px;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 28px 40px;
        }

        .detail-item {}

        .detail-item .detail-label {
            font-size: 11px;
            font-weight: 700;
            color: #aaa;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 6px;
        }

        .detail-item .detail-value {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .detail-divider {
            grid-column: 1 / -1;
            border: none;
            border-top: 1px solid #f0f0f0;
            margin: 4px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 14px;
            border-radius: 99px;
            font-size: 14px;
            font-weight: 700;
        }
        .status-pending    { background: #fff3cd; color: #856404; }
        .status-active     { background: #d1f5d3; color: #155724; }
        .status-approved   { background: #d1f5d3; color: #155724; }
        .status-inactive   { background: #f0f0f0; color: #666; }
        .status-cancelled  { background: #fde8e8; color: #9b1c1c; }
        .status-disapproved{ background: #fde8e8; color: #9b1c1c; }
        .status-signed     { background: #dbeafe; color: #1e40af; }

        .back-btn {
            display: inline-block;
            margin-top: 40px;
            padding: 12px 28px;
            background: #1a1a1a;
            color: #fff;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            transition: background 0.2s;
        }
        .back-btn:hover { background: #333; }

        /* Nav logo fix */
        .navbar img { width: 50px; height: auto; }
        .navbar { background: #1a1a1a; padding: 12px 0; }
        .nav-container { display:flex; align-items:center; justify-content:space-between; max-width:1200px; margin:0 auto; padding:0 24px; }
        .nav-links { display:flex; align-items:center; gap:24px; }
        .nav-links a { color:#fff; text-decoration:none; font-size:14px; }
        .login-btn { background:#e05a00; color:#fff; border:none; border-radius:6px; padding:8px 18px; font-weight:700; cursor:pointer; }
        .nav-username-btn { border:2px solid #fff; border-radius:20px; padding:5px 16px; font-weight:700; }

        @media (max-width: 600px) {
            .view-wrapper { padding: 28px 20px; }
            .detail-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>


<div class="view-wrapper">
    <h1>Request Details</h1>

    <div class="detail-grid">

        <!-- Row 1 -->
        <div class="detail-item">
            <div class="detail-label">Company Name</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['display_company'] ?: '—'); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Contact Person</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['display_contact'] ?: '—'); ?></div>
        </div>

        <!-- Row 2 -->
        <div class="detail-item">
            <div class="detail-label">Phone</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['display_phone'] ?: '—'); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Email</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['display_email'] ?: '—'); ?></div>
        </div>

        <hr class="detail-divider">

        <!-- Row 3 -->
        <div class="detail-item">
            <div class="detail-label">Pickup Location</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['pickup_location'] ?: '—'); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Delivery Location</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['delivery_location'] ?: '—'); ?></div>
        </div>

        <!-- Row 4 -->
        <div class="detail-item">
            <div class="detail-label">Distance</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['distance'] ?: '—'); ?> KM</div>
        </div>
        <div class="detail-item" style="display:flex; gap:40px;">
            <div>
                <div class="detail-label">Start Date</div>
                <div class="detail-value"><?php echo htmlspecialchars($row['start_date'] ?: '—'); ?></div>
            </div>
            <div>
                <div class="detail-label">End Date</div>
                <div class="detail-value"><?php echo htmlspecialchars($row['end_date'] ?: '—'); ?></div>
            </div>
        </div>

        <hr class="detail-divider">

        <!-- Row 5 -->
        <div class="detail-item">
            <div class="detail-label">Truck Type</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['truck_type'] ?: '—'); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Special Transport</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['display_transport'] ?: '—'); ?></div>
        </div>

        <!-- Row 6 -->
        <div class="detail-item">
            <div class="detail-label">Cargo Weight</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['cargo_weight'] ?: '—'); ?> <?php echo htmlspecialchars($row['weight_unit'] ?: 'kg'); ?></div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Frequency</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['booking_frequency'] ?: $row['frequency'] ?: '—'); ?></div>
        </div>

        <hr class="detail-divider">

        <!-- Row 7 -->
        <div class="detail-item">
            <div class="detail-label">Status</div>
            <div class="detail-value">
                <?php
                $status = strtolower($row['booking_status'] ?: $row['status'] ?: 'pending');
                echo '<span class="status-badge status-' . htmlspecialchars($status) . '">'
                     . ucfirst(htmlspecialchars($status)) . '</span>';
                ?>
            </div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Created At</div>
            <div class="detail-value"><?php echo htmlspecialchars($row['created_at'] ?: '—'); ?></div>
        </div>

        <!-- Row 8: Notes (full width) -->
        <?php if (!empty($row['special_requirements']) || !empty($row['details'])): ?>
        <hr class="detail-divider">
        <div class="detail-item" style="grid-column:1/-1;">
            <div class="detail-label">Details / Notes</div>
            <div class="detail-value" style="font-size:16px; font-weight:400; color:#333; line-height:1.6;">
                <?php echo nl2br(htmlspecialchars($row['special_requirements'] ?: $row['details'] ?: '')); ?>
            </div>
        </div>
        <?php endif; ?>

    </div>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>