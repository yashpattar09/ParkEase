<?php
session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'parkease_db');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// Get POST data
$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute query - MODIFIED to fetch full_name too
$stmt = $conn->prepare("SELECT user_id, password, full_name FROM users WHERE email = ?");
$stmt->bind_param('s', $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Assuming passwords are stored as plain text (not recommended), compare directly
    if ($password === $user['password']) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['loggedin'] = true; // Add this line
        $_SESSION['full_name'] = $user['full_name']; // Add this line
        header('Location: home.php'); // Changed to redirect to home.php
        exit();
    } else {
        header('Location: login.php?error=Incorrect+password');
        exit();
    }
} else {
    header('Location: login.php?error=User+not+found');
    exit();
}
?>