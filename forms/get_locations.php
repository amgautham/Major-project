<?php
// Database connection
include('db.php');

header('Content-Type: application/json');

// Check if district ID is provided
if (isset($_GET['district_id'])) {
    // Fetch sub_locality_name based on the district ID
    $district_id = $_GET['district_id'];

    $sql = "SELECT sub_locality_name FROM sub_localities WHERE district_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $district_id); // Using integer parameter binding
    $stmt->execute();
    $result = $stmt->get_result();

    $sub_locality_name = [];
    while ($row = $result->fetch_assoc()) {
        $sub_locality_name[] = $row['sub_locality_name'];
    }

    echo json_encode($sub_locality_name);
    $stmt->close();
} else {
    // Fetch all districts with their IDs
    $sql = "SELECT district_id, district_name FROM districts";
    $result = $conn->query($sql);

    $districts = [];
    while ($row = $result->fetch_assoc()) {
        $districts[] = [
            'id' => $row['district_id'],       // District ID
            'name' => $row['district_name']     // District name
        ];
    }

    echo json_encode($districts);
}

$conn->close();
?>
