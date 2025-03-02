<?php
// Database connection
$host = "localhost";  // Change if needed
$user = "root";       // Change if needed
$pass = "";           // Change if needed
$dbname = "low_cost_housing"; // Change to your DB name

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch materials and prices for a specific sub-locality
$sub_locality_id = 2; // Change as needed
$sql = "SELECT m.material_name, mp.price 
        FROM materials m
        JOIN material_prices mp ON m.material_id = mp.material_id
        WHERE mp.sub_locality_id = $sub_locality_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Material Cost Calculator</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        tr:nth-child(even) { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2>Material Cost Calculation</h2>
<table>
    <tr>
        <th>Material</th>
        <th>Quantity</th>
        <th>Total Price</th>
    </tr>

    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $quantity = rand(1, 100); // Dummy quantity (1-100)
            $total_price = $quantity * $row["price"];
            echo "<tr>
                    <td>{$row['material_name']}</td>
                    <td>$quantity</td>
                    <td>₹" . number_format($total_price, 2) . "</td>
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
