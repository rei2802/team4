<?php
// admin/update_status.php
include '../config/connect.php';

$allowed = ['Active', 'In Negotiation', 'Inactive'];

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id     = (int)$_GET['id'];
    $status = $_GET['status'];

    if (!in_array($status, $allowed)) {
        die("Invalid status");
    }

    // Update BOTH status columns so they stay in sync
    $sql  = "UPDATE bookings SET status = ?, booking_status = ? WHERE booking_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $status, $status, $id);
    mysqli_stmt_execute($stmt);
    echo "success";
    exit();
}

echo "Missing parameters";
?>