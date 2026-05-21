<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');

if (!empty($_SESSION['user_id']) || !empty($_SESSION['isAdmin'])) {
    echo json_encode([
        'logged_in'  => true,
        'isAdmin'    => $_SESSION['isAdmin']   ?? false,
        'is_admin'   => $_SESSION['isAdmin']   ?? false,  // both keys
        'admin_role' => $_SESSION['adminRole'] ?? 'admin',
        'name'       => $_SESSION['username']  ?? $_SESSION['adminName'] ?? 'User',
        'user_id'    => $_SESSION['user_id']   ?? null,
    ]);
} else {
    echo json_encode(['logged_in' => false]);
}
?>