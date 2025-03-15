<?php
// estimator.php

// 1. Database connection
$conn = new mysqli("localhost", "root", "", "buildwise");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

/**
 * getCategoryQuantity
 * Returns how much area (or quantity) is needed for each category.
 * 
 * @param string $category   E.g., "BATHROOM FLOORING", "BATHROOM WALL", "CEILING", etc.
 * @param float  $houseArea  The total house area (sq.ft) user entered.
 * 
 * The formulas below are just EXAMPLES:
 * - BATHROOM FLOORING => ~8% of house area is bathrooms; tile size 2×2 => 4 sq.ft each + 10% wastage
 * - BATHROOM WALL    => ~8% of house area for bathrooms × 3 for walls, tile size 1 sq.ft + 10% wastage
 * - CEILING          => ~100% of house area for a single-story home
 * - FLOORING         => ~100% of house area
 * - KITCHEN CABINETS => ~10% of house area as a rough guess for cabinets
 * - KITCHEN COUNTERTOPS => ~5% of house area for counters
 * - KITCHEN FLOORING => ~7% of house area for kitchen floor
 * - KITCHEN WALL TILES => ~7% of house area × 3 for walls, tile size 1 sq.ft + 10% wastage
 * - ROOFING          => ~houseArea × 1.1 for overhang
 */
