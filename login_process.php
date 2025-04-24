<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "parkease_db";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Debug output
    error_log("Login attempt for email: $email");
    
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("s", $email);
    
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        // Debug output
        error_log("User found: " . print_r($user, true));
        
        // Plain text comparison (temporary)
        if ($password == $user['password_hash']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            
            // Debug output
            error_log("Login successful for user ID: " . $user['id']);
            
            // Redirect to success page
            header("Location: login_success.php");
            $stmt->close();
            mysqli_close($conn);
            exit();
        } else {
            error_log("Password mismatch for user: $email");
        }
    } else {
        error_log("No user found with email: $email");
    }
    
    // If we get here, login failed
    header("Location: login.html?error=1");
    $stmt->close();
    mysqli_close($conn);
    exit();
}

// If not POST request
mysqli_close($conn);
header("Location: login.html?error=2");
exit();
?>