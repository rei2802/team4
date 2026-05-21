<?php
include '../config.php';

$allowed = ['Pending', 'Active', 'In Negotiation', 'Inactive', 'Signed'];

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id     = (int)$_GET['id'];
    $status = $_GET['status'];

    if (!in_array($status, $allowed)) {
        echo "Invalid status";
        exit();
    }

    $sql  = "UPDATE bookings SET booking_status = ? WHERE booking_id = ?"; // ← both fixed
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "MySQL Error: " . mysqli_error($conn);
    }
    exit();
}

echo "Missing parameters";
exit();
?>