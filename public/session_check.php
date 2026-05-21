<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id']) || isset($_SESSION['isAdmin'])) {
    echo json_encode([
        'loggedIn' => true,
        'isAdmin'  => $_SESSION['isAdmin'] ?? false,
        'user_id'  => $_SESSION['user_id'] ?? null,
        'name'     => $_SESSION['username'] ?? $_SESSION['adminName'] ?? '',
    ]);
} else {
    echo json_encode(['loggedIn' => false]);
}
?>