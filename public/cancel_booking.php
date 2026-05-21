<?php
include 'config.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$data       = json_decode(file_get_contents('php://input'), true);
$booking_id = intval($data['booking_id'] ?? 0);
$user_id    = intval($_SESSION['user_id']);

$stmt = mysqli_prepare($conn,
    "UPDATE bookings SET booking_status='Cancelled'
     WHERE booking_id=? AND user_id=? AND booking_status='Pending'"
);
mysqli_stmt_bind_param($stmt, 'ii', $booking_id, $user_id);
mysqli_stmt_execute($stmt);

if (mysqli_stmt_affected_rows($stmt) > 0) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Booking not found or cannot be cancelled.']);
}
?>