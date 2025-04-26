<?php
session_start();
require 'db_connection.php'; // You'll need to create this

// Get form data
$location = $_POST['location'];
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

// Get user ID from session (assuming user is logged in)
$user_id = $_SESSION['user_id'] ?? null;

// For demo, we'll use location as location_id (you should have a locations table)
$location_id = array_search($location, [
    'Shahapur' => 1,
    'Vadagoan' => 2,
    'Tilakwadi' => 3,
    'Angol' => 4,
    'Sadashiv_nagar' => 5,
    'Shivbasava_nagar' => 6,
    'Neharu_nagar' => 7,
    'Gandhi_nagar' => 8
]);

// Insert into database
$stmt = $conn->prepare("INSERT INTO bookings (
    booking_reference,
    user_id,
    location_id,
    car_number,
    booking_date,
    start_time,
    duration_hours,
    end_time,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'active')");

$stmt->bind_param("siisssis", 
    $booking_reference,
    $user_id,
    $location_id,
    $car_number,
    $booking_date,
    $start_time,
    $duration_hours,
    $end_time
);

if ($stmt->execute()) {
    header("Location: booking_success.php?ref=" . urlencode($booking_reference));
} else {
    header("Location: booking_page.html?error=Booking failed");
}

$stmt->close();
$conn->close();
?>