<?php
if (session_status() === PHP_SESSION_NONE) session_start();
include 'config.php';
header('Content-Type: application/json');

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$user_id = intval($_SESSION['user_id']);

$stmt = mysqli_prepare($conn,
    "SELECT
        b.booking_id,
        b.user_id,
        b.company_name,
        b.contact_person,
        b.contact_number,
        b.pickup_location,
        b.delivery_location,
        b.distance,
        b.truck_type,
        COALESCE(b.special_transport, b.transport_type, 'None') AS special_transport,
        COALESCE(b.frequency, b.booking_frequency, 'once')      AS frequency,
        b.start_date,
        b.end_date,
        b.cargo_weight,
        b.weight_unit,
        COALESCE(b.details, b.special_requirements, '')         AS details,
        COALESCE(b.status, b.booking_status, 'Pending')         AS status,
        b.created_at,
        b.disapproval_reason,
        b.disapproval_message,
        bc.final_price,
        bc.downpayment,
        bc.payment_method,
        bc.payment_deadline,
        bc.service_type,
        bc.fleet_assigned,
        bc.terms,
        bc.admin_notes,
        bc.progress_updates,
        bc.sent_at AS confirmation_sent_at
     FROM bookings b
     LEFT JOIN booking_confirmations bc ON bc.booking_id = b.booking_id
     WHERE b.user_id = ?
     ORDER BY b.booking_id DESC"
);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$bookings = [];
while ($row = mysqli_fetch_assoc($result)) {
    $bookings[] = $row;
}

echo json_encode(['success' => true, 'bookings' => $bookings]);
?>