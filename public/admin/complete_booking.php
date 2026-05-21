<?php
session_start();
include '../config.php';

// Admin guard
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    echo "Unauthorized";
    exit;
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $stmt = mysqli_prepare($conn,
        "UPDATE bookings SET booking_status = 'Completed', status = 'Completed' WHERE booking_id = ?"
    );

    if (!$stmt) {
        echo "DB error: " . mysqli_error($conn);
        exit;
    }

    mysqli_stmt_bind_param($stmt, 'i', $id);

    if (mysqli_stmt_execute($stmt)) {
        echo "success";
    } else {
        echo "MySQL Error: " . mysqli_stmt_error($stmt);
    }
    exit;
}

echo "Missing parameters";
exit;
?>