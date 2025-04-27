<?php
session_start();

// Check if there's a pending booking
if (!isset($_SESSION['pending_booking'])) {
    header("Location: booking_page.html");
    exit();
}

$booking = $_SESSION['pending_booking'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - ParkEase</title>
    <link rel="stylesheet" href="booking_form.css">
    <link rel="stylesheet" href="payment_styles.css">
    <script>
        function selectPaymentMethod(method) {
            // Remove selected class from all methods
            document.querySelectorAll('.payment-method').forEach(el => {
                el.classList.remove('selected');
            });
            
            // Add selected class to clicked method
            document.getElementById(method).classList.add('selected');
            
            // Enable pay button
            document.getElementById('payButton').disabled = false;
            
            // Store selected method
            document.getElementById('selected_payment_method').value = method;
        }
    </script>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h2>Payment Details</h2>
        </div>
        
        <div class="payment-summary">
            <div class="price-row">
                <span>Location:</span>
                <span><?php echo htmlspecialchars($booking['location_name']); ?></span>
            </div>
            <div class="price-row">
                <span>Spot:</span>
                <span><?php echo htmlspecialchars($booking['spot_name']); ?></span>
            </div>
            <div class="price-row">
                <span>Date:</span>
                <span><?php echo htmlspecialchars($booking['booking_date']); ?></span>
            </div>
            <div class="price-row">
                <span>Time:</span>
                <span><?php echo htmlspecialchars($booking['start_time']); ?></span>
            </div>
            <div class="price-row">
                <span>Duration:</span>
                <span><?php echo htmlspecialchars($booking['duration_hours']); ?> hour(s)</span>
            </div>
            <div class="price-row">
                <span>Rate:</span>
                <span>₹<?php echo htmlspecialchars(number_format($booking['price_per_hour'], 2)); ?> per hour</span>
            </div>
            <div class="price-row total">
                <span>Total Amount:</span>
                <span>₹<?php echo htmlspecialchars(number_format($booking['total_price'], 2)); ?></span>
            </div>
        </div>
        
        <h3>Select Payment Method</h3>
        
        <form action="process_payment.php" method="POST">
            <input type="hidden" id="selected_payment_method" name="payment_method" value="">
            
            <div class="payment-methods">
                <div id="credit-card" class="payment-method" onclick="selectPaymentMethod('credit-card')">
                    <img src="/api/placeholder/40/40" alt="Credit Card">
                    <span class="payment-method-label">Credit Card</span>
                </div>
                
                <div id="debit-card" class="payment-method" onclick="selectPaymentMethod('debit-card')">
                    <img src="/api/placeholder/40/40" alt="Debit Card">
                    <span class="payment-method-label">Debit Card</span>
                </div>
                
                <div id="upi" class="payment-method" onclick="selectPaymentMethod('upi')">
                    <img src="/api/placeholder/40/40" alt="UPI">
                    <span class="payment-method-label">UPI Payment</span>
                </div>
                
                <div id="net-banking" class="payment-method" onclick="selectPaymentMethod('net-banking')">
                    <img src="/api/placeholder/40/40" alt="Net Banking">
                    <span class="payment-method-label">Net Banking</span>
                </div>
            </div>
            
            <div class="payment-buttons">
                <a href="booking_page.html" class="back-btn">Back</a>
                <button type="submit" id="payButton" class="pay-btn" disabled>Pay Now</button>
            </div>
        </form>
    </div>
</body>
</html>