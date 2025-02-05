<?php
// db.php - MySQL Connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "house";  // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch records from all tables
$districts_query = "SELECT * FROM districts";
$sub_localities_query = "SELECT * FROM sub_localities";
$materials_query = "SELECT * FROM materials";
$prices_query = "SELECT * FROM material_prices";

// Execute queries
$districts_result = $conn->query($districts_query);
$sub_localities_result = $conn->query($sub_localities_query);
$materials_result = $conn->query($materials_query);
$prices_result = $conn->query($prices_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Tables</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 50px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f9;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Panel - View Tables</h1>

    <h2>Districts</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>District Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $districts_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['district_id']; ?></td>
                    <td><?php echo $row['district_name']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h2>Sub-localities</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Sub-locality Name</th>
                <th>District ID</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $sub_localities_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['sub_locality_id']; ?></td>
                    <td><?php echo $row['sub_locality_name']; ?></td>
                    <td><?php echo $row['district_id']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h2>Materials</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Material Name</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $materials_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['material_id']; ?></td>
                    <td><?php echo $row['material_name']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <h2>Material Prices</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Sub-locality ID</th>
                <th>Material ID</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $prices_result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['price_id']; ?></td>
                    <td><?php echo $row['sub_locality_id']; ?></td>
                    <td><?php echo $row['material_id']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

</div>

</body>
</html>

<?php
// Close connection
$conn->close();
?>
