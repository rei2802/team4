<?php
include '../config.php';
date_default_timezone_set('Asia/Manila');
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$booking_id  = intval($data['booking_id'] ?? 0);
$update_only = !empty($data['update_only']);

// ── UPDATE ONLY: append timestamped progress update ──
if ($update_only) {
    if (!$booking_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid booking.']);
        exit;
    }

    $new_note = trim($data['admin_notes'] ?? '');
    if (!$new_note) {
        echo json_encode(['success' => false, 'message' => 'Note cannot be empty.']);
        exit;
    }

    // Get existing progress updates
    $fetch = mysqli_prepare($conn,
        "SELECT progress_updates FROM booking_confirmations WHERE booking_id = ?"
    );
    mysqli_stmt_bind_param($fetch, 'i', $booking_id);
    mysqli_stmt_execute($fetch);
    $row      = mysqli_fetch_assoc(mysqli_stmt_get_result($fetch));
    $existing = trim($row['progress_updates'] ?? '');

    // Append: message|timestamp
    $timestamp = date('M d, Y h:i A');
    $appended  = $existing . ($existing ? "\n" : '') . "{$new_note}|{$timestamp}";

    $stmt = mysqli_prepare($conn,
        "UPDATE booking_confirmations SET progress_updates = ?, sent_at = NOW() WHERE booking_id = ?"
    );
    mysqli_stmt_bind_param($stmt, 'si', $appended, $booking_id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    }
    exit;
}

// ── FULL CONFIRMATION ──
$final_price      = floatval($data['final_price']    ?? 0);
$payment_method   = trim($data['payment_method']     ?? '');
$payment_deadline = trim($data['payment_deadline']   ?? '');
$service_type     = trim($data['service_type']       ?? '');
$fleet_assigned   = trim($data['fleet_assigned']     ?? '');
$terms            = trim($data['terms']              ?? '');
$admin_notes      = trim($data['admin_notes']        ?? '');

if (!$booking_id || !$final_price || !$payment_method || !$payment_deadline || !$service_type || !$fleet_assigned || !$terms) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$downpayment = $final_price * 0.30;

$check = mysqli_prepare($conn, "SELECT id FROM booking_confirmations WHERE booking_id = ?");
mysqli_stmt_bind_param($check, 'i', $booking_id);
mysqli_stmt_execute($check);
$existing = mysqli_stmt_get_result($check);

if (mysqli_num_rows($existing) > 0) {
    $stmt = mysqli_prepare($conn,
        "UPDATE booking_confirmations
         SET final_price=?, downpayment=?, payment_method=?, payment_deadline=?,
             service_type=?, fleet_assigned=?, terms=?, admin_notes=?, sent_at=NOW()
         WHERE booking_id=?"
    );
    mysqli_stmt_bind_param($stmt, 'ddssssssi',
        $final_price, $downpayment, $payment_method, $payment_deadline,
        $service_type, $fleet_assigned, $terms, $admin_notes, $booking_id
    );
} else {
    $stmt = mysqli_prepare($conn,
        "INSERT INTO booking_confirmations
            (booking_id, final_price, downpayment, payment_method, payment_deadline,
             service_type, fleet_assigned, terms, admin_notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );
    mysqli_stmt_bind_param($stmt, 'iddssssss',
        $booking_id, $final_price, $downpayment, $payment_method, $payment_deadline,
        $service_type, $fleet_assigned, $terms, $admin_notes
    );
}

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'downpayment' => $downpayment]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}
?>