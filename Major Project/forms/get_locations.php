<?php
// Database connection
include('db.php');

header('Content-Type: application/json');

// Check if district ID is provided
if (isset($_GET['district_id'])) {
    // Fetch locations based on the district ID
    $district_id = $_GET['district_id'];

    $sql = "SELECT locations FROM location WHERE district_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $district_id); // Using integer parameter binding
    $stmt->execute();
    $result = $stmt->get_result();

    $locations = [];
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row['locations'];
    }

    echo json_encode($locations);
    $stmt->close();
} else {
    // Fetch all districts with their IDs
    $sql = "SELECT id, dis FROM districts";
    $result = $conn->query($sql);

    $districts = [];
    while ($row = $result->fetch_assoc()) {
        $districts[] = [
            'id' => $row['id'],       // District ID
            'name' => $row['dis']     // District name
        ];
    }

    echo json_encode($districts);
}

$conn->close();
?>
