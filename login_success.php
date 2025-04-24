<?php
session_start();

// Verify user is actually logged in
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
    <title>Login Successful - ParkEase</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f5f7fa;
            margin: 0;
        }
        .success-container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
        }
        .success-icon {
            font-size: 4rem;
            color: #2ecc71;
            margin-bottom: 1rem;
        }
        h1 {
            color: #34495e;
            margin-bottom: 1rem;
        }
        p {
            color: #666;
            margin-bottom: 2rem;
        }
        .btn {
            display: inline-block;
            padding: 0.8rem 1.5rem;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h1>Login Successful!</h1>
        <p>Welcome back, <?php echo htmlspecialchars($_SESSION['full_name']); ?>!</p>
        <p>You are being redirected to your dashboard...</p>
        <a href="home.html" class="btn">Continue Now</a>
        
        <!-- Auto-redirect after 3 seconds -->
        <script>
            setTimeout(function() {
                window.location.href = "home.html";
            }, 3000);
        </script>
    </div>
</body>
</html>