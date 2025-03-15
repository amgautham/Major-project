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
     */
    $estimation_rates = [
        1 => [
            "cement" => 0.4,
            "sand" => 0.6,
            "aggregate" => 0.8,
            "bricks" => 6,
            "steel" => 2,
            "flooring" => 1.1,
            "doors" => 0.03,
            "windows" => 0.02,
            "electrical fittings" => 0.015
        ],
        2 => [
            "cement" => 0.45,
            "sand" => 0.65,
            "aggregate" => 0.9,
            "bricks" => 7,
            "steel" => 2.5,
            "flooring" => 1.15,
            "doors" => 0.035,
            "windows" => 0.025,
            "electrical fittings" => 0.02
        ],
        3 => [
            "cement" => 0.5,
            "sand" => 0.7,
            "aggregate" => 1.0,
            "bricks" => 8,
            "steel" => 3,
            "flooring" => 1.2,
            "doors" => 0.04,
            "windows" => 0.03,
            "electrical fittings" => 0.025
        ]
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
             * Three price ranges: Low, Medium, and High
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            outline: none;
        }

        button.next-button {
            background-color: #fdd835;
            color: black;
            font-weight: bold;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            transition: background-color 0.3s ease;
        }

        button.next-button:hover {
            background-color: #fbc02d;
        }

        /* New Table Styles */
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

        .material-row input[type="radio"] {
            margin: 0 5px 0 10px;
            vertical-align: middle;
        }

        .row-cost {
            font-weight: bold;
            color: #2c3e50;
        }

        /* Responsive Design */
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
    <script>
        function updateTotalCost() {
            let total = 0;
            document.querySelectorAll('.material-row').forEach(function(row) {
                let materialId = row.getAttribute('data-material-id');
                let selectedQuality = row.querySelector('input[name="quality_' + materialId + '"]:checked').value;
                let costInput = document.getElementById(selectedQuality + '_cost_' + materialId);
                let cost = parseFloat(costInput.value);
                row.querySelector('.row-cost').innerText = cost.toFixed(2);
                total += cost;
            });
            document.getElementById('total_cost').innerText = total.toFixed(2);
        }
    </script>
</head>

