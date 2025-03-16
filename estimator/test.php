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
 * Returns the estimated quantity of material required based on the material category and house area.
 *
 * NOTE for Admin (Civil Engineer):
 * If you need to adjust these formulas based on local construction guidelines, simply update the multipliers,
 * wastage factors, or any constant values in the switch cases below.
 *
 * For example, if the guideline for brick usage changes (currently assumed as 20 bricks per sq.ft),
 * modify that value accordingly. Also note that for bricks, since the price is per 1000 bricks,
 * the total brick count is divided by 1000 to yield the number of "thousands" required.
 *
 * @param string $category   The material category (e.g., "BATHROOM FLOORING", "BRICK", "DOORS", etc.)
 * @param float  $houseArea  The total house area (sq.ft) entered by the user.
 * @return int               The estimated quantity (rounded up) for that material.
 */
function getCategoryQuantity($category, $houseArea) {
    // Convert category to uppercase for consistent matching.
    $cat = strtoupper($category);

    switch ($cat) {
        // Bathroom Flooring:
        // - Bathrooms cover about 8% of the house area.
        // - Each tile covers 4 sq.ft (assumed tile size: 2ft x 2ft).
        // - Add 10% extra for wastage.
        case 'BATHROOM FLOORING':
            $bathFloorArea = $houseArea * 0.08;
            $tilesNeeded = ($bathFloorArea / 4.0) * 1.1;
            return ceil($tilesNeeded);

        // Bathroom Wall:
        // - Assume the wall area for bathrooms is roughly three times the bathroom floor area.
        // - Add 10% extra for wastage.
        case 'BATHROOM WALL':
            $bathWallArea = ($houseArea * 0.08) * 3;
            $tilesNeeded = $bathWallArea * 1.1;
            return ceil($tilesNeeded);

        // Ceiling:
        // - The ceiling area is approximately equal to the house area.
        case 'CEILING':
            return ceil($houseArea);

        // Flooring:
        // - Main flooring is assumed to cover the entire house area.
        case 'FLOORING':
            return ceil($houseArea);

        // Kitchen Cabinets:
        // - Assume kitchen cabinet work covers roughly 10% of the house area.
        case 'KITCHEN CABINETS':
            return ceil($houseArea * 0.10);

        // Kitchen Countertops:
        // - Estimate countertop area as 5% of the house area.
        case 'KITCHEN COUNTERTOPS':
            return ceil($houseArea * 0.05);

        // Kitchen Flooring:
        // - Assume kitchen floor area is about 7% of the house area.
        case 'KITCHEN FLOORING':
            return ceil($houseArea * 0.07);

        // Kitchen Wall Tiles:
        // - Assume the wall tiling area in the kitchen is 3 times the kitchen area (7% each),
        //   then add 10% extra for wastage.
        case 'KITCHEN WALL TILES':
            $kitchWallArea = ($houseArea * 0.07) * 3;
            return ceil($kitchWallArea * 1.1);

        // Roofing:
        // - Assume roofing requires 110% of the house area (to account for overhangs).
        case 'ROOFING':
            return ceil($houseArea * 1.1);

        // Stone:
        // - Estimate stone quantity as 10% of the house area.
        case 'STONE':
            return ceil($houseArea * 0.10);

        // Brick:
        // - Assume a guideline of 20 bricks per square foot.
        // - Since brick prices are quoted per 1000 bricks, we convert the total brick count into "thousands".
        //   Formula: total bricks = houseArea * 20; then, quantity (in thousands) = ceil(total_bricks / 1000)
        case 'BRICK':
            return ceil(($houseArea * 20) / 1000);

        // Wall:
        // - For wall construction (other than bricks), assume material required is 1.5 times the house area.
        case 'WALL':
            return 100; //ceil($houseArea * 1.5);

        // Doors:
        // - Estimate one door for every 1000 sq.ft of house area.
        case 'DOORS':
            return ceil($houseArea / 1000);

        // Window:
        // - Estimate two windows for every 1000 sq.ft of house area.
        case 'WINDOW':
            return ceil(($houseArea / 1000) * 2);

        // Plumbing:
        // - Assume one complete set of plumbing fixtures is required per house.
        case 'PLUMBING':
            return 1;

        // Bond (Mortar):
        // - Estimate the required mortar as 5% of the house area.
        case 'BOND':
            return ceil($houseArea * 0.05);

        // Kitchen Sink:
        // - Typically, one kitchen sink is sufficient.
        case 'KITCHEN SINK':
            return 1;

        // Kitchen Faucets:
        // - Assume one set of faucets is sufficient.
        case 'KITCHEN FAUCETS':
            return 1;

        // Default:
        // - If the category is unrecognized, return the house area as a fallback quantity.
        default:
            return 40; // ceil($houseArea);
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
            background-color: #ffffff; /* White background */
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); /* Softer shadow */
            text-align: center;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1E3A8A; /* Dark blue for headings */
            margin-bottom: 20px;
        }

        h3 {
            color: #1E3A8A; /* Dark blue */
            margin: 25px 0;
            font-size: 20px;
            font-weight: 600;
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
            color: #1E3A8A; /* Dark blue for labels */
            margin-bottom: 8px;
        }

        select,
        input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #A3CFFA; /* Light blue border */
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            background-color: #f9faff; /* Very light blue background for inputs */
            transition: border-color 0.3s ease;
        }

        select:focus,
        input[type="number"]:focus {
            border-color: #4A90E2; /* Medium blue on focus */
        }
        
        button.next-button, input[type="submit"], button.action-button {
            background-color: #E53E3E; /* Red button */
            color: #ffffff; /* White text */
            font-weight: bold;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 15px;
            margin-right: 10px;
            transition: background-color 0.3s ease;
        }

        button.next-button:hover, input[type="submit"]:hover, button.action-button:hover {
            background-color: #C53030; /* Darker red on hover */
        }

        .button-container {
            display: flex;
            justify-content: center;
            gap: 10px; /* Space between buttons */
            margin-top: 20px;
        }

        table {
            width: 100%;
            max-width: 900px;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: #ffffff; /* White table background */
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
            background-color: #4A90E2; /* Medium blue header */
            color: #ffffff; /* White text */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        tr {
            border-bottom: 1px solid #A3CFFA; /* Light blue border */
            transition: background-color 0.3s ease;
        }

        tr:hover {
            background-color: #E6F0FA; /* Very light blue on hover */
        }

        td {
            color: #1E3A8A; /* Dark blue text */
        }

        .row-cost {
            font-weight: bold;
            color: #1E3A8A; /* Dark blue */
        }

        a {
            color: #4A90E2; /* Medium blue for links */
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #1E3A8A; /* Darker blue on hover */
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
                border: 1px solid #A3CFFA; /* Light blue border */
                border-radius: 5px;
            }

            .button-container {
                flex-direction: column;
                gap: 15px;
            }

            button.action-button {
                width: 100%;
                max-width: 200px;
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
                /*$material_images = [
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
                ];*/

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
            echo "<div class='button-container'>";
            echo "<input type='submit' name='calculate_estimation' value='Calculate Estimation'>";
            echo "<button type='button' class='action-button' onclick='window.print()'>Print</button>";
            echo "</div>";
        }
        ?>
    </form>
    <div class="button-container">
        <a href="../main/emical/EmIcalc.html"><button type="button" class="action-button">EMI Calculator</button></a>
    </div>

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