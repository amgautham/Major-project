<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form values safely
    $district = isset($_POST['district']) ? $_POST['district'] : "Not selected";
    $area = isset($_POST['area']) ? (float) $_POST['area'] : 0;
    $unit = isset($_POST['unit']) ? $_POST['unit'] : "sqft";
    $residential = isset($_POST['residential']) ? $_POST['residential'] : "Not selected";

    // Mapping for class selection
    $classMapping = [
        "1bhk" => "1 BHK",
        "2bhk" => "2 BHK",
        "3bhk" => "3 BHK",
        "4bhk" => "4 BHK"
    ];

    // Convert selected class to readable format
    $classSelected = isset($classMapping[$residential]) ? $classMapping[$residential] : "Unknown";

    // Display the collected data
    echo "<h2>Form Submission Results:</h2>";
    echo "<p><strong>District:</strong> $district</p>";
    echo "<p><strong>Area:</strong> $area $unit</p>";
    echo "<p><strong>Building Type:</strong> $classSelected</p>";

} else {
    echo "<p>Error: Invalid form submission.</p>";
}
?>
