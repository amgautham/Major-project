<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "low_cost_housing");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch districts for dropdown
$districts_result = $conn->query("SELECT district_id, district_name FROM districts");

$area = $building_type = $district_id = 0;
$materials = [];
$costs = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $area = (float)$_POST['area'];
    $building_type = (int)$_POST['building_type'];
    $district_id = (int)$_POST['district_id'];

    // Material estimation rates based on building type
    $estimation_rates = [
        1 => ["cement" => 0.4, "sand" => 0.6, "aggregate" => 0.8, "bricks" => 6, "steel" => 2, "flooring" => 1.1, "doors" => 0.03, "windows" => 0.02, "electrical fittings" => 0.015],
        2 => ["cement" => 0.45, "sand" => 0.65, "aggregate" => 0.9, "bricks" => 7, "steel" => 2.5, "flooring" => 1.15, "doors" => 0.035, "windows" => 0.025, "electrical fittings" => 0.02],
        3 => ["cement" => 0.5, "sand" => 0.7, "aggregate" => 1.0, "bricks" => 8, "steel" => 3, "flooring" => 1.2, "doors" => 0.04, "windows" => 0.03, "electrical fittings" => 0.025]
    ];

    // Calculate material quantities
    $material_quantity = [];
    foreach ($estimation_rates[$building_type] as $material => $rate) {
        $material_quantity[strtolower($material)] = round($area * $rate, 2);
    }

    // Fetch material prices from the database
    $sql = "SELECT m.material_id, m.material_name, mp.low_price, mp.medium_price, mp.high_price
            FROM materials m
            LEFT JOIN material_prices mp ON m.material_id = mp.material_id AND mp.district_id = ?
            WHERE m.material_name IN ('Aggregate', 'Bricks', 'Cement', 'Doors', 'Electrical Fittings', 'Flooring', 'Sand', 'Steel', 'Windows')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $district_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $material_key = strtolower($row['material_name']);
        $materials[] = $row;
        
        if (isset($material_quantity[$material_key])) {
            $costs[$row['material_id']] = [
                'low' => $row['low_price'] * $material_quantity[$material_key],
                'medium' => $row['medium_price'] * $material_quantity[$material_key],
                'high' => $row['high_price'] * $material_quantity[$material_key]
            ];
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
        function updateCost(material_id, quality) {
            let cost = document.getElementById(quality + "_" + material_id).value;
            document.getElementById("cost_" + material_id).innerText = cost;
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
                        <input type="hidden" id="low_<?= $mat['material_id']; ?>" value="<?= $costs[$mat['material_id']]['low']; ?>">
                        <input type="hidden" id="medium_<?= $mat['material_id']; ?>" value="<?= $costs[$mat['material_id']]['medium']; ?>">
                        <input type="hidden" id="high_<?= $mat['material_id']; ?>" value="<?= $costs[$mat['material_id']]['high']; ?>">
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" onclick="updateCost(<?= $mat['material_id']; ?>, 'low')" checked> Low
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" onclick="updateCost(<?= $mat['material_id']; ?>, 'medium')"> Medium
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" onclick="updateCost(<?= $mat['material_id']; ?>, 'high')"> High
                    </td>
                    <td id="cost_<?= $mat['material_id']; ?>">
                        <?= $costs[$mat['material_id']]['low']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>
