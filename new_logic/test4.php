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
 */
function getCategoryQuantity($category, $houseArea) {
    $cat = strtoupper($category);
    switch ($cat) {
        case 'BATHROOM FLOORING':
            $bathFloorArea = $houseArea * 0.08; 
            $tilesNeeded = ($bathFloorArea / 4.0) * 1.1;
            return ceil($tilesNeeded);
        case 'BATHROOM WALL':
            $bathWallArea = ($houseArea * 0.08) * 3;
            $tilesNeeded = $bathWallArea * 1.1; 
            return ceil($tilesNeeded);
        case 'CEILING':
            return ceil($houseArea);
        case 'FLOORING':
            return ceil($houseArea);
        case 'KITCHEN CABINETS':
            $cabinetQty = $houseArea * 0.10;
            return ceil($cabinetQty);
        case 'KITCHEN COUNTERTOPS':
            $counterArea = $houseArea * 0.05;
            return ceil($counterArea);
        case 'KITCHEN FLOORING':
            $kitchFloor = $houseArea * 0.07;
            return ceil($kitchFloor);
        case 'KITCHEN WALL TILES':
            $kitchWall = ($houseArea * 0.07) * 3;
            $tilesNeeded = $kitchWall * 1.1;
            return ceil($tilesNeeded);
        case 'ROOFING':
            $roofArea = $houseArea * 1.1;
            return ceil($roofArea);
        default:
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

        // Check if materials are to be loaded, best preference selected, or estimation calculated
        if (isset($_POST['load_materials']) || isset($_POST['best_preference']) || isset($_POST['calculate_estimation'])) {
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
                echo "<tr>
                        <th>Material (Category)</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Cost (Quantity × Price × BHK)</th>
                      </tr>";
                while ($row = $result->fetch_assoc()) {
                    $material_id   = $row['material_id'];
                    $material_name = $row['material_name']; // e.g., "BATHROOM FLOORING", "KITCHEN CABINETS"

                    // 1. Get available types & prices for this material.
                    // If best_preference button is clicked, order by priority (lower is better)
                    if (isset($_POST['best_preference'])) {
                        $types_query = "SELECT material_type, price, priority 
                                        FROM material_prices 
                                        WHERE district_id = $district_id 
                                          AND material_id = $material_id 
                                        ORDER BY priority ASC";
                    } else {
                        $types_query = "SELECT material_type, price 
                                        FROM material_prices 
                                        WHERE district_id = $district_id 
                                          AND material_id = $material_id";
                    }
                    $types_result = $conn->query($types_query);

                    $options = "<option value=''>Select Type</option>";
                    
                    // For Best Preference, mark only the first option (lowest priority) as selected.
                    if (isset($_POST['best_preference'])) {
                        $first_option = true;
                    }
                    while ($type = $types_result->fetch_assoc()) {
                        $selected_value = "";
                        if (isset($_POST['best_preference'])) {
                            if ($first_option) {
                                $selected_value = "selected";
                                $first_option = false;
                            }
                        } else {
                            if (isset($_POST['selected_type'][$material_id]) && 
                                $_POST['selected_type'][$material_id] == $type['price']) {
                                $selected_value = "selected";
                            }
                        }
                        $options .= "<option value='{$type['price']}' $selected_value>{$type['material_type']}</option>";
                    }

                    // 2. Determine user-selected price
                    $selected_price = isset($_POST['selected_type'][$material_id])
                        ? floatval($_POST['selected_type'][$material_id])
                        : (isset($_POST['best_preference']) && isset($first_option) ? 0 : 0);
                    // (When best preference is used, the first option is auto-selected via the dropdown.)

                    // 3. Get quantity: use user input if available, otherwise calculate from formula.
                    if (isset($_POST['quantity'][$material_id]) && $_POST['quantity'][$material_id] !== '') {
                        $quantity = floatval($_POST['quantity'][$material_id]);
                    } else {
                        $quantity = getCategoryQuantity($material_name, $area);
                    }

                    // 4. Final cost = quantity × price × BHK
                    // Use the auto-selected price if best_preference is set and no manual selection exists.
                    if (isset($_POST['best_preference']) && empty($_POST['selected_type'][$material_id])) {
                        // Re-run the query to get the best option price
                        $best_query = "SELECT price FROM material_prices 
                                       WHERE district_id = $district_id 
                                         AND material_id = $material_id 
                                       ORDER BY priority ASC LIMIT 1";
                        $best_result = $conn->query($best_query);
                        if ($best_result && $best_row = $best_result->fetch_assoc()) {
                            $selected_price = floatval($best_row['price']);
                        }
                    } else {
                        $selected_price = isset($_POST['selected_type'][$material_id])
                            ? floatval($_POST['selected_type'][$material_id])
                            : 0;
                    }
                    
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
                            <td>
                                <input type='number' step='any' name='quantity[$material_id]' value='{$quantity}' />
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
        // Display buttons.
        // Initially, show "Load Materials" and "Best Preference"
        if (!isset($_POST['load_materials']) && !isset($_POST['best_preference']) && !isset($_POST['calculate_estimation'])) {
            echo "<input type='submit' name='load_materials' value='Load Materials'> ";
            echo "<input type='submit' name='best_preference' value='Best Preference'>";
        } else {
            echo "<input type='submit' name='calculate_estimation' value='Calculate Estimation'>";
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
