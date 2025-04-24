<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkEase - Smart Parking Solution</title>
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #2ecc71;
            --secondary-dark: #27ae60;
            --dark: #34495e;
            --light: #f5f7fa;
            --gray: #ecf0f1;
            --warning: #f39c12;
            --danger: #e74c3c;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Navigation */
        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 0;
        }
        
        .logo {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary);
        }
        
        .logo-icon {
            margin-right: 8px;
            font-size: 1.8rem;
        }
        
        .nav-links {
            display: flex;
            gap: 1.5rem;
        }
        
        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: var(--primary);
        }
        
        .auth-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            text-align: center;
            text-decoration: none;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background-color: var(--primary);
            color: white;
        }
        
        /* Hero Section */
        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('parking3.jpg');
            background-size: cover;
            background-position: center;
            color: rgb(255, 255, 255);
            padding: 6rem 0;
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .hero h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            animation: fadeIn 1s ease-in;
        }
        
        .hero p {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            animation: fadeIn 1.5s ease-in;
        }
        
        .hero-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
            animation: fadeIn 2s ease-in;
        }
        
        .btn-large {
            padding: 0.8rem 2rem;
            font-size: 1.1rem;
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: var(--secondary-dark);
        }
        
        /* Location Selector */
        .location-selector {
            background-color: white;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            animation: slideUp 0.5s ease-out;
        }
        
        .location-selector h2 {
            margin-bottom: 1.5rem;
            color: var(--dark);
            text-align: center;
        }
        
        .location-form {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            justify-content: center;
        }
        
        .form-group {
            flex: 1;
            min-width: 200px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .form-submit {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
            width: 100%;
        }
        
        /* Stats Section */
        .stats-section {
            margin-bottom: 3rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--primary);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        /* How It Works */
        .how-it-works {
            padding: 3rem 0;
            background-color: var(--gray);
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .how-it-works h2 {
            margin-bottom: 2.5rem;
            color: var(--dark);
        }
        
        .steps {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 2rem;
        }
        
        .step {
            flex: 1;
            min-width: 200px;
            max-width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .step-number {
            background-color: var(--primary);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }
        
        .step h3 {
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 3rem 0;
        }
        
        .footer-content {
            display: flex;
            flex-wrap: wrap;
            gap: 2rem;
            justify-content: space-between;
        }
        
        .footer-branding {
            flex: 2;
            min-width: 250px;
        }
        
        .footer-branding h3 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .footer-links {
            flex: 1;
            min-width: 160px;
        }
        
        .footer-links h4 {
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }
        
        .footer-links ul {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: white;
            text-decoration: underline;
        }
        
        .copyright {
            text-align: center;
            padding-top: 2rem;
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { 
                transform: translateY(30px);
                opacity: 0;
            }
            to { 
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-links {
                margin: 1rem 0;
            }
            
            .hero h1 {
                font-size: 2.2rem;
            }
            
            .location-form {
                flex-direction: column;
            }
            
            .footer-content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <div class="logo">
                <span class="logo-icon">🅿️</span> ParkEase
            </div>
            <div class="nav-links">
                <a href="#">Home</a>
                <a href="#">Locations</a>
                <a href="#how-it-works">How It Works</a>
                <a href="#contact">Contact</a>
            </div>
            <div class="auth-buttons">
                <a href="login.html" class="btn btn-outline">Login</a>
                <a href="signup.html" class="btn btn-primary">Sign Up</a><br>
                
            </div>
        </div>
    </nav>
 

    <!-- Hero Section -->
    <section class="hero">
        <div class="container" background-image="parking-lot.jpg">
            <h1>Smart Parking Made Simple</h1>
            <p>Find and reserve parking spaces in real-time. Say goodbye to parking frustrations and hello to convenience.</p>
            <div class="hero-buttons">
                <a href="#" class="btn btn-large btn-primary">Book a Spot</a>
                <a href="#" class="btn btn-large btn-secondary">View Availability</a>
            </div>
        </div>
    </section>


    <!-- Location Selector -->
    <div class="container">
        <div class="location-selector">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</h1><br>
            <h2>Find Parking Near You</h2>
            <form class="location-form">
                <div class="form-group">
                    <label for="parking-location">Select Location</label>
                    <select id="parking-location" class="form-control">
                        <option value="">Choose a parking lot</option>
                        <option value="downtown">Downtown Parking</option>
                        <option value="mall">Shopping Mall</option>
                        <option value="airport">Airport Terminal</option>
                        <option value="stadium">Sports Stadium</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Date</label>
                    <input type="date" id="date" class="form-control" value="2025-04-22">
                </div>
                <div class="form-group">
                    <label for="time">Time</label>
                    <input type="time" id="time" class="form-control" value="12:00">
                </div>
                <div class="form-submit">
                    <button type="submit" class="btn btn-large btn-primary">Search Availability</button>
                </div>
            </form>
        </div>
        

        <!-- Stats Section -->
        <section class="stats-section">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">🚗</div>
                    <div class="stat-value">250</div>
                    <div class="stat-label">Total Parking Slots</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">✅</div>
                    <div class="stat-value">138</div>
                    <div class="stat-label">Available Now</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">⚡</div>
                    <div class="stat-value">25</div>
                    <div class="stat-label">EV Charging Spots</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon">🌟</div>
                    <div class="stat-value">48</div>
                    <div class="stat-label">Premium Spots</div>
                </div>
            </div>
        </section>
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