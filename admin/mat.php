<?php
// Include your database connection
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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sub_locality_id = $_POST['sub_locality_id'];  // Only one sub-locality selected
    $material_ids = $_POST['material_id'];
    $prices = $_POST['price'];

    // Insert or update prices for the selected sub-locality
    for ($i = 0; $i < count($material_ids); $i++) {
        $material_id = $material_ids[$i];
        $price = $prices[$i];

        // Add or update price logic
        $stmt = $conn->prepare("INSERT INTO material_prices (sub_locality_id, material_id, price) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE price = ?");
        $stmt->bind_param($sub_locality_id, $material_id, $price, $price);
        $stmt->execute();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Material Prices</title>
    <style>
        /* Add some basic styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        input[type="number"] {
            width: 100px;
            padding: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h2>Manage Material Prices</h2>

<form action="admin.php" method="POST">
    <label for="sub_locality">Select Sub-locality:</label>
    <select name="sub_locality_id" id="sub_locality" required>
        <option value="">Select Sub-locality</option>
        <?php
        // Fetch sub-localities for the dropdown
        $sub_localities_result = $conn->query("SELECT * FROM sub_localities");

        // Loop through and populate the sub-locality dropdown
        while ($sub_locality = $sub_localities_result->fetch_assoc()) {
            echo "<option value='" . $sub_locality['sub_locality_id'] . "'>" . $sub_locality['sub_locality_name'] . "</option>";
        }
        ?>
    </select>
    
    <table>
        <thead>
            <tr>
                <th>Material</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Fetch materials
            $materials_result = $conn->query("SELECT * FROM materials");

            // Loop through the materials and display in table
            while ($material = $materials_result->fetch_assoc()) {
                ?>
                <tr>
                    <td>
                        <?php echo $material['material_name']; ?>
                        <input type="hidden" name="material_id[]" value="<?php echo $material['material_id']; ?>">
                    </td>
                    <td>
                        <input type="number" name="price[]" placeholder="Price" step="0.01" required>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
    <button type="submit">Update Prices</button>
</form>

</body>
</html>
