<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // User is not logged in, show regular buttons
    $authButtons = '
        <a href="login.php" class="btn btn-outline">Login</a>
        <a href="signup.php" class="btn btn-primary">Sign Up</a>
    ';
} else {
    // User is logged in, show profile button
    $authButtons = '
        <a href="user_profile.php" class="profile-btn">👤</a>
    ';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkEase - Smart Parking Solution</title>
    <link rel="stylesheet" href="home.css">

</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <span class="logo-icon">🅿️</span> ParkEase
            </div>
            <div class="nav-links">
                <a href="admin.php">Locations</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="auth-buttons">
                <?php echo $authButtons; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container" >
            <h1>Smart Parking Made Simple</h1>
            <p>Find and reserve parking spaces in real-time. Say goodbye to parking frustrations and hello to convenience.</p>
            <div class="hero-buttons">
                <a href="booking_page.html" class="btn btn-large btn-primary">Book your Spot now</a>
            </div>
        </div>
    </section>

    <!-- Location Selector -->
    <div class="container">
        <div class="location-selector">
            <?php if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1><br>
            <?php endif; ?>
            <h2>Find Parking Near You</h2>
            <form class="location-form" action="admin.php">
                
                <div class="form-submit">
                    <button type="submit" class="btn btn-large btn-primary">Search Availability</button>
                </div>
            </form>
        </div>

        <!-- Stats Section -->
        
    </div>

    <!-- How It Works -->
    <section class="how-it-works">
        <div class="container" id="how-it-works">
            <h2>How ParkEase Works</h2>
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <h3>Find a Spot</h3>
                    <p>Search for available parking spots near your destination in real-time.</p>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <h3>Book & Pay</h3>
                    <p>Reserve your spot with a few clicks and secure payment.</p>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <h3>Park with Ease</h3>
                    <p>Use your confirmation code or QR code for seamless entry and exit.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-branding">
                    <h3>🅿️ ParkEase</h3>
                    <p>Making parking hassle-free with smart technology and seamless booking experiences.</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#">Home</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Locations</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                    </ul>
                </div>
                <div class="footer-links id">
                    <h4>Support</h4>
                    <ul>
                        <li>Contact us at</li>
                        <li>+91 9756048379</li>
                        <li>+91 8088509434</li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 ParkEase. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>