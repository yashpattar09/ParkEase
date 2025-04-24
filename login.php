<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ... existing head content ... -->
    <style>
        /* ... existing styles ... */
        
        /* Add this before the footer styles */
        .error-message {
            color: #e74c3c;
            padding: 10px;
            margin-bottom: 20px;
            background: #f8d7da;
            border-radius: 4px;
            text-align: center;
            display: <?php echo isset($_GET['error']) ? 'block' : 'none'; ?>;
        }
        
        /* ... rest of the styles ... */
    </style>
</head>
<body>
    <!-- Navigation remains the same -->
    
    <div class="auth-container">
        <div class="auth-card" id="login-form">
            <div class="auth-header">
                <h1>Welcome Back</h1>
                <p class="auth-subtitle">Log in to manage your parking spots</p>
            </div>
            
            <!-- Add the error message div here -->
            <div class="error-message">
                <?php 
                if (isset($_GET['error'])) {
                    switch ($_GET['error']) {
                        case 1: echo "Invalid email or password"; break;
                        case 2: echo "Invalid request method"; break;
                        default: echo "Login failed";
                    }
                }
                ?>
            </div>
            
            <form class="auth-form" action="login_process.php" method="POST">
                <!-- ... rest of the form remains the same ... -->
            </form>
            
            <!-- ... rest of the content remains the same ... -->
        </div>
    </div>

    <!-- ... footer remains the same ... -->
</body>
</html>