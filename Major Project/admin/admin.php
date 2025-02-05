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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert District
    if (isset($_POST['district'])) {
        $district_name = $_POST['district'];
        $sql = "INSERT INTO districts (district_name) VALUES ('$district_name')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='message success'>New district added successfully.</div>";
        } else {
            echo "<div class='message error'>Error: " . $conn->error . "</div>";
        }
    }
    // Insert Sub-locality
    elseif (isset($_POST['sub_locality'])) {
        $sub_locality_name = $_POST['sub_locality'];
        $district_id = $_POST['district_id'];
        $sql = "INSERT INTO sub_localities (sub_locality_name, district_id) VALUES ('$sub_locality_name', $district_id)";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='message success'>New sub-locality added successfully.</div>";
        } else {
            echo "<div class='message error'>Error: " . $conn->error . "</div>";
        }
    }
    // Insert Material
    elseif (isset($_POST['material'])) {
        $material_name = $_POST['material'];
        $sql = "INSERT INTO materials (material_name) VALUES ('$material_name')";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='message success'>New material added successfully.</div>";
        } else {
            echo "<div class='message error'>Error: " . $conn->error . "</div>";
        }
    }
    // Insert Material Price
    elseif (isset($_POST['material_price'])) {
        $sub_locality_id = $_POST['sub_locality_id'];
        $material_id = $_POST['material_id'];
        $price = $_POST['price'];
        $sql = "INSERT INTO material_prices (sub_locality_id, material_id, price) VALUES ($sub_locality_id, $material_id, $price)";
        if ($conn->query($sql) === TRUE) {
            echo "<div class='message success'>Material price added successfully.</div>";
        } else {
            echo "<div class='message error'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Low Cost Housing</title>
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
        h2 {
            margin-bottom: 10px;
        }
        form {
            margin-bottom: 20px;
        }
        input[type="text"], input[type="number"], select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        select {
            background-color: #fff;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Admin Panel</h1>

    <!-- Add District Form -->
    <h2>Add District</h2>
    <form action="admin.php" method="POST">
        <input type="text" name="district" placeholder="District Name" required>
        <button type="submit">Add District</button>
    </form>

    <!-- Add Sub-locality Form -->
    <h2>Add Sub-locality</h2>
    <form action="admin.php" method="POST">
        <input type="text" name="sub_locality" placeholder="Sub-locality Name" required>
        <select name="district_id" required>
            <option value="">Select District</option>
            <?php
            // Fetch districts to populate the dropdown
            $result = $conn->query("SELECT * FROM districts");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['district_id']}'>{$row['district_name']}</option>";
            }
            ?>
        </select>
        <button type="submit">Add Sub-locality</button>
    </form>

    <!-- Add Material Form -->
    <h2>Add Material</h2>
    <form action="admin.php" method="POST">
        <input type="text" name="material" placeholder="Material Name" required>
        <button type="submit">Add Material</button>
    </form>

    <!-- Add Material Price Form -->
    <h2>Add Material Price</h2>
    <form action="admin.php" method="POST">
        <select name="sub_locality_id" required>
            <option value="">Select Sub-locality</option>
            <?php
            // Fetch sub-localities to populate the dropdown
            $result = $conn->query("SELECT * FROM sub_localities");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['sub_locality_id']}'>{$row['sub_locality_name']}</option>";
            }
            ?>
        </select>
        <select name="material_id" required>
            <option value="">Select Material</option>
            <?php
            // Fetch materials to populate the dropdown
            $result = $conn->query("SELECT * FROM materials");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['material_id']}'>{$row['material_name']}</option>";
            }
            ?>
        </select>
        <input type="number" name="price" placeholder="Price" step="0.01" required>
        <button type="submit">Add Price</button>
    </form>
</div>

</body>
</html>
