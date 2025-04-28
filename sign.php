<script>
    function validateForm() {
        const password = document.getElementById('signup-password').value;
        const confirmPassword = document.getElementById('signup-confirm').value;
        const phone = document.getElementById('signup-phone').value;
        
        // Phone validation
        const phoneRegex = /^\+?([0-9]{2})\)?[-. ]?([0-9]{10})$/;
        if (!phoneRegex.test(phone)) {
            alert('Please enter a valid phone number (e.g., +91 1234567890)');
            return false;
        }
        
        if (password.length < 8) {
            alert('Password must be at least 8 characters long');
            return false;
        }
        
        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return false;
        }
        
        return true;
    }

    <style>
        .error-message {
            color: #e74c3c;
            padding: 10px;
            margin-bottom: 20px;
            background: #f8d7da;
            border-radius: 4px;
            text-align: center;
            display: none;
        }
    </style>

    <script>
        // Add this at the start of your script section
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            if (error) {
                document.querySelector('.error-message').style.display = 'block';
            }
        }
    </script>
</script>