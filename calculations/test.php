<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "low_cost_housing");

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch districts for the dropdown menu
$districts_result = $conn->query("SELECT district_id, district_name FROM districts");

// Initialize variables
$area = $building_type = $district_id = 0;
$materials = [];
$costs = [];
$total_cost = ['low' => 0, 'medium' => 0, 'high' => 0];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $area = (float)$_POST['area'];
    $building_type = (int)$_POST['building_type'];
    $district_id = (int)$_POST['district_id'];

    /*
     * Material Estimation Rates based on Building Type
     * The rates are per square foot.
     * Example: Cement rate = 0.4 bags per sq. ft.
     * If the area is 1000 sq. ft., Cement needed = 1000 * 0.4 = 400 bags.
     */
    $estimation_rates = [
        1 => ["cement" => 0.4, "sand" => 0.6, "aggregate" => 0.8, "bricks" => 6, "steel" => 2, "flooring" => 1.1, "doors" => 0.03, "windows" => 0.02, "electrical fittings" => 0.015],
        2 => ["cement" => 0.45, "sand" => 0.65, "aggregate" => 0.9, "bricks" => 7, "steel" => 2.5, "flooring" => 1.15, "doors" => 0.035, "windows" => 0.025, "electrical fittings" => 0.02],
        3 => ["cement" => 0.5, "sand" => 0.7, "aggregate" => 1.0, "bricks" => 8, "steel" => 3, "flooring" => 1.2, "doors" => 0.04, "windows" => 0.03, "electrical fittings" => 0.025]
    ];

    // Calculate material quantities based on area and rates
    $material_quantity = [];
    foreach ($estimation_rates[$building_type] as $material => $rate) {
        $material_quantity[strtolower($material)] = round($area * $rate, 2);
    }

    /*
     * Fetch material prices from the database
     * The LEFT JOIN ensures we retrieve price details based on the selected district.
     */
    $sql = "SELECT m.material_id, m.material_name, mp.low_price, mp.medium_price, mp.high_price
            FROM materials m
            LEFT JOIN material_prices mp ON m.material_id = mp.material_id AND mp.district_id = ?
            WHERE m.material_name IN ('Aggregate', 'Bricks', 'Cement', 'Doors', 'Electrical Fittings', 'Flooring', 'Sand', 'Steel', 'Windows')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $district_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $material_key = strtolower($row['material_name']); // Normalize material names to lowercase
        $materials[] = $row;

        // Check if the material has a calculated quantity, otherwise set cost to 0
        if (isset($material_quantity[$material_key])) {
            /*
             * Cost Calculation:
             * Cost = Quantity × Price
             * Three price ranges are provided: Low, Medium, and High
             */
            $costs[$row['material_id']] = [
                'low' => $row['low_price'] * $material_quantity[$material_key],
                'medium' => $row['medium_price'] * $material_quantity[$material_key],
                'high' => $row['high_price'] * $material_quantity[$material_key]
            ];

            // Sum up the total estimated costs for all materials
            $total_cost['low'] += $costs[$row['material_id']]['low'];
            $total_cost['medium'] += $costs[$row['material_id']]['medium'];
            $total_cost['high'] += $costs[$row['material_id']]['high'];
        } else {
            $costs[$row['material_id']] = ['low' => 0, 'medium' => 0, 'high' => 0];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Building Estimation</title>
    <script>
        // Function to update the total cost dynamically when price range is selected
        function updateTotalCost(quality) {
            document.getElementById("total_cost").innerText = document.getElementById(quality + "_total").value;
        }
    </script>
</head>
<body>
    <h2>Estimate Building Cost</h2>
    <form method="post">
        <label>Enter Area (sq. ft.):</label>
        <input type="number" name="area" required> <br><br>

        <label>Select Building Type:</label>
        <select name="building_type" required>
            <option value="1">1BHK</option>
            <option value="2">2BHK</option>
            <option value="3">3BHK</option>
        </select> <br><br>

        <label>Select District:</label>
        <select name="district_id" required>
            <option value="">-- Select District --</option>
            <?php while ($row = $districts_result->fetch_assoc()): ?>
                <option value="<?= $row['district_id']; ?>" <?= ($row['district_id'] == $district_id) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($row['district_name']); ?>
                </option>
            <?php endwhile; ?>
        </select> <br><br>

        <button type="submit">Get Estimation</button>
    </form>

    <?php if (!empty($materials)): ?>
        <h3>Estimation Result</h3>
        <table border="1">
            <tr>
                <th>Material</th>
                <th>Quantity</th>
                <th>Quality</th>
                <th>Cost</th>
            </tr>
            <?php foreach ($materials as $mat): ?>
                <tr>
                    <td><?= htmlspecialchars($mat['material_name']); ?></td>
                    <td>
                        <?= isset($material_quantity[strtolower($mat['material_name'])]) ? $material_quantity[strtolower($mat['material_name'])] : 'N/A'; ?>
                    </td>
                    <td>
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" onclick="updateTotalCost('low')" checked> Low
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" onclick="updateTotalCost('medium')"> Medium
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" onclick="updateTotalCost('high')"> High
                    </td>
                    <td>
                        <?= number_format($costs[$mat['material_id']]['low'], 2); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Display total estimated cost -->
        <h3>Total Estimated Cost: ₹ <span id="total_cost"><?= number_format($total_cost['low'], 2); ?></span></h3>
        <input type="hidden" id="low_total" value="<?= number_format($total_cost['low'], 2); ?>">
        <input type="hidden" id="medium_total" value="<?= number_format($total_cost['medium'], 2); ?>">
        <input type="hidden" id="high_total" value="<?= number_format($total_cost['high'], 2); ?>">
    <?php endif; ?>
</body>
</html>
