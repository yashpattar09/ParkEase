<?php
session_start();

// Check if there's a pending booking
if (!isset($_SESSION['pending_booking'])) {
    header("Location: booking_page.html");
    exit();
}

$booking = $_SESSION['pending_booking'];
$payment_method = $_POST['payment_method'] ?? '';

if (empty($payment_method)) {
    header("Location: payment_page.php?error=Select+payment+method");
    exit();
}

// Store payment method in session
$_SESSION['payment_method'] = $payment_method;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment - ParkEase</title>
    <link rel="stylesheet" href="booking_form.css">
    <link rel="stylesheet" href="payment_styles.css">
    <script>
        // Simulate payment processing
        window.onload = function() {
            const paymentContainer = document.querySelector('.payment-container');
            const processingSection = document.getElementById('processingSection');
            const successSection = document.getElementById('successSection');
            
            // Show processing animation
            setTimeout(function() {
                paymentContainer.style.display = 'none';
                processingSection.style.display = 'block';
                
                // Show success after 3 seconds
                setTimeout(function() {
                    processingSection.style.display = 'none';
                    successSection.style.display = 'block';
                    
                    // Automatically complete payment after 2 seconds
                    setTimeout(function() {
                        document.getElementById('completePaymentForm').submit();
                    }, 2000);
                }, 3000);
            }, 1000);
        };
    </script>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h2>Processing Payment</h2>
        </div>
        
        <div class="payment-summary">
            <div class="price-row">
                <span>Amount:</span>
                <span>₹<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></span>
            </div>
            <div class="price-row">
                <span>Payment Method:</span>
                <span><?php 
                    $method_labels = [
                        'credit-card' => 'Credit Card',
                        'debit-card' => 'Debit Card',
                        'upi' => 'UPI Payment',
                        'net-banking' => 'Net Banking'
                    ];
                    echo htmlspecialchars($method_labels[$payment_method] ?? $payment_method); 
                ?></span>
            </div>
        </div>
        
        <p>Please do not close this window. Your payment is being processed...</p>
    </div>
    
    <div id="processingSection" class="payment-processing">
        <div class="spinner"></div>
        <h3>Processing Payment</h3>
        <p>Please wait while we process your payment...</p>
    </div>
    
    <div id="successSection" class="payment-success">
        <div class="success-icon"></div>
        <h3>Payment Successful!</h3>
        <p>Your payment has been processed successfully.</p>
        <p>Booking Reference: <?php echo htmlspecialchars($booking['booking_reference']); ?></p>
        
        <form id="completePaymentForm" action="complete_booking.php" method="POST">
            <input type="hidden" name="payment_complete" value="1">
        </form>
    </div>
</body>
</html>