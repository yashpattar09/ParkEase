<?php
$servername = "localhost"; // or 127.0.0.1
$username = "root";        // change if you use a different user
$password = "";            // your DB password
$dbname = "test_db";       // make sure this DB exists

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];

// Insert into database
$sql = "INSERT INTO users (name, email) VALUES ('$name', '$email')";

if ($conn->query($sql) === TRUE) {
  echo "New record inserted successfully!";
} else {
  echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
