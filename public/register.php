<?php
error_reporting(0);
ini_set('display_errors', 0);
include 'config.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$name     = trim($data['name']     ?? '');
$phone    = trim($data['phone']    ?? '');
$password = $data['password']      ?? '';

if (!$name || !$phone || !$password) {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}

if (strlen($phone) < 10) {
    echo json_encode(['success' => false, 'message' => 'Invalid phone number.']);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters.']);
    exit;
}

/*
  login_process.php queries:
    username = ? OR contact_number = ?
  and reads: username, contact_number, contact_person, company_name

  So we store:
    username       = the display name the user typed
    contact_number = the phone number
    password       = hashed password
*/

// Check if contact_number already exists
$check = mysqli_prepare($conn,
    "SELECT user_id FROM users WHERE contact_number = ? OR username = ?"
);
mysqli_stmt_bind_param($check, 'ss', $phone, $name);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);

if (mysqli_stmt_num_rows($check) > 0) {
    echo json_encode(['success' => false, 'message' => 'An account with this phone number or username already exists.']);
    exit;
}
mysqli_stmt_close($check);

// Hash and insert using columns that login_process.php actually reads
$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = mysqli_prepare($conn,
    "INSERT INTO users (username, contact_number, password) VALUES (?, ?, ?)"
);

if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'DB error: ' . mysqli_error($conn)]);
    exit;
}

mysqli_stmt_bind_param($stmt, 'sss', $name, $phone, $hashed);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true, 'message' => 'Account created! You can now log in.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Registration failed: ' . mysqli_stmt_error($stmt)]);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>