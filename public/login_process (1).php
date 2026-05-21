<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 0);
session_start();
include 'config.php';
ob_clean();
header('Content-Type: application/json');

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

$data       = json_decode(file_get_contents('php://input'), true);
$identifier = trim($data['phone'] ?? '');
$password   = $data['password'] ?? '';

if (!$identifier || !$password) {
    echo json_encode(['success' => false, 'message' => 'Please enter your details.']);
    exit;
}

// 1. Check admin_users
$stmt = mysqli_prepare($conn,
    "SELECT admin_id, username, password, role FROM admin_users WHERE username = ?"
);
mysqli_stmt_bind_param($stmt, 's', $identifier);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$admin  = mysqli_fetch_assoc($result);

// Debug: log what we found
error_log("Admin lookup for '$identifier': " . ($admin ? "found, hash=" . substr($admin['password'],0,20) : "not found"));
error_log("password_verify result: " . ($admin ? var_export(password_verify($password, $admin['password']), true) : 'n/a'));

if ($admin && password_verify($password, $admin['password'])) {
    $role = strtolower(trim($admin['role'] ?? 'admin'));
    $_SESSION['isAdmin']   = true;
    $_SESSION['adminName'] = $admin['username'];
    $_SESSION['adminRole'] = $role;
    echo json_encode([
        'success'    => true,
        'role'       => $role,
        'isAdmin'    => true,
        'name'       => $admin['username'],
        'user_id'    => $admin['admin_id'],
        'admin_role' => $role
    ]);
    exit;
}

// 2. Check regular users
$stmt2 = mysqli_prepare($conn,
    "SELECT user_id, username, contact_number, company_name, contact_person, email, password
     FROM users WHERE username = ? OR contact_number = ? LIMIT 1"
);
mysqli_stmt_bind_param($stmt2, 'ss', $identifier, $identifier);
mysqli_stmt_execute($stmt2);
$result2 = mysqli_stmt_get_result($stmt2);
$user    = mysqli_fetch_assoc($result2);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['isAdmin']  = false;
    $_SESSION['user_id']  = $user['user_id'];
    $_SESSION['username'] = $user['username'] ?: $user['contact_person'] ?: $user['company_name'];
    echo json_encode([
        'success' => true,
        'role'    => 'user',
        'isAdmin' => false,
        'name'    => $user['username'] ?: $user['contact_person'] ?: $user['company_name'],
        'phone'   => $user['contact_number'],
        'user_id' => $user['user_id']
    ]);
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid credentials.']);
?>