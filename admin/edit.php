<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "low_cost_housing");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch districts
$districts_result = $conn->query("SELECT district_id, district_name FROM districts");

// Fetch materials
$materials_result = $conn->query("SELECT material_id, material_name FROM materials");

// Default values
$district_id = isset($_POST['district_id']) ? (int)$_POST['district_id'] : 0;
$material_id = isset($_POST['material_id']) ? (int)$_POST['material_id'] : 0;
$low_price = $medium_price = $high_price = "";

// Fetch existing prices when both district & material are selected
if ($district_id > 0 && $material_id > 0) {
    $sql = "SELECT low_price, medium_price, high_price 
            FROM material_prices 
            WHERE district_id = ? AND material_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $district_id, $material_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $low_price = $row['low_price'];
        $medium_price = $row['medium_price'];
        $high_price = $row['high_price'];
    } else {
        echo "<p style='color: red;'>No price data found for this District & Material.</p>";
    }
}

// Handle price update
if (isset($_POST['update_prices'])) {
    $new_low_price = isset($_POST['low_price']) ? (float)$_POST['low_price'] : 0;
    $new_medium_price = isset($_POST['medium_price']) ? (float)$_POST['medium_price'] : 0;
    $new_high_price = isset($_POST['high_price']) ? (float)$_POST['high_price'] : 0;

    // Check if price data exists for this district & material
    $check_sql = "SELECT * FROM material_prices WHERE district_id = ? AND material_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $district_id, $material_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update existing price
        $update_sql = "UPDATE material_prices 
                       SET low_price = ?, medium_price = ?, high_price = ?, last_updated = CURRENT_TIMESTAMP 
                       WHERE district_id = ? AND material_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("dddii", $new_low_price, $new_medium_price, $new_high_price, $district_id, $material_id);

        if ($update_stmt->execute()) {
            echo "<p style='color: green;'>Prices updated successfully!</p>";
            header("Refresh: 1");
            exit;
        } else {
            echo "<p style='color: red;'>Error updating prices: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color: red;'>No price data found for this selection.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Material Prices</title>
</head>
<body>
    <h2>Edit Material Prices</h2>

    <form method="post">
        <label>Select District:</label>
        <select name="district_id" required onchange="this.form.submit()">
            <option value="">-- Select District --</option>
            <?php while ($row = $districts_result->fetch_assoc()): ?>
                <option value="<?= $row['district_id']; ?>" <?= ($row['district_id'] == $district_id) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($row['district_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <label>Select Material:</label>
        <select name="material_id" required onchange="this.form.submit()">
            <option value="">-- Select Material --</option>
            <?php while ($row = $materials_result->fetch_assoc()): ?>
                <option value="<?= $row['material_id']; ?>" <?= ($row['material_id'] == $material_id) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($row['material_name']); ?>
                </option>
            <?php endwhile; ?>
        </select>

        <br><br>

        <label>Low Price:</label>
        <input type="number" step="0.01" name="low_price" value="<?= htmlspecialchars($low_price); ?>" required><br><br>

        <label>Medium Price:</label>
        <input type="number" step="0.01" name="medium_price" value="<?= htmlspecialchars($medium_price); ?>" required><br><br>

        <label>High Price:</label>
        <input type="number" step="0.01" name="high_price" value="<?= htmlspecialchars($high_price); ?>" required><br><br>

        <button type="submit" name="update_prices">Update Prices</button>
    </form>

    <br>
    <a href="admin.php">Back to Data List</a>
</body>
</html>
