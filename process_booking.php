<?php
session_start();
require 'db_connection.php';

// Get form data
$location = $_POST['location'];
$spot_id = $_POST['spot_id']; // New spot_id field
$car_number = $_POST['car_number'];
$booking_date = $_POST['booking_date'];
$start_time = $_POST['start_time'];
$duration_hours = (int)$_POST['duration_hours'];

// Calculate end time
$start_datetime = new DateTime("$booking_date $start_time");
$end_datetime = clone $start_datetime;
$end_datetime->add(new DateInterval("PT{$duration_hours}H"));
$end_time = $end_datetime->format('H:i:s');

// Generate booking reference
$booking_reference = 'PARK-' . strtoupper(substr($location, 0, 3)) . '-' . date('Ymd') . '-' . bin2hex(random_bytes(2));

// Get user ID from session
$user_id = $_SESSION['user_id'] ?? null;

// Map location names to location_id values
$location_map = [
    'Shahapur' => 1,
    'Vadagoan' => 2,
    'Tilakwadi' => 3,
    'Angol' => 4,
    'Sadashiv_nagar' => 5,
    'Shivbasava_nagar' => 6,
    'Neharu_nagar' => 7,
    'Gandhi_nagar' => 8
];

// Get the location_id from the map
$location_id = $location_map[$location] ?? null;

if ($location_id === null) {
    header("Location: booking_page.html?error=Invalid+location");
    exit();
}

// Verify if spot exists and is available
$spot_check = $conn->prepare("SELECT status FROM parking_spots WHERE spot_name = ? AND location_id = ?");
$spot_check->bind_param("si", $spot_id, $location_id);
$spot_check->execute();
$spot_result = $spot_check->get_result();

if ($spot_result->num_rows === 0) {
    header("Location: booking_page.html?error=Spot+not+found");
    exit();
}

$spot_data = $spot_result->fetch_assoc();
if ($spot_data['status'] !== 'vacant' && $spot_data['status'] !== 'available') {
    header("Location: booking_page.html?error=Spot+not+available");
    exit();
}
$spot_check->close();

// Insert into database
$stmt = $conn->prepare("INSERT INTO bookings (
    booking_reference,
    user_id,
    location_id,
    spot_id,
    car_number,
    booking_date,
    start_time,
    duration_hours,
    end_time,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active')");

// Get actual spot_id from database
$get_spot_id = $conn->prepare("SELECT spot_id FROM parking_spots WHERE spot_name = ? AND location_id = ?");
$get_spot_id->bind_param("si", $spot_id, $location_id);
$get_spot_id->execute();
$spot_id_result = $get_spot_id->get_result();
$actual_spot_id = $spot_id_result->fetch_assoc()['spot_id'];
$get_spot_id->close();

$stmt->bind_param("siisssiss", 
    $booking_reference,
    $user_id,
    $location_id,
    $actual_spot_id,
    $car_number,
    $booking_date,
    $start_time,
    $duration_hours,
    $end_time
);

if ($stmt->execute()) {
    // Update spot status to occupied
    $update_spot = $conn->prepare("UPDATE parking_spots SET status = 'occupied' WHERE spot_id = ?");
    $update_spot->bind_param("i", $actual_spot_id);
    $update_spot->execute();
    $update_spot->close();
    
    header("Location: booking_success.php?ref=" . urlencode($booking_reference));
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>