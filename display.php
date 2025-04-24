<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "test_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Fetch data
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Display Users</title>
  <style>
    table {
      border-collapse: collapse;
      width: 60%;
      margin: 20px auto;
    }
    th, td {
      border: 1px solid #666;
      padding: 10px;
      text-align: left;
    }
    th {
      background-color: #ddd;
    }
  </style>
</head>
<body>
  <h2 style="text-align:center;">User Submissions</h2>
  <table>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Email</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["id"]. "</td>
                <td>" . $row["name"]. "</td>
                <td>" . $row["email"]. "</td>
              </tr>";
      }
    } else {
      echo "<tr><td colspan='3'>No data available</td></tr>";
    }
    $conn->close();
    ?>
  </table>
</body>
</html>
