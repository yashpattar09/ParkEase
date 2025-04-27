<?php
session_start();
require_once 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Get booking details from POST
$location_id = $_POST['location_id'];
$spot_id = $_POST['spot_id'];
$duration = $_POST['duration'];
$car_number = $_POST['car_number'];

// Calculate total (simulated price)
$total = $duration * 10; // $10 per hour for demo
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Booking - ParkEase</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .payment-container {
            max-width: 500px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .payment-option {
            padding: 1rem;
            margin: 1rem 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .payment-option:hover {
            background: #f5f5f5;
            border-color: #3498db;
        }
        .payment-option.selected {
            background: #e3f2fd;
            border-color: #3498db;
        }
        #payment-animation {
            display: none;
            margin: 2rem 0;
        }
        .checkmark {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: block;
            stroke-width: 5;
            stroke: #4CAF50;
            stroke-miterlimit: 10;
            margin: 0 auto;
            animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
        }
        .checkmark__circle {
            stroke-dasharray: 166;
            stroke-dashoffset: 166;
            stroke-width: 5;
            stroke-miterlimit: 10;
            stroke: #4CAF50;
            fill: none;
            animation: stroke .6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
        }
        .checkmark__check {
            transform-origin: 50% 50%;
            stroke-dasharray: 48;
            stroke-dashoffset: 48;
            animation: stroke .3s cubic-bezier(0.65, 0, 0.45, 1) .8s forwards;
        }
        @keyframes stroke {
            100% { stroke-dashoffset: 0; }
        }
        @keyframes scale {
            0%, 100% { transform: none; }
            50% { transform: scale3d(1.1, 1.1, 1); }
        }
        @keyframes fill {
            100% { box-shadow: inset 0px 0px 0px 50px #4CAF50; }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <div class="payment-container">
            <h2>Confirm Your Booking</h2>
            <p>Location: Parking Spot #<?php echo htmlspecialchars($spot_id); ?></p>
            <p>Duration: <?php echo htmlspecialchars($duration); ?> hours</p>
            <p>Vehicle: <?php echo htmlspecialchars($car_number); ?></p>
            <h3>Total: $<?php echo number_format($total, 2); ?></h3>

            <div class="payment-options">
                <h4>Select Payment Method</h4>
                <div class="payment-option" onclick="selectPayment('card')">
                    💳 Credit/Debit Card
                </div>
                <div class="payment-option" onclick="selectPayment('wallet')">
                    📱 Mobile Wallet
                </div>
                <div class="payment-option" onclick="selectPayment('upi')">
                    🏦 UPI Payment
                </div>
            </div>

            <div id="payment-animation">
                <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                    <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                    <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                </svg>
                <h3>Payment Successful!</h3>
                <p>Your booking has been confirmed.</p>
            </div>

            <button id="pay-button" class="btn btn-primary" onclick="processPayment()" disabled>
                Complete Payment
            </button>
        </div>
    </div>

    <script>
        let selectedMethod = null;
        
        function selectPayment(method) {
            selectedMethod = method;
            document.querySelectorAll('.payment-option').forEach(el => {
                el.classList.remove('selected');
            });
            event.currentTarget.classList.add('selected');
            document.getElementById('pay-button').disabled = false;
        }

        function processPayment() {
            if (!selectedMethod) return;
            
            // Show processing message
            document.getElementById('pay-button').textContent = 'Processing...';
            document.getElementById('pay-button').disabled = true;
            
            // Simulate API call delay
            setTimeout(() => {
                document.querySelector('.payment-options').style.display = 'none';
                document.getElementById('pay-button').style.display = 'none';
                document.getElementById('payment-animation').style.display = 'block';
                
                // After animation, submit form to server
                setTimeout(() => {
                    submitBooking();
                }, 2000);
            }, 1500);
        }

        function submitBooking() {
            // Create a form and submit it
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'process_booking.php';
            
            <?php foreach ($_POST as $key => $value): ?>
                const input<?php echo $key; ?> = document.createElement('input');
                input<?php echo $key; ?>.type = 'hidden';
                input<?php echo $key; ?>.name = '<?php echo $key; ?>';
                input<?php echo $key; ?>.value = '<?php echo addslashes($value); ?>';
                form.appendChild(input<?php echo $key; ?>);
            <?php endforeach; ?>
            
            const totalInput = document.createElement('input');
            totalInput.type = 'hidden';
            totalInput.name = 'total_amount';
            totalInput.value = '<?php echo $total; ?>';
            form.appendChild(totalInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>