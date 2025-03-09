<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district = $_POST["district"] ?? "Not selected";
    $location = $_POST["location"] ?? "Not selected";
    $area = $_POST["area"] ?? "0";
    $unit = $_POST["unit"] ?? "sqft";
    $residential = $_POST["residential"] ?? "Not selected";

    // Convert to square feet if unit is sqm
    if ($unit == "sqm") {
        $area = $area * 10.764;
    }

    echo "<h2>Selected Values:</h2>";
    echo "<p><strong>District:</strong> $district</p>";
    echo "<p><strong>Location:</strong> $location</p>";
    echo "<p><strong>Area:</strong> $area sq. feet</p>";
    echo "<p><strong>Residential Type:</strong> $residential</p>";
}
?>
