<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $district = $_POST["district"] ?? "Not selected";
    $location = $_POST["location"] ?? "Not selected";
    $area = $_POST["area"] ?? "0";
    $unit = $_POST["unit"] ?? "sqft";
    $residential = $_POST["residential"] ?? "Not selected";

    // Convert to square feet if unit is sqm
    if ($unit == "sqm") {
        $area = $area * 10.764;
    }

    $cost_per_sqft = 1000; // Example cost per square foot
    $total_cost = floatval($area) * $cost_per_sqft; // Ensure it's numeric

    // Material calculations
    $cement_required = floatval($area) * 0.4;
    $sand_required = floatval($area) * 0.816;
    $aggregate_required = floatval($area) * 0.608;
    $steel_required = floatval($area) * 4;
    $paint_required = floatval($area) * 0.18;
    $bricks_required = floatval($area) * 8;
    $flooring = floatval($area) * 1.3;

    // Cost calculations
    $cement_cost = (16.4 / 100) * $total_cost;
    $sand_cost = (12.3 / 100) * $total_cost;
    $aggregate_cost = (7.4 / 100) * $total_cost;
    $steel_cost = (24.6 / 100) * $total_cost;
    $finishing_cost = (16.5 / 100) * $total_cost;
    $fittings_cost = (22.8 / 100) * $total_cost;

    // Display in tabular format
    echo "<h2>Cost Estimation for " . number_format($area, 2) . " sqft</h2>";
    echo "<table border='1' style='border-collapse: collapse; width: 50%; text-align: center;'>
            <tr>
                <th>Material</th>
                <th>Quantity</th>
                <th>Price (Rs.)</th>
            </tr>
            <tr>
                <td>Cement</td>
                <td>" . number_format($cement_required, 2) . " bags</td>
                <td>" . number_format($cement_cost, 2) . "</td>
            </tr>
            <tr>
                <td>Sand</td>
                <td>" . number_format($sand_required, 2) . " tons</td>
                <td>" . number_format($sand_cost, 2) . "</td>
            </tr>
            <tr>
                <td>Aggregate</td>
                <td>" . number_format($aggregate_required, 2) . " tons</td>
                <td>" . number_format($aggregate_cost, 2) . "</td>
            </tr>
            <tr>
                <td>Steel</td>
                <td>" . number_format($steel_required, 2) . " kg</td>
                <td>" . number_format($steel_cost, 2) . "</td>
            </tr>
            <tr>
                <td>Paint</td>
                <td>" . number_format($paint_required, 2) . " liters</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Bricks</td>
                <td>" . number_format($bricks_required, 2) . " pieces</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Flooring</td>
                <td>" . number_format($flooring, 2) . " sqft</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Finishing</td>
                <td>-</td>
                <td>" . number_format($finishing_cost, 2) . "</td>
            </tr>
            <tr>
                <td>Fittings</td>
                <td>-</td>
                <td>" . number_format($fittings_cost, 2) . "</td>
            </tr>
            <tr style='font-weight: bold;'>
                <td colspan='2'>Total Cost</td>
                <td>" . number_format($total_cost, 2) . "</td>
            </tr>
        </table>";
}
?>
