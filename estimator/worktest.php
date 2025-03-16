<?php
// cost_estimation.php

// Database connection
$conn = new mysqli("localhost", "root", "", "buildwise");

// Check if the connection is successful
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
    <title>Building Estimation</title>
    <style>
        .container {
            max-width: 5000px;
            background-color: #fffbee;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            font-size: 22px;
            font-weight: bold;
            color: #333;
        }

        h3 {
            color: #444;
            margin: 20px 0;
        }

        .calculator-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        .form-group {
            margin: 15px 0;
            text-align: left;
            width: 100%;
            max-width: 500px;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            display: block;
            color: #666;
            margin-bottom: 5px;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        button.next-button, input[type="submit"] {
            background-color: #fdd835;
            color: black;
            font-weight: bold;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        button.next-button:hover, input[type="submit"]:hover {
            background-color: #fbc02d;
        }

        table {
            width: 100%;
            max-width: 900px;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 15px;
            text-align: left;
            font-size: 14px;
        }

        th {
            background-color: #fdd835;
            color: #333;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr {
            border-bottom: 1px solid #eee;
            transition: background-color 0.3s ease;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        td {
            color: #555;
        }

        .row-cost {
            font-weight: bold;
            color: #2c3e50;
        }

        @media (max-width: 600px) {
            table,
            th,
            td {
                display: block;
                width: 100%;
            }

            th,
            td {
                padding: 10px;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #ddd;
                border-radius: 5px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="container">
    <h1>Estimate Building Cost</h1>
    <form method="post" class="calculator-form">
        <div class="form-group">
            <label>Enter Area (sq. ft.):</label>
            <input type="number" step="any" name="area" 
                   value="<?php echo isset($_POST['area']) ? htmlspecialchars($_POST['area']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label>Select Building Type:</label>
            <select name="bhk" required>
                <option value="1" <?php if(isset($_POST['bhk']) && $_POST['bhk'] == "1") echo 'selected'; ?>>1 BHK</option>
                <option value="2" <?php if(isset($_POST['bhk']) && $_POST['bhk'] == "2") echo 'selected'; ?>>2 BHK</option>
                <option value="3" <?php if(isset($_POST['bhk']) && $_POST['bhk'] == "3") echo 'selected'; ?>>3 BHK</option>
            </select>
        </div>
        <div class="form-group">
            <label>Select District:</label>
            <select name="district_id" required>
                <option value="">-- Select District --</option>
                <?php
                $districts_query = "SELECT * FROM districts";
                $districts_result = $conn->query($districts_query);
                while($row = $districts_result->fetch_assoc()) {
                    $selected = (isset($_POST['district_id']) && $_POST['district_id'] == $row['district_id']) ? 'selected' : '';
                    echo "<option value='{$row['district_id']}' $selected>{$row['district_name']}</option>";
                }
                ?>
            </select>
        </div>

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
                        <th>Cost (₹)</th>
                      </tr>";

                // Define material images
                $material_images = [
                    'cement' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-ceme.png',
                    'steel' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-stee.png',
                    'bricks' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-bric.png',
                    'aggregate' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-aggr.png',
                    'sand' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-sand.png',
                    'flooring' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                    'windows' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-wind.png',
                    'doors' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-door.png',
                    'electrical fittings' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-elec.png',
                    'bathroom flooring' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                    'bathroom wall' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                    'ceiling' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                    'kitchen cabinets' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-door.png',
                    'kitchen countertops' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                    'kitchen flooring' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                    'kitchen wall tiles' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                    'roofing' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png'
                ];

                while ($row = $result->fetch_assoc()) {
                    $material_id = $row['material_id'];
                    $material_name = $row['material_name'];

                    // 1. Get available types & prices for this material
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
                    
                    // For Best Preference, mark only the first option (lowest priority) as selected
                    $first_option = true;
                    while ($type = $types_result->fetch_assoc()) {
                        $selected_value = "";
                        if (isset($_POST['best_preference']) && $first_option) {
                            $selected_value = "selected";
                            $first_option = false;
                        } else {
                            if (isset($_POST['selected_type'][$material_id]) && 
                                $_POST['selected_type'][$material_id] == $type['price']) {
                                $selected_value = "selected";
                            }
                        }
                        $options .= "<option value='{$type['price']}' $selected_value>{$type['material_type']}</option>";
                    }

                    // 2. Determine user-selected price
                    if (isset($_POST['best_preference']) && empty($_POST['selected_type'][$material_id])) {
                        // Re-run the query to get the best option price
                        $best_query = "SELECT price FROM material_prices 
                                       WHERE district_id = $district_id 
                                         AND material_id = $material_id 
                                       ORDER BY priority ASC LIMIT 1";
                        $best_result = $conn->query($best_query);
                        if ($best_result && $best_row = $best_result->fetch_assoc()) {
                            $selected_price = floatval($best_row['price']);
                        } else {
                            $selected_price = 0;
                        }
                    } else {
                        $selected_price = isset($_POST['selected_type'][$material_id])
                            ? floatval($_POST['selected_type'][$material_id])
                            : 0;
                    }

                    // 3. Get quantity: use user input if available, otherwise calculate from formula
                    if (isset($_POST['quantity'][$material_id]) && $_POST['quantity'][$material_id] !== '') {
                        $quantity = floatval($_POST['quantity'][$material_id]);
                    } else {
                        $quantity = getCategoryQuantity($material_name, $area);
                    }

                    // 4. Final cost = quantity × price × BHK
                    $cost = $quantity * $selected_price * $bhk;

                    // Save for chart
                    $breakdown[] = array(
                        'material_name' => $material_name,
                        'cost' => $cost
                    );

                    $material_key = strtolower($material_name);
                    $image_url = isset($material_images[$material_key]) ? $material_images[$material_key] : '';

                    echo "<tr>
                            <td>";
                    if ($image_url) {
                        echo "<img src='$image_url' alt='" . htmlspecialchars($material_name) . "' style='width: 30px; height: 30px; vertical-align: middle; margin-right: 10px;'>";
                    }
                    echo htmlspecialchars($material_name) . "</td>
                            <td>
                                <select name='selected_type[$material_id]'>
                                    $options
                                </select>
                            </td>
                            <td>
                                <input type='number' step='any' name='quantity[$material_id]' value='{$quantity}' style='width: 100px;' />
                            </td>
                            <td class='row-cost'>" . number_format($cost, 2) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No materials found for the selected district.</p>";
            }
        }

        // If "Calculate Estimation" pressed, sum total cost & display chart
        if (isset($_POST['calculate_estimation'])) {
            $total = 0;
            foreach ($breakdown as $item) {
                $total += $item['cost'];
            }
            echo "<h3>Total Estimated Cost: ₹ <span id='total_cost'>" . number_format($total, 2) . "</span></h3>";

            // Prepare data for the chart
            $labels = array();
            $costValues = array();
            foreach ($breakdown as $item) {
                if ($item['cost'] > 0) { // Only include non-zero costs
                    $labels[] = $item['material_name'];
                    $costValues[] = $item['cost'];
                }
            }
        }
        ?>

        <?php
        // Display buttons
        if (!isset($_POST['load_materials']) && !isset($_POST['best_preference']) && !isset($_POST['calculate_estimation'])) {
            echo "<input type='submit' name='load_materials' value='Load Materials'> ";
            echo "<input type='submit' name='best_preference' value='Best Preference'>";
        } else {
            echo "<input type='submit' name='calculate_estimation' value='Calculate Estimation'>";
        }
        ?>
    </form>
    <br>
    <a href="../main/emical/EmIcalc.html">EMI Calc Here</a>

    <?php if (isset($_POST['calculate_estimation']) && !empty($labels)): ?>
        <h3>Cost Breakdown by Category</h3>
        <div style="max-width: 800px; margin: 20px auto; width: 100%;">
            <canvas id="costBreakdownChart" width="800" height="400"></canvas>
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
                        title: { display: true, text: 'Total Estimated Cost (INR)' }
                    },
                    cutout: '50%'
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
<?php $conn->close(); ?>