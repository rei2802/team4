<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'config.php';

// 1. Identify the structural context state of the client connection
$isLoggedIn = !empty($_SESSION['user_id']);
$user_id = $isLoggedIn ? $_SESSION['user_id'] : null; // Safely assign null for guest entries

// 2. Capture form input data parameters safely
$company_name     = $_POST['company_name']      ?? '';
$contact_person   = trim($_POST['contact_person'] ?? '');
$contact_number   = $_POST['contact_number']    ?? '';
$email            = $_POST['email']             ?? '';
$pickup           = $_POST['pickup_location']   ?? '';
$delivery         = $_POST['delivery_location'] ?? '';
$distanceKm       = floatval($_POST['distance'] ?? 0);
$frequency        = $_POST['frequency']         ?? 'once';
$startDate        = $_POST['start_date']        ?? '';
$endDate          = $_POST['end_date']          ?? '';
$truckType        = $_POST['truck_type']        ?? '';
$specialTransport = $_POST['special_transport'] ?? 'None';
$cargoWeight      = floatval($_POST['cargo_weight'] ?? 0);
$weightUnit       = $_POST['weight_unit']       ?? 'kg';
$details          = $_POST['details']           ?? '';

// 3. Run validation checks
if (!$isLoggedIn && empty($contact_person)) {
    echo "error: login_required";
    exit;
}

if (empty($contact_person) || empty($contact_number) || empty($pickup) || empty($delivery) || empty($truckType)) {
    echo "error: missing required fields";
    exit;
}

// 4. Prepare your SQL statement
$stmt = $conn->prepare("
    INSERT INTO bookings (
        user_id, company_name, contact_person, contact_number, email,
        pickup_location, delivery_location, distance, frequency,
        start_date, end_date, truck_type, special_transport,
        cargo_weight, weight_unit, details, status,
        transport_type, booking_frequency, booking_status, special_requirements
    ) VALUES (
        ?, ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, ?,
        ?, ?, ?, 'Pending',
        ?, ?, 'Pending', ?
    )
");

// 19 parameters matching your 19 placeholder values
$stmt->bind_param(
    "issssssdsssssdsssss",
    $user_id,
    $company_name,
    $contact_person,
    $contact_number,
    $email,
    $pickup,
    $delivery,
    $distanceKm,
    $frequency,
    $startDate,
    $endDate,
    $truckType,
    $specialTransport,
    $cargoWeight,
    $weightUnit,
    $details,
    $specialTransport,  // transport_type
    $frequency,         // booking_frequency
    $details            // special_requirements
);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error: " . $stmt->error;
}
?>