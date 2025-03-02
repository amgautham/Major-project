<?php
$conn = new mysqli("localhost", "root", "", "low_cost_housing");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all materials and their details
$query = "SELECT * FROM material_prices";  // Adjust the table name if needed
$result = $conn->query($query);
$materials = [];

while ($row = $result->fetch_assoc()) {
    $materials[$row['resource']][] = $row;
}

$conn->close();
?>

<div class="cost-table">
    <h2>Cost by Resource Allocation</h2>
    <table>
        <thead>
            <tr>
                <th>Resource</th>
                <th>Quantity</th>
                <th>Quality</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $resource => $qualities): ?>
                <tr>
                    <td><?php echo $resource; ?></td>
                    <td><?php echo $qualities[0]['quantity']; ?></td> 
                    <td>
                        <?php foreach ($qualities as $quality): ?>
                            <input type="radio" name="<?php echo strtolower($resource); ?>" 
                                value="<?php echo $quality['quality']; ?>" 
                                onchange="updatePrice('<?php echo $resource; ?>', this.value)" 
                                <?php echo ($quality['is_default'] == 1) ? 'checked' : ''; ?>>
                            <?php echo $quality['quality']; ?>
                        <?php endforeach; ?>
                    </td>
                    <td id="<?php echo strtolower($resource); ?>-price">
                        ₹<?php echo $qualities[0]['price']; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3"><strong>Total Amount</strong></td>
                <td id="total-amount">₹0</td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function updatePrice(resource, quality) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "update_price.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            let response = JSON.parse(xhr.responseText);
            document.getElementById(resource.toLowerCase() + "-price").innerHTML = "₹" + response.new_price;
            document.getElementById("total-amount").innerHTML = "₹" + response.total_price;
        }
    };
    xhr.send("resource=" + resource + "&quality=" + quality);
}
</script>
