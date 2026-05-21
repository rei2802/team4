<?php
// C:\xampp\htdocs\2k2j\admin\update_booking.php
include '../config.php';

$allowed = ['Pending','Active','In Negotiation','Inactive'];

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = (int)$_GET['id'];
    $status = $_GET['status'];

    if (!in_array($status, $allowed)) {
        echo "Invalid status";
        exit();
    }

    $sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "si", $status, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "MySQL Error: " . mysqli_error($conn);
    }
    exit();
}
?>
