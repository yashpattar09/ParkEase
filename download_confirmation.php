<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$booking_reference = $_GET['ref'] ?? '';
if (empty($booking_reference)) {
    die("Booking reference is required");
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch booking details
$query = "SELECT b.*, 
          l.location_name, 
          p.spot_name,
          u.full_name, 
          u.email, 
          u.phone 
          FROM bookings b
          JOIN users u ON b.user_id = u.user_id
          JOIN parking_locations l ON b.location_id = l.location_id
          JOIN parking_spots p ON b.spot_id = p.spot_id
          WHERE b.booking_reference = ? AND b.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("si", $booking_reference, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found or unauthorized access");
}

$booking = $result->fetch_assoc();

// Format date and time
$booking_date = date('F j, Y', strtotime($booking['booking_date']));
$start_time = date('h:i A', strtotime($booking['start_time']));
$end_time = date('h:i A', strtotime($booking['end_time']));

// Create image dimensions
$width = 800;
$height = 1200;
$image = imagecreatetruecolor($width, $height);

// Colors
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$blue = imagecolorallocate($image, 0, 102, 204);
$darkGray = imagecolorallocate($image, 64, 64, 64);
$lightGray = imagecolorallocate($image, 230, 230, 230);

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $white);

// Set font path (use a real font file if available)
$font = 5; // Built-in GD font (1-5)
$fontLarge = 5;
$fontBold = 5;

// Header
imagestring($image, $fontLarge, $width/2 - 50, 30, "ParkEase", $blue);
imagestring($image, $font, $width/2 - 80, 60, "Your Smart Parking Solution", $darkGray);

// Booking reference
imagestring($image, $fontBold, $width/2 - 100, 100, "Booking Confirmation", $black);
imagestring($image, $font, $width/2 - 80, 130, "Reference: " . $booking_reference, $black);

// Customer Information
imagestring($image, $fontBold, 50, 180, "Customer Information", $blue);
imagestring($image, $font, 50, 210, "Name: " . $booking['full_name'], $black);
imagestring($image, $font, 50, 240, "Email: " . $booking['email'], $black);
imagestring($image, $font, 50, 270, "Phone: " . $booking['phone'], $black);

// Booking Details
imagestring($image, $fontBold, 50, 330, "Booking Details", $blue);
imagestring($image, $font, 50, 360, "Location: " . $booking['location_name'], $black);
imagestring($image, $font, 50, 390, "Parking Spot: " . $booking['spot_name'], $black);
imagestring($image, $font, 50, 420, "Date: " . $booking_date, $black);
imagestring($image, $font, 50, 450, "Time: " . $start_time . " - " . $end_time, $black);
imagestring($image, $font, 50, 480, "Duration: " . $booking['duration_hours'] . " hour(s)", $black);
imagestring($image, $font, 50, 510, "Vehicle: " . $booking['car_number'], $black);
imagestring($image, $font, 50, 540, "Status: " . ucfirst($booking['status']), $black);

// Payment Information
imagestring($image, $fontBold, 50, 600, "Payment Information", $blue);
imagestring($image, $font, 50, 630, "Payment Reference: " . ($booking['payment_reference'] ?? 'N/A'), $black);
imagestring($image, $font, 50, 660, "Payment Date: " . date('F j, Y, h:i A', strtotime($booking['payment_date'] ?? 'now')), $black);
imagestring($image, $font, 50, 690, "Payment Status: " . ucfirst($booking['payment_status'] ?? 'N/A'), $black);

// Total Amount
imagestring($image, $fontBold, $width - 250, 750, "Total Amount: ₹" . number_format($booking['total_amount'], 2), $blue);

// Simple barcode simulation (GD doesn't have barcode functions)
for ($i = 0; $i < 200; $i += 2) {
    $height = rand(30, 60);
    imageline($image, 100 + $i, 800, 100 + $i, 800 + $height, $black);
}

// Footer
imagestring($image, $font, $width/2 - 200, 900, "This is an electronically generated confirmation", $darkGray);
imagestring($image, $font, $width/2 - 200, 930, "and does not require a signature.", $darkGray);
imagestring($image, $font, $width/2 - 100, 960, "© " . date('Y') . " ParkEase", $darkGray);
imagestring($image, $font, $width/2 - 150, 990, "For any inquiries, please contact info@parkease.com", $darkGray);

// Output the image
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="booking_confirmation_' . $booking_reference . '.png"');
imagepng($image);
imagedestroy($image);

