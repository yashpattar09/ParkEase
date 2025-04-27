<?php
session_start();
require 'db_connection.php';

// Check if there's a pending booking and payment is complete
if (!isset($_SESSION['pending_booking']) || !isset($_POST['payment_complete'])) {
    header("Location: booking_page.html");
    exit();
}

$booking = $_SESSION['pending_booking'];
$payment_method = $_SESSION['payment_method'] ?? 'unknown';

// Generate payment reference
$payment_reference = 'PAY-' . strtoupper(substr($payment_method, 0, 3)) . '-' . date('Ymd') . '-' . bin2hex(random_bytes(2));

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
    total_amount,
    payment_status,
    payment_reference,
    payment_date,
    status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'paid', ?, NOW(), 'active')");

$stmt->bind_param("siissssisds", 
    $booking['booking_reference'],
    $booking['user_id'],
    $booking['location_id'],
    $booking['spot_id'],
    $booking['car_number'],
    $booking['booking_date'],
    $booking['start_time'],
    $booking['duration_hours'],
    $booking['end_time'],
    $booking['total_price'],
    $payment_reference
);

if ($stmt->execute()) {
    // Update spot status to occupied
    $update_spot = $conn->prepare("UPDATE parking_spots SET status = 'occupied' WHERE spot_id = ?");
    $update_spot->bind_param("i", $booking['spot_id']);
    $update_spot->execute();
    $update_spot->close();
    
    // Format the time slot for display
    $start_time_formatted = date('h:i A', strtotime($booking['start_time']));
    $end_time_formatted = date('h:i A', strtotime($booking['end_time']));
    $time_slot = $start_time_formatted . ' - ' . $end_time_formatted;
    
    // Clear pending booking from session
    unset($_SESSION['pending_booking']);
    unset($_SESSION['payment_method']);
    
    // Redirect to success page
    header("Location: booking_success.php?ref=" . urlencode($booking['booking_reference']) . 
           "&spot=" . urlencode($booking['spot_name']) . 
           "&date=" . urlencode($booking['booking_date']) . 
           "&time=" . urlencode($time_slot) .
           "&amount=" . urlencode($booking['total_price']));
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>