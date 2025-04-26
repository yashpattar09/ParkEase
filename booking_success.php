<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="booking_form.css">
    <style>
        .confirmation-details {
            margin: 20px 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 10px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            width: 150px;
        }
        .redirect-message {
            margin-top: 20px;
            color: #666;
            font-style: italic;
        }
    </style>
    <script>
        // Countdown timer for redirect
        setTimeout(function() {
            window.location.href = "home.php";
        }, 3000); // 3000 milliseconds = 3 seconds
    </script>
</head>
<body>
    <div class="booking-form">
        <h2>Booking Confirmed!</h2>
        
        <div class="confirmation-details">
            <div class="detail-row">
                <div class="detail-label">Reference:</div>
                <div><?php echo htmlspecialchars($_GET['ref']); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Spot Number:</div>
                <div><?php echo htmlspecialchars($_GET['spot'] ?? ''); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Date:</div>
                <div><?php echo htmlspecialchars($_GET['date'] ?? ''); ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Time Slot:</div>
                <div><?php echo htmlspecialchars($_GET['time'] ?? ''); ?></div>
            </div>
        </div>

        <p>Thank you for using ParkEase.</p>
        
        <div class="redirect-message">
            You will be redirected to the home page in 3 seconds...
        </div>
        
        <div style="margin-top: 20px;">
            <a href="home.php" class="submit-btn">Go Home Now</a>
        </div>
    </div>
</body>
</html>