function getCategoryQuantity($category, $houseArea) {
    // Convert to uppercase or something consistent for matching
    $cat = strtoupper($category);

    switch ($cat) {
        case 'BATHROOM FLOORING':
            // ~8% of total area for bathrooms
            $bathFloorArea = $houseArea * 0.08; 
            // 2×2 ft tile => 4 sq.ft each
            // +10% wastage
            $tilesNeeded = ($bathFloorArea / 4.0) * 1.1;
            return ceil($tilesNeeded);

        case 'BATHROOM WALL':
            // ~8% of house area for bathrooms, times 3 for walls
            // tile size 1 sq.ft, +10% wastage
            $bathWallArea = ($houseArea * 0.08) * 3;
            $tilesNeeded = $bathWallArea * 1.1; 
            return ceil($tilesNeeded);

        case 'CEILING':
            // Assume ceiling ~100% of house area
            return ceil($houseArea);

        case 'FLOORING':
            // Main flooring ~100% of house area
            return ceil($houseArea);

        case 'KITCHEN CABINETS':
            // EXAMPLE: ~10% of house area used for cabinets
            // This is quite rough. You might do linear feet instead.
            $cabinetQty = $houseArea * 0.10;
            return ceil($cabinetQty);

        case 'KITCHEN COUNTERTOPS':
            // EXAMPLE: ~5% of house area for counters
            $counterArea = $houseArea * 0.05;
            return ceil($counterArea);

        case 'KITCHEN FLOORING':
            // EXAMPLE: ~7% of house area for kitchen floor
            // tile size 4 sq.ft if you want? 
            // For simplicity, just return area portion:
            $kitchFloor = $houseArea * 0.07;
            return ceil($kitchFloor);

        case 'KITCHEN WALL TILES':
            // ~7% of house area for kitchen, times 3 for walls
            // tile size 1 sq.ft, +10% wastage
            $kitchWall = ($houseArea * 0.07) * 3;
            $tilesNeeded = $kitchWall * 1.1;
            return ceil($tilesNeeded);

        case 'ROOFING':
            // EXAMPLE: ~houseArea × 1.1 for overhang
            $roofArea = $houseArea * 1.1;
            return ceil($roofArea);

        default:
            // If we don't recognize the category, fallback to houseArea
            return ceil($houseArea);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Low-Cost Housing Estimator</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; margin-top: 15px; width: 100%; }
        table, th, td { border: 1px solid #888; padding: 8px; text-align: center; }
        th { background-color: #eee; }
        input[type="submit"] { margin-top: 15px; padding: 8px 12px; }
        #chartContainer { width: 400px; height: 400px; margin-top: 30px; }
    </style>
    <!-- Include Chart.js from CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Low-Cost Housing Estimator</h2>
    <form method="post" action="">
        <label>Area (sq.ft): </label>
        <input type="number" step="any" name="area" 
               value="<?php echo isset($_POST['area']) ? htmlspecialchars($_POST['area']) : ''; ?>" required>
        <br><br>
        
        <label>BHK: </label>
        <select name="bhk" required>
            <option value="1" <?php if(isset($_POST['bhk']) && $_POST['bhk'] == "1") echo 'selected'; ?>>1 BHK</option>
            <option value="2" <?php if(isset($_POST['bhk']) && $_POST['bhk'] == "2") echo 'selected'; ?>>2 BHK</option>
            <option value="3" <?php if(isset($_POST['bhk']) && $_POST['bhk'] == "3") echo 'selected'; ?>>3 BHK</option>
        </select>
        <br><br>
        
        <label>District: </label>
        <select name="district_id" required>
            <option value="">Select District</option>
            <?php
            // Fetch districts
            $districts_query = "SELECT * FROM districts";
            $districts_result = $conn->query($districts_query);
            while($row = $districts_result->fetch_assoc()){
                $selected = (isset($_POST['district_id']) && $_POST['district_id'] == $row['district_id']) ? 'selected' : '';
                echo "<option value='{$row['district_id']}' $selected>{$row['district_name']}</option>";
            }
            ?>
        </select>
        <br><br>
        
        <?php
        // For cost breakdown
        $breakdown = array();

        // If user clicked "Load Materials" or "Calculate Estimation"
        if (isset($_POST['load_materials']) || isset($_POST['calculate_estimation'])) {
            $district_id = intval($_POST['district_id']);
            $bhk = intval($_POST['bhk']);
            $area = floatval($_POST['area']);

            echo "<h3>Materials for Selected District</h3>";
            
            // Query distinct materials for chosen district
            $query = "SELECT DISTINCT m.material_id, m.material_name 
                      FROM materials m 
                      JOIN material_prices mp ON m.material_id = mp.material_id
                      WHERE mp.district_id = $district_id";
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Material (Category)</th><th>Type</th><th>Cost (Quantity × Price × BHK)</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $material_id   = $row['material_id'];
                    $material_name = $row['material_name']; // e.g., "BATHROOM FLOORING", "KITCHEN CABINETS"

                    // 1. Get available types & prices for this material
                    $types_query = "SELECT material_type, price 
                                    FROM material_prices 
                                    WHERE district_id = $district_id 
                                      AND material_id = $material_id";
                    $types_result = $conn->query($types_query);

                    $options = "<option value=''>Select Type</option>";
                    while ($type = $types_result->fetch_assoc()) {
                        $selected_value = "";
                        if (isset($_POST['selected_type'][$material_id]) && 
                            $_POST['selected_type'][$material_id] == $type['price']) {
                            $selected_value = "selected";
                        }
                        $options .= "<option value='{$type['price']}' $selected_value>{$type['material_type']}</option>";
                    }

                    // 2. Determine user-selected price
                    $selected_price = isset($_POST['selected_type'][$material_id])
                        ? floatval($_POST['selected_type'][$material_id])
                        : 0;

                    // 3. Calculate quantity from category formula
                    $quantity = getCategoryQuantity($material_name, $area);

                    // 4. Final cost = quantity × price × BHK
                    $cost = $quantity * $selected_price * $bhk;

                    // Save for chart
                    $breakdown[] = array(
                        'material_name' => $material_name,
                        'cost' => $cost
                    );

                    echo "<tr>
                            <td>{$material_name}</td>
                            <td>
                                <select name='selected_type[$material_id]'>
                                    $options
                                </select>
                            </td>
                            <td>" . number_format($cost, 2) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "No materials found for the selected district.<br>";
            }
        }

        // If "Calculate Estimation" pressed, sum total cost & display chart
        if (isset($_POST['calculate_estimation'])) {
            $bhk = intval($_POST['bhk']);
            $area = floatval($_POST['area']);
            $total = 0;

            foreach ($breakdown as $item) {
                $total += $item['cost'];
            }
            echo "<h3>Total Estimated Cost: ₹" . number_format($total, 2) . "</h3>";

            // Prepare data for the chart
            $labels = array();
            $costValues = array();
            foreach ($breakdown as $item) {
                $labels[] = $item['material_name'];
                $costValues[] = $item['cost'];
            }
        }
        ?>
        
        <br>
        <?php
        // Display correct button
        if (isset($_POST['load_materials']) || isset($_POST['calculate_estimation'])) {
            echo "<input type='submit' name='calculate_estimation' value='Calculate Estimation'>";
        } else {
            echo "<input type='submit' name='load_materials' value='Load Materials'>";
        }
        ?>
    </form>
    
    <?php if (isset($_POST['calculate_estimation'])): ?>
    <h3>Cost Breakdown</h3>
    <div id="chartContainer">
        <canvas id="costBreakdownChart"></canvas>
    </div>
    <script>
        var labels = <?php echo json_encode($labels); ?>;
        var costValues = <?php echo json_encode($costValues); ?>;
        
        const ctx = document.getElementById('costBreakdownChart').getContext('2d');
        if (window.costChart) {
            window.costChart.destroy();
        }
        window.costChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: costValues,
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                        '#9966FF', '#FF9F40', '#C9CBCF', '#8BC34A',
                        '#607D8B', '#F06292'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'right' },
                    title: {
                        display: true,
                        text: 'Total Estimated Cost (INR)'
                    }
                },
                cutout: '50%'
            }
        });
    </script>
    <?php endif; ?>
</body>
</html>