$conn->close();
?><?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$booking_reference = $_GET['ref'] ?? '';
if (empty($booking_reference)) {
    die("Booking reference is required");
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch booking details
$query = "SELECT b.*, 
          l.location_name, 
          p.spot_name,
          u.full_name, 
          u.email, 
          u.phone 
          FROM bookings b
          JOIN users u ON b.user_id = u.user_id
          JOIN parking_locations l ON b.location_id = l.location_id
          JOIN parking_spots p ON b.spot_id = p.spot_id
          WHERE b.booking_reference = ? AND b.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("si", $booking_reference, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found or unauthorized access");
}

$booking = $result->fetch_assoc();

// Format date and time
$booking_date = date('F j, Y', strtotime($booking['booking_date']));
$start_time = date('h:i A', strtotime($booking['start_time']));
$end_time = date('h:i A', strtotime($booking['end_time']));

// Create image dimensions
$width = 800;
$height = 1200;
$image = imagecreatetruecolor($width, $height);

// Colors
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);
$blue = imagecolorallocate($image, 0, 102, 204);
$darkGray = imagecolorallocate($image, 64, 64, 64);
$lightGray = imagecolorallocate($image, 230, 230, 230);

// Fill background
imagefilledrectangle($image, 0, 0, $width, $height, $white);

// Set font path (use a real font file if available)
$font = 5; // Built-in GD font (1-5)
$fontLarge = 5;
$fontBold = 5;

// Header
imagestring($image, $fontLarge, $width/2 - 50, 30, "ParkEase", $blue);
imagestring($image, $font, $width/2 - 80, 60, "Your Smart Parking Solution", $darkGray);

// Booking reference
imagestring($image, $fontBold, $width/2 - 100, 100, "Booking Confirmation", $black);
imagestring($image, $font, $width/2 - 80, 130, "Reference: " . $booking_reference, $black);

// Customer Information
imagestring($image, $fontBold, 50, 180, "Customer Information", $blue);
imagestring($image, $font, 50, 210, "Name: " . $booking['full_name'], $black);
imagestring($image, $font, 50, 240, "Email: " . $booking['email'], $black);
imagestring($image, $font, 50, 270, "Phone: " . $booking['phone'], $black);

// Booking Details
imagestring($image, $fontBold, 50, 330, "Booking Details", $blue);
imagestring($image, $font, 50, 360, "Location: " . $booking['location_name'], $black);
imagestring($image, $font, 50, 390, "Parking Spot: " . $booking['spot_name'], $black);
imagestring($image, $font, 50, 420, "Date: " . $booking_date, $black);
imagestring($image, $font, 50, 450, "Time: " . $start_time . " - " . $end_time, $black);
imagestring($image, $font, 50, 480, "Duration: " . $booking['duration_hours'] . " hour(s)", $black);
imagestring($image, $font, 50, 510, "Vehicle: " . $booking['car_number'], $black);
imagestring($image, $font, 50, 540, "Status: " . ucfirst($booking['status']), $black);

// Payment Information
imagestring($image, $fontBold, 50, 600, "Payment Information", $blue);
imagestring($image, $font, 50, 630, "Payment Reference: " . ($booking['payment_reference'] ?? 'N/A'), $black);
imagestring($image, $font, 50, 660, "Payment Date: " . date('F j, Y, h:i A', strtotime($booking['payment_date'] ?? 'now')), $black);
imagestring($image, $font, 50, 690, "Payment Status: " . ucfirst($booking['payment_status'] ?? 'N/A'), $black);

// Total Amount
imagestring($image, $fontBold, $width - 250, 750, "Total Amount: ₹" . number_format($booking['total_amount'], 2), $blue);

// Simple barcode simulation (GD doesn't have barcode functions)
for ($i = 0; $i < 200; $i += 2) {
    $height = rand(30, 60);
    imageline($image, 100 + $i, 800, 100 + $i, 800 + $height, $black);
}

// Footer
imagestring($image, $font, $width/2 - 200, 900, "This is an electronically generated confirmation", $darkGray);
imagestring($image, $font, $width/2 - 200, 930, "and does not require a signature.", $darkGray);
imagestring($image, $font, $width/2 - 100, 960, "© " . date('Y') . " ParkEase", $darkGray);
imagestring($image, $font, $width/2 - 150, 990, "For any inquiries, please contact info@parkease.com", $darkGray);

// Output the image
header('Content-Type: image/png');
header('Content-Disposition: attachment; filename="booking_confirmation_' . $booking_reference . '.png"');
imagepng($image);
imagedestroy($image);

$conn->close();
?>