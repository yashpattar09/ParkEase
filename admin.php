<?php
// admin.php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parkease";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get parking statistics
function getParkingStatistics($conn) {
    $stats = array();
    
    // Get total statistics
    $totalQuery = "SELECT 
                    COUNT(spot_id) as total_spots,
                    SUM(CASE WHEN status = 'occupied' THEN 1 ELSE 0 END) as occupied_spots
                   FROM parking_spots";
    $totalResult = $conn->query($totalQuery);
    if ($totalResult->num_rows > 0) {
        $stats['total'] = $totalResult->fetch_assoc();
    } else {
        // Default values if query fails
        $stats['total'] = array(
            'total_spots' => 0,
            'occupied_spots' => 0
        );
    }
    
    // Get statistics by location
    $locationsQuery = "SELECT 
                        l.location_id, 
                        l.location_name, 
                        COUNT(sp.spot_id) as total_slots,
                        SUM(CASE WHEN sp.status = 'occupied' THEN 1 ELSE 0 END) as occupied_slots
                       FROM parking_locations l
                       LEFT JOIN parking_sections s ON l.location_id = s.location_id
                       LEFT JOIN parking_spots sp ON s.section_id = sp.section_id
                       GROUP BY l.location_id, l.location_name";
    $locationsResult = $conn->query($locationsQuery);
    
    if ($locationsResult->num_rows > 0) {
        while($row = $locationsResult->fetch_assoc()) {
            $stats['locations'][$row['location_id']] = $row;
            
            // Get sections for each location
            $sectionsQuery = "SELECT 
                                s.section_id,
                                s.section_name,
                                COUNT(sp.spot_id) as total_spots,
                                SUM(CASE WHEN sp.status = 'occupied' THEN 1 ELSE 0 END) as occupied_spots
                              FROM parking_sections s
                              LEFT JOIN parking_spots sp ON s.section_id = sp.section_id
                              WHERE s.location_id = " . $row['location_id'] . "
                              GROUP BY s.section_id, s.section_name";
            $sectionsResult = $conn->query($sectionsQuery);
            
            if ($sectionsResult->num_rows > 0) {
                while($section = $sectionsResult->fetch_assoc()) {
                    $stats['locations'][$row['location_id']]['sections'][] = $section;
                    
                    // Get spots for each section
                    $spotsQuery = "SELECT 
                                    spot_id, 
                                    spot_name, 
                                    status
                                   FROM parking_spots
                                   WHERE section_id = " . $section['section_id'];
                    $spotsResult = $conn->query($spotsQuery);
                    
                    if ($spotsResult->num_rows > 0) {
                        while($spot = $spotsResult->fetch_assoc()) {
                            $stats['locations'][$row['location_id']]['sections'][count($stats['locations'][$row['location_id']]['sections'])-1]['spots'][] = $spot;
                        }
                    }
                }
            }
        }
    }
    
    return $stats;
}

// Get all parking statistics
$parkingStats = getParkingStatistics($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="container">
        <header>
            <a href="home.php" class="home-btn">Home</a>
            <h1>Parking Dashboard</h1>
        </header>
        
        <div class="stats-container">
            <div class="stat-card total-spots">
                <h3>Total Spots</h3>
                <div class="number"><?php echo $parkingStats['total']['total_spots'] ?? 0; ?></div>
            </div>
            
            <div class="stat-card occupied">
                <h3>Occupied</h3>
                <div class="number"><?php echo $parkingStats['total']['occupied_spots'] ?? 0; ?></div>
            </div>
        </div>
        
        <?php if (!empty($parkingStats['locations'])): ?>
            <?php foreach ($parkingStats['locations'] as $location): ?>
                <div class="parking-section">
                    <h2><?php echo htmlspecialchars($location['location_name']); ?></h2>
                    
                    <div class="section-stats">
                        <div class="section-stat occupied">
                            <h4>Occupied</h4>
                            <div class="number"><?php echo $location['occupied_slots'] ?? 0; ?></div>
                        </div>
                    </div>
                    
                    <?php if (!empty($location['sections'])): ?>
                        <?php foreach ($location['sections'] as $section): ?>
                            <h3>Section: <?php echo htmlspecialchars($section['section_name']); ?></h3>
                            <div class="parking-grid" id="<?php echo strtolower(str_replace(' ', '', $location['location_name'])) . '_' . $section['section_id']; ?>">
                                <?php if (!empty($section['spots'])): ?>
                                    <?php foreach ($section['spots'] as $spot): ?>
                                        <div class="parking-spot <?php echo $spot['status']; ?>">
                                            <div><?php echo htmlspecialchars($spot['spot_name']); ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>No spots found in this section.</p>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>No sections found for this location.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No parking locations found.</p>
        <?php endif; ?>
    </div>

    <script>
        // Function to refresh the page data every 10 seconds
        function refreshData() {
            setTimeout(function() {
                window.location.reload();
            }, 10000); // 10 seconds
        }

        // Initialize refresh when page loads
        document.addEventListener('DOMContentLoaded', function() {
            refreshData();
        });
    </script>
</body>
</html>
<?php
// Close connection
$conn->close();
?>