<?php
require 'db_connection.php';

$location = $_GET['location'] ?? '';

// Map location names to location_id values
$location_map = [
    'Shahapur' => 1,
    'Vadagoan' => 2,
    'Tilakwadi' => 3,
    'Angol' => 4,
    'Sadashiv_nagar' => 5,
    'Shivbasava_nagar' => 6,
    'Neharu_nagar' => 7,
    'Gandhi_nagar' => 8
];

$location_id = $location_map[$location] ?? 0;

if ($location_id === 0) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$query = "SELECT spot_id, spot_name, status FROM parking_spots 
          WHERE location_id = ? 
          ORDER BY spot_name";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $location_id);
$stmt->execute();
$result = $stmt->get_result();

$spots = [];
while ($row = $result->fetch_assoc()) {
    $spots[] = $row;
}

header('Content-Type: application/json');
echo json_encode($spots);

$stmt->close();
$conn->close();
?>