<body class="container">
    <h1>Estimate Building Cost</h1>
    <form method="post" class="calculator-form">
        <div class="form-group">
            <label>Enter Area (sq. ft.):</label>
            <input type="number" name="area" required>
        </div>

        <div class="form-group">
            <label>Select Building Type:</label>
            <select name="building_type" required>
                <option value="1">1BHK</option>
                <option value="2">2BHK</option>
                <option value="3">3BHK</option>
            </select>
        </div>

        <div class="form-group">
            <label>Select District:</label>
            <select name="district_id" required>
                <option value="">-- Select District --</option>
                <?php while ($row = $districts_result->fetch_assoc()): ?>
                    <option value="<?= $row['district_id']; ?>" <?= ($row['district_id'] == $district_id) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($row['district_name']); ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>

        <button type="submit" class="next-button">Get Estimation</button>
    </form>

    <?php if (!empty($materials)): ?>
        <h3>Estimation Result</h3>
        <table>
            <tr>
                <th>Material</th>
                <th>Quantity</th>
                <th>Quality</th>
                <th>Cost (₹)</th>
            </tr>
            <?php
            // Define image URLs for each material
            $material_images = [
                'cement' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-ceme.png',
                'steel' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-stee.png',
                'bricks' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-bric.png',
                'aggregate' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-aggr.png',
                'sand' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-sand.png',
                'flooring' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-floo.png',
                'windows' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-wind.png',
                'doors' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-door.png',
                'electrical fittings' => 'https://www.ultratechcement.com/content/dam/ultratechcement/cost-calculator/store-icon-elec.png'
            ];

            foreach ($materials as $mat):
                $material_key = strtolower($mat['material_name']);
                $image_url = isset($material_images[$material_key]) ? $material_images[$material_key] : '';
            ?>
                <tr class="material-row" data-material-id="<?= $mat['material_id']; ?>">
                    <td>
                        <?php if ($image_url): ?>
                            <img src="<?= $image_url ?>" alt="<?= htmlspecialchars($mat['material_name']); ?>" style="width: 30px; height: 30px; vertical-align: middle; margin-right: 10px;">
                        <?php endif; ?>
                        <?= htmlspecialchars($mat['material_name']); ?>
                    </td>
                    <td>
                        <?= isset($material_quantity[$material_key]) ? $material_quantity[$material_key] : 'N/A'; ?>
                    </td>
                    <td>
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" value="low" onclick="updateTotalCost()" checked> Low
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" value="medium" onclick="updateTotalCost()"> Medium
                        <input type="radio" name="quality_<?= $mat['material_id']; ?>" value="high" onclick="updateTotalCost()"> High
                    </td>
                    <td>
                        <span class="row-cost"><?= number_format($costs[$mat['material_id']]['low'], 2); ?></span>
                    </td>
                    <input type="hidden" id="low_cost_<?= $mat['material_id']; ?>" value="<?= $costs[$mat['material_id']]['low']; ?>">
                    <input type="hidden" id="medium_cost_<?= $mat['material_id']; ?>" value="<?= $costs[$mat['material_id']]['medium']; ?>">
                    <input type="hidden" id="high_cost_<?= $mat['material_id']; ?>" value="<?= $costs[$mat['material_id']]['high']; ?>">
                </tr>
            <?php endforeach; ?>
        </table>

        <h3>Total Estimated Cost: ₹ <span id="total_cost"><?= number_format($total_cost['low'], 2); ?></span></h3>

        <!-- Add Donut Chart -->
        <h3>Cost Breakdown by Category (%)</h3>
        <div style="max-width: 800px; margin: 20px auto; width: 100%;"> <!-- Increased max-width to 800px -->
            <canvas id="costBreakdownChart" width="800" height="400"></canvas> <!-- Explicit width and height -->
        </div>

        <!-- Include Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Function to update total cost and chart
            function updateTotalCostAndChart() {
                let total = 0;
                let costsByCategory = {
                    'Brickwork & Plastering': 0, // Bricks, Sand
                    'Flooring & Tiling': 0, // Flooring
                    'Electric Wiring': 0, // Electrical Fittings
                    'Water Supply & Plumbing': 0, // Placeholder (not directly mapped)
                    'Door': 0, // Doors
                    'Roof Slab': 0, // Steel
                    'RCC Work - Columns & Slabs': 0, // Cement, Aggregate
                    'Footing & Foundation': 0, // Placeholder (not directly mapped)
                    'Excavation': 0, // Placeholder (not directly mapped)
                    'Home Design & Approval': 0 // Placeholder (not directly mapped)
                };

                document.querySelectorAll('.material-row').forEach(function(row) {
                    let materialId = row.getAttribute('data-material-id');
                    let materialName = row.cells[0].textContent.trim().toLowerCase();
                    let selectedQuality = row.querySelector('input[name="quality_' + materialId + '"]:checked').value;
                    let costInput = document.getElementById(selectedQuality + '_cost_' + materialId);
                    let cost = parseFloat(costInput.value);
                    row.querySelector('.row-cost').innerText = cost.toFixed(2);
                    total += cost;

                    // Map materials to chart categories
                    if (materialName.includes('bricks') || materialName.includes('sand')) {
                        costsByCategory['Brickwork & Plastering'] += cost;
                    } else if (materialName.includes('flooring')) {
                        costsByCategory['Flooring & Tiling'] += cost;
                    } else if (materialName.includes('electrical fittings')) {
                        costsByCategory['Electric Wiring'] += cost;
                    } else if (materialName.includes('doors')) {
                        costsByCategory['Door'] += cost;
                    } else if (materialName.includes('steel')) {
                        costsByCategory['Roof Slab'] += cost;
                    } else if (materialName.includes('cement') || materialName.includes('aggregate')) {
                        costsByCategory['RCC Work - Columns & Slabs'] += cost;
                    }
                    // Note: Windows are not directly mapped to any category in the chart
                });

                document.getElementById('total_cost').innerText = total.toFixed(2);

                // Calculate percentages
                let percentages = [];
                let labels = [];
                for (let category in costsByCategory) {
                    if (costsByCategory[category] > 0) { // Only include categories with non-zero costs
                        let percentage = (costsByCategory[category] / total) * 100;
                        percentages.push(percentage.toFixed(2));
                        labels.push(category);
                    }
                }

                // Update or create the chart
                const ctx = document.getElementById('costBreakdownChart').getContext('2d');
                if (window.costChart) {
                    window.costChart.destroy(); // Destroy the existing chart if it exists
                }
                window.costChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: percentages,
                            backgroundColor: [
                                '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
                                '#9966FF', '#FF9F40', '#C9CBCF', '#8BC34A',
                                '#607D8B', '#F06292' // Colors matching the chart in the image
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false, // Allow custom size
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            title: {
                                display: true,
                                text: 'Total Estimated Cost (INR)'
                            }
                        },
                        cutout: '50%' // Makes it a donut chart
                    }
                });
            }

            // Initial call to set up the chart
            document.addEventListener('DOMContentLoaded', updateTotalCostAndChart);

            // Update the existing updateTotalCost function to also update the chart
            function updateTotalCost() {
                updateTotalCostAndChart();
            }
        </script>
    <?php endif; ?>
</body>

</html>
<?php $conn->close(); ?>