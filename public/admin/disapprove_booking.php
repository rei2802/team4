<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
include '../config.php';
date_default_timezone_set('Asia/Manila');

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$data    = json_decode(file_get_contents('php://input'), true);
$id      = intval($data['booking_id']        ?? 0);
$reason  = trim($data['disapproval_reason']  ?? '');
$message = trim($data['disapproval_message'] ?? '');

if (!$id || !$reason) {
    echo json_encode(['success' => false, 'message' => 'Missing booking ID or reason.']);
    exit;
}

mysqli_query($conn, "ALTER TABLE bookings ADD COLUMN IF NOT EXISTS disapproval_message TEXT NULL");

$stmt = mysqli_prepare($conn,
    "UPDATE bookings
     SET booking_status      = 'Disapproved',
         status              = 'Disapproved',
         disapproval_reason  = ?,
         disapproval_message = ?
     WHERE booking_id = ?"
);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed: ' . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'ssi', $reason, $message, $id);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Execute failed: ' . mysqli_stmt_error($stmt)]);
}
?>