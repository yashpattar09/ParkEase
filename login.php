<?php
session_start();

// Redirect already logged-in users to their profile
if (isset($_SESSION['user_id'])) {
    header('Location: user_profile.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ParkEase</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<nav class="navbar">
    <div class="container nav-container">
        <a href="index.php" class="logo">
            <span class="logo-icon">🅿️</span> ParkEase
        </a>
        <div class="nav-links">
            <a href="home.php">Home</a>
        
        </div>
    </div>
</nav>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Login</h1>
            <p class="auth-subtitle">Access your account</p>
        </div>

        <?php if (isset($_GET['error'])) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php } ?>

        <form action="login_process.php" method="POST" class="auth-form">
            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <div class="auth-footer">
    Don't have an account? <a href="signup.php">Sign up</a>
</div>

        </form>
    </div>
</div>

<footer>
    <div class="container footer-content">
        <p>&copy; 2025 ParkEase. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
