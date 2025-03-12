<?php
// Database connection details
$host = "localhost"; // Change if needed
$user = "root"; // Your MySQL username
$pass = ""; // Your MySQL password
$dbname = "low_cost_housing"; // Replace with your actual database name

// Create connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL Query to fetch all data
$sql = "SELECT 
            d.district_id, 
            d.district_name, 
            m.material_id, 
            m.material_name, 
            mp.low_price, 
            mp.medium_price, 
            mp.high_price, 
            mp.last_updated 
        FROM material_prices mp
        JOIN districts d ON mp.district_id = d.district_id
        JOIN materials m ON mp.material_id = m.material_id
        ORDER BY d.district_id, m.material_id";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Material Prices Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h2>Complete Material Prices Data</h2>
<table>
    <tr>
        <th>District ID</th>
        <th>District Name</th>
        <th>Material ID</th>
        <th>Material Name</th>
        <th>Low Price</th>
        <th>Medium Price</th>
        <th>High Price</th>
        <th>Last Updated</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['district_id']}</td>
                    <td>{$row['district_name']}</td>
                    <td>{$row['material_id']}</td>
                    <td>{$row['material_name']}</td>
                    <td>{$row['low_price']}</td>
                    <td>{$row['medium_price']}</td>
                    <td>{$row['high_price']}</td>
                    <td>{$row['last_updated']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No data available</td></tr>";
    }
    ?>
</table>

</body>
</html>

<?php
$conn->close();
?>
