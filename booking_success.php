<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="booking_form.css">
</head>
<body>
    <div class="booking-form">
        <h2>Booking Confirmed!</h2>
        <p>Your booking reference is: <strong><?php echo htmlspecialchars($_GET['ref']); ?></strong></p>
        <p>Thank you for using ParkEase.</p>
        <a href="dashboard.php" class="submit-btn">Return to Dashboard</a>
    </div>
</body>
</html>