<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ParkEase - Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="home.html" class="logo">
                <span class="logo-icon">🅿️</span> ParkEase
            </a>
            <div class="nav-links">
                <a href="home.php">Home</a>
            </div>
        </div>
    </nav>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Create Account</h1>
                <p class="auth-subtitle">Sign up to get started with ParkEase</p>
            </div>
            
            <!-- Error Message -->
            <div class="error-message">
                <?php echo isset($_GET['error']) ? htmlspecialchars($_GET['error']) : ''; ?>
            </div>
            <form class="auth-form" action="connect.php" method="POST" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="signup-name">Full Name</label>
                    <input type="text" id="signup-name" class="form-control" placeholder="John Doe" required name="full_name">
                </div>
                
                <div class="form-group">
                    <label for="signup-email">Email Address</label>
                    <input type="email" id="signup-email" class="form-control" placeholder="your@email.com" required name="email">
                    <div class="error-feedback">Please enter a valid email address.</div>
                </div>
                
                <div class="form-group">
                    <label for="signup-phone">Phone Number</label>
                    <input type="tel" id="signup-phone" class="form-control" placeholder="+91 1234567890" required name="phone">
                </div>
                
                <div class="form-group">
                    <label for="signup-password">Password</label>
                    <input type="password" id="signup-password" class="form-control" placeholder="Create a strong password (min 8 characters)" required name="password">
                    <div class="password-strength"></div>
                    <small class="text-muted">Must contain at least 8 characters, including uppercase, lowercase, and numbers</small>
                </div>
                
                <div class="form-group">
                    <label for="signup-confirm">Confirm Password</label>
                    <input type="password" id="signup-confirm" class="form-control" placeholder="Confirm your password" required>
                    <div class="error-feedback">Passwords do not match.</div>
                </div>
                
                <button type="submit" class="btn btn-secondary btn-block">Create Account</button>
            </form>
            
            <div class="auth-footer">
                <div class="switch-form">
                    <p>Already have an account?</p>
                    <a href="login.php">Log In</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="logo">
                    <span class="logo-icon">🅿️</span> ParkEase
                </div>
                <div class="footer-links">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Help Center</a>
                    <a href="#">Contact</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Form validation
        function validateForm() {
            const password = document.getElementById('signup-password').value;
            const confirmPassword = document.getElementById('signup-confirm').value;
            
            // Check password length
            if (password.length < 8) {
                alert('Password must be at least 8 characters long');
                return false;
            }
            
            // Check password complexity
            if (!password.match(/[a-z]/) || !password.match(/[A-Z]/) || !password.match(/[0-9]/)) {
                alert('Password must contain at least one uppercase letter, one lowercase letter, and one number');
                return false;
            }
            
            // Check password match
            if (password !== confirmPassword) {
                alert('Passwords do not match');
                return false;
            }
            
            return true;
        }

        // Password strength indicator
        document.getElementById('signup-password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.querySelector('.password-strength');
            
            // Remove all classes first
            strengthBar.classList.remove('strength-weak', 'strength-medium', 'strength-strong');
            
            if (password.length === 0) {
                return;
            } else if (password.length < 8) {
                strengthBar.classList.add('strength-weak');
            } else if (password.length >= 8 && password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/)) {
                strengthBar.classList.add('strength-strong');
            } else if (password.length >= 8) {
                strengthBar.classList.add('strength-medium');
            }
        });
        
        // Password match checker
        document.getElementById('signup-confirm').addEventListener('input', function() {
            const password = document.getElementById('signup-password').value;
            const confirmPassword = this.value;
            const errorFeedback = this.nextElementSibling;
            
            if (confirmPassword && password !== confirmPassword) {
                errorFeedback.style.display = 'block';
            } else {
                errorFeedback.style.display = 'none';
            }
        });
    </script>
</body>
</html>