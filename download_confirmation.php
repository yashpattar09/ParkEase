<?php
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$booking_reference = $_GET['ref'] ?? '';
if (empty($booking_reference)) {
    die("Booking reference is required");
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Fetch booking details
$query = "SELECT b.*, 
          l.location_name, 
          p.spot_name,
          u.full_name, 
          u.email, 
          u.phone 
          FROM bookings b
          JOIN users u ON b.user_id = u.user_id
          JOIN parking_locations l ON b.location_id = l.location_id
          JOIN parking_spots p ON b.spot_id = p.spot_id
          WHERE b.booking_reference = ? AND b.user_id = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("si", $booking_reference, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found or unauthorized access");
}

$booking = $result->fetch_assoc();

// Format date and time
$booking_date = date('F j, Y', strtotime($booking['booking_date']));
$start_time = date('h:i A', strtotime($booking['start_time']));
$end_time = date('h:i A', strtotime($booking['end_time']));

// Set the content type to HTML for browser display
// You could also use 'application/pdf' with a PDF library like FPDF
header('Content-Type: text/html');
header('Content-Disposition: attachment; filename="booking_confirmation_'.$booking_reference.'.html"');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation - <?php echo $booking_reference; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #007BFF;
        }
        .confirmation-number {
            font-size: 24px;
            font-weight: bold;
            color: #007BFF;
            margin: 20px 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 18px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .barcode {
            text-align: center;
            margin: 30px 0;
        }
        .barcode img {
            max-width: 300px;
        }
        .qr-code {
            text-align: center;
            margin: 30px 0;
        }
        .logo {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .total-amount {
            font-size: 20px;
            font-weight: bold;
            text-align: right;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">🅿️ ParkEase</div>
        <p>Your Smart Parking Solution</p>
    </div>

    <div class="confirmation-number">
        Booking Reference: <?php echo htmlspecialchars($booking_reference); ?>
    </div>

    <div class="section">
        <div class="section-title">Customer Information</div>
        <table>
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($booking['email']); ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlspecialchars($booking['phone']); ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Booking Details</div>
        <table>
            <tr>
                <th>Location</th>
                <td><?php echo htmlspecialchars($booking['location_name']); ?></td>
            </tr>
            <tr>
                <th>Parking Spot</th>
                <td><?php echo htmlspecialchars($booking['spot_name']); ?></td>
            </tr>
            <tr>
                <th>Date</th>
                <td><?php echo $booking_date; ?></td>
            </tr>
            <tr>
                <th>Time</th>
                <td><?php echo $start_time . ' - ' . $end_time; ?></td>
            </tr>
            <tr>
                <th>Duration</th>
                <td><?php echo htmlspecialchars($booking['duration_hours']); ?> hour(s)</td>
            </tr>
            <tr>
                <th>Vehicle</th>
                <td><?php echo htmlspecialchars($booking['car_number']); ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo htmlspecialchars(ucfirst($booking['status'])); ?></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Payment Information</div>
        <table>
            <tr>
                <th>Payment Reference</th>
                <td><?php echo htmlspecialchars($booking['payment_reference'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
                <th>Payment Date</th>
                <td><?php echo date('F j, Y, h:i A', strtotime($booking['payment_date'] ?? 'now')); ?></td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td><?php echo htmlspecialchars(ucfirst($booking['payment_status'] ?? 'N/A')); ?></td>
            </tr>
        </table>
    </div>

    <div class="total-amount">
        Total Amount: ₹<?php echo number_format($booking['total_amount'], 2); ?>
    </div>

    <!-- Inside your HTML, replace the placeholder barcode with: -->
<div class="barcode">
    <svg id="barcode"></svg>
</div>

<!-- Before </body>, add: -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        JsBarcode("#barcode", "<?php echo $booking_reference; ?>", {
            format: "CODE128",
            lineColor: "#000",
            width: 2,
            height: 100,
            displayValue: true,
            fontSize: 16
        });
    });
</script>

    <div class="footer">
        <p>This is an electronically generated confirmation and does not require a signature.</p>
        <p>&copy; <?php echo date('Y'); ?> ParkEase. All rights reserved.</p>
        <p>For any inquiries, please contact info@parkease.com</p>
    </div>
    
</body>
</html>
<?php
$conn->close();
?>