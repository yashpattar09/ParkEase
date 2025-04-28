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
        .payment-success-icon {
            text-align: center;
            margin: 20px 0;
        }
        .payment-success-icon .icon {
            width: 60px;
            height: 60px;
            margin: 0 auto;
            border-radius: 50%;
            background: #4CAF50;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .payment-success-icon .icon:before {
            content: '✓';
            color: white;
            font-size: 32px;
            font-weight: bold;
        }
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .download-btn {
            background: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .download-btn:hover {
            background: #218838;
        }
    </style>
    <script>
        // Countdown timer for redirect
        setTimeout(function() {
            window.location.href = "home.php";
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
</head>
<body>
    <div class="booking-form">
        <h2>Booking Confirmed!</h2>
        
        <div class="payment-success-icon">
            <div class="icon"></div>
            <p>Payment Successful</p>
        </div>
        
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
            <div class="detail-row">
                <div class="detail-label">Amount Paid:</div>
                <div>₹<?php echo htmlspecialchars(number_format($_GET['amount'] ?? 0, 2)); ?></div>
            </div>
        </div>

        <p>Thank you for using ParkEase.</p>
        
        <div class="redirect-message">
            You will be redirected to the home page in 5 seconds...
        </div>
        
        <div class="action-buttons">
            <a href="home.php" class="submit-btn">Go Home Now</a>
            <a href="download_confirmation.php?ref=<?php echo urlencode($_GET['ref']); ?>" class="download-btn">Download Confirmation</a>
        </div>
    </div>
</body>
</html>