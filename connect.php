<?php
session_start();
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

// Validate password strength
if (strlen($plain_password) < 8) {
    header("Location: signup.php?error=Password must be at least 8 characters long");
    exit();
}

// Check if email already exists
$check_query = "SELECT * FROM users WHERE email = ?";
$check_stmt = $conn->prepare($check_query);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    header("Location: signup.php?error=Email already exists");
    exit();
}

// Insert into database using prepared statement (store plain password)
$stmt = $conn->prepare("INSERT INTO users (full_name, email, phone, password) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $full_name, $email, $phone, $plain_password); // Store plain text

if ($stmt->execute()) {
    // Set session variables
    $_SESSION['loggedin'] = true;
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['email'] = $email;
    $_SESSION['full_name'] = $full_name;
    
    header("Location: login_success.php");
    exit();
} else {
    header("Location: signup.php?error=Registration failed");
    exit();
}

$stmt->close();
$check_stmt->close();
mysqli_close($conn);
?>