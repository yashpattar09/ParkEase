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

// Get price per hour for the selected location
$price_query = $conn->prepare("SELECT price_per_hour FROM parking_locations WHERE location_id = ?");
$price_query->bind_param("i", $location_id);
$price_query->execute();
$price_result = $price_query->get_result();
$price_data = $price_result->fetch_assoc();
$price_per_hour = $price_data['price_per_hour'];
$price_query->close();

// Calculate total price
$total_price = $price_per_hour * $duration_hours;

// Get actual spot_id from database
$get_spot_id = $conn->prepare("SELECT spot_id FROM parking_spots WHERE spot_name = ? AND location_id = ?");
$get_spot_id->bind_param("si", $spot_id, $location_id);
$get_spot_id->execute();
$spot_id_result = $get_spot_id->get_result();
$actual_spot_id = $spot_id_result->fetch_assoc()['spot_id'];
$get_spot_id->close();

// Store booking details in session for payment page
$_SESSION['pending_booking'] = [
    'booking_reference' => $booking_reference,
    'user_id' => $user_id,
    'location_id' => $location_id,
    'location_name' => $location,
    'spot_id' => $actual_spot_id,
    'spot_name' => $spot_id,
    'car_number' => $car_number,
    'booking_date' => $booking_date,
    'start_time' => $start_time,
    'duration_hours' => $duration_hours,
    'end_time' => $end_time,
    'price_per_hour' => $price_per_hour,
    'total_price' => $total_price
];

// Redirect to payment page instead of directly booking
header("Location: payment_page.php");
exit();
?>