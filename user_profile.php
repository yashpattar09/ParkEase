<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database Connection
$conn = new mysqli('localhost', 'root', '', 'parkease_db');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch user details
$userQuery = $conn->prepare("SELECT full_name, email, phone FROM users WHERE user_id = ?");
$userQuery->bind_param('i', $user_id);
$userQuery->execute();
$userResult = $userQuery->get_result();
$user = $userResult->fetch_assoc();

// If no user found
if (!$user) {
    header('Location: login.php');
    exit();
}

// Fetch user bookings
// Fetch user bookings - updated to match your actual table structure
// Fetch user bookings - simplified version without joins
$bookingQuery = $conn->prepare("SELECT 
    booking_id, 
    location_id AS location, 
    spot_id AS slot, 
    CONCAT(booking_date, ' ', start_time) AS datetime,
    car_number AS vehicle_details,
    status
    FROM bookings WHERE user_id = ?");
$bookingQuery->bind_param('i', $user_id);
$bookingQuery->execute();
$bookings = $bookingQuery->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - ParkEase</title>
    <link rel="stylesheet" href="user_profile.css">
</head>
<body>
<!-- Navigation -->
<nav class="navbar">
    <div class="container nav-container">
        <div class="logo">
            <span class="logo-icon">🅿️</span> ParkEase
        </div>
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="admin.html">Locations</a>
            <a href="booking_page.html">Book your slot</a>
        </div>
        <div class="auth-buttons">
            <div class="user-greeting">Welcome, <?php echo htmlspecialchars(explode(' ', $user['full_name'])[0]); ?>!</div>
            <a href="logout.php" class="btn btn-outline">Logout</a>
        </div>
    </div>
</nav>

<!-- Profile Section -->
<section class="profile-section">
    <div class="container">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php echo strtoupper(substr($user['full_name'], 0, 2)); ?>
            </div>
            <div class="profile-info">
                <h1><?php echo htmlspecialchars($user['full_name']); ?></h1>
                <div class="profile-contact">
                    <p><?php echo htmlspecialchars($user['email']); ?></p>
                    <p><?php echo htmlspecialchars($user['phone']); ?></p>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="tabs">
            <div class="tab active" data-tab="bookings">My Bookings</div>
            <div class="tab" data-tab="profile">Profile Details</div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- Bookings Tab -->
            <div class="tab-pane active" id="bookings">
                <h2>My Bookings</h2>
                <table class="bookings-table">
                    <thead>
                        <tr>
                            <th>Booking ID</th>
                            <th>Location</th>
                            <th>Slot</th>
                            <th>Date & Time</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = $bookings->fetch_assoc()) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['booking_id']); ?></td>
                                <td><?php echo htmlspecialchars($booking['location']); ?></td>
                                <td><?php echo htmlspecialchars($booking['slot']); ?></td>
                                <td><?php echo htmlspecialchars($booking['datetime']); ?></td>
                                <td><?php echo htmlspecialchars($booking['vehicle_details']); ?></td>
                                <td><?php echo htmlspecialchars($booking['status']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <!-- Profile Details Tab -->
            <div class="tab-pane" id="profile">
                <h2>Profile Details</h2>
                <p>Full Name: <?php echo htmlspecialchars($user['full_name']); ?></p>
                <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
                <p>Phone: <?php echo htmlspecialchars($user['phone']); ?></p>
            </div>

        </div>
    </div>
</section>

<footer>
    <div class="container">
        <p>&copy; 2025 ParkEase. All rights reserved.</p>
    </div>
</footer>

<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });
});
</script>
</body>
</html>
