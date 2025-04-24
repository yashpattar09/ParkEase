<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "parkease_db";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Collect POST data
$full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$plain_password = $_POST['password']; // Store the plain text password

// Insert into database using prepared statement
$stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $full_name, $email, $phone, $plain_password);

if ($stmt->execute()) {
    echo "Signup successful!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
mysqli_close($conn);
?>