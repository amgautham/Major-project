
<?php
// estimator.php

// Create database connection
$conn = new mysqli("localhost", "root", "", "buildwise");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
        <input type="number" step="any" name="area" value="<?php echo isset($_POST['area']) ? htmlspecialchars($_POST['area']) : ''; ?>" required>
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
        // This block loads the materials and shows the cost per material.
        $breakdown = array();  // Array to hold material cost breakdown
        if (isset($_POST['load_materials']) || isset($_POST['calculate_estimation'])) {
            $district_id = intval($_POST['district_id']);
            $bhk = intval($_POST['bhk']);
            $area = floatval($_POST['area']);
            echo "<h3>Materials for Selected District</h3>";
            // Query distinct materials available in the chosen district
            $query = "SELECT DISTINCT m.material_id, m.material_name 
                      FROM materials m 
                      JOIN material_prices mp ON m.material_id = mp.material_id
                      WHERE mp.district_id = $district_id";
            $result = $conn->query($query);
            
            if ($result && $result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Material</th><th>Type</th><th>Cost (Area × Price × BHK)</th></tr>";
                while ($row = $result->fetch_assoc()) {
                    $material_id = $row['material_id'];
                    // Get available types and prices for this material & district
                    $types_query = "SELECT material_type, price 
                                    FROM material_prices 
                                    WHERE district_id = $district_id 
                                      AND material_id = $material_id";
                    $types_result = $conn->query($types_query);
                    $options = "<option value=''>Select Type</option>";
                    while ($type = $types_result->fetch_assoc()) {
                        // Retain previously selected type if available
                        $selected_value = "";
                        if (isset($_POST['selected_type'][$material_id]) && $_POST['selected_type'][$material_id] == $type['price']) {
                            $selected_value = "selected";
                        }
                        $options .= "<option value='{$type['price']}' $selected_value>{$type['material_type']}</option>";
                    }
                    // Calculate cost for this material based on selected type (if any)
                    $selected_price = isset($_POST['selected_type'][$material_id]) ? floatval($_POST['selected_type'][$material_id]) : 0;
                    $cost = $area * $selected_price * $bhk;
                    // Save the breakdown (using material name as label)
                    $breakdown[] = array('material_name' => $row['material_name'], 'cost' => $cost);
                    
                    echo "<tr>
                            <td>{$row['material_name']}</td>
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
        
        // If the Calculate Estimation button was pressed, calculate and display total cost.
        if (isset($_POST['calculate_estimation'])) {
            $bhk = intval($_POST['bhk']);
            $area = floatval($_POST['area']);
            $total = 0;
            if (isset($_POST['selected_type']) && is_array($_POST['selected_type'])) {
                foreach ($_POST['selected_type'] as $mat_id => $price) {
                    $price_val = floatval($price);
                    $total += ($area * $price_val * $bhk);
                }
            }
            echo "<h3>Total Estimated Cost: ₹" . number_format($total, 2) . "</h3>";
            
            // Prepare data for the Doughnut chart
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
        // Display the submit button based on the current step.
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
        // Prepare the data arrays from PHP
        var labels = <?php echo json_encode($labels); ?>;
        var costValues = <?php echo json_encode($costValues); ?>;
        
        const ctx = document.getElementById('costBreakdownChart').getContext('2d');
        if (window.costChart) {
            window.costChart.destroy(); // Destroy existing chart if present
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