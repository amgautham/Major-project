<?php
require_once 'db_connect.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to the login page if not set or not admin
    header("Location: /Major-project/admin/login.php");
    exit();
}
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'district_id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Handle delete
if (isset($_POST['delete'])) {
    $district_id = $_POST['district_id'];
    $material_id = $_POST['material_id'];
    $stmt = $pdo->prepare("DELETE FROM material_prices WHERE district_id = ? AND material_id = ?");
    $stmt->execute([$district_id, $material_id]);
}

// Handle add
if (isset($_POST['add'])) {
    $district_id = $_POST['district_id'];
    $material_id = $_POST['material_id'];
    $low_price = (float)$_POST['low_price'];
    $medium_price = (float)$_POST['medium_price'];
    $high_price = (float)$_POST['high_price'];
    
    $stmt = $pdo->prepare("INSERT INTO material_prices (district_id, material_id, low_price, medium_price, high_price, last_updated) 
                          VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP)");
    $stmt->execute([$district_id, $material_id, $low_price, $medium_price, $high_price]);
}

// Handle edit
if (isset($_POST['edit'])) {
    $district_id = $_POST['district_id'];
    $material_id = $_POST['material_id'];
    $low_price = (float)$_POST['low_price'];
    $medium_price = (float)$_POST['medium_price'];
    $high_price = (float)$_POST['high_price'];
    
    $stmt = $pdo->prepare("UPDATE material_prices SET low_price = ?, medium_price = ?, high_price = ?, last_updated = CURRENT_TIMESTAMP 
                          WHERE district_id = ? AND material_id = ?");
    $stmt->execute([$low_price, $medium_price, $high_price, $district_id, $material_id]);
}

// Fetch districts and materials for dropdowns
$districts_stmt = $pdo->query("SELECT district_id, district_name FROM districts");
$districts = $districts_stmt->fetchAll(PDO::FETCH_ASSOC);

$materials_stmt = $pdo->query("SELECT material_id, material_name FROM materials");
$materials = $materials_stmt->fetchAll(PDO::FETCH_ASSOC);

$allowed_sorts = ['district_id', 'material_id', 'low_price', 'medium_price', 'high_price', 'last_updated'];
$sort = in_array($sort, $allowed_sorts) ? $sort : 'district_id';

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM material_prices mp 
                           JOIN materials m ON mp.material_id = m.material_id 
                           JOIN districts d ON mp.district_id = d.district_id 
                           WHERE m.material_name LIKE ? OR d.district_name LIKE ?");
$count_stmt->execute(["%$search%", "%$search%"]);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

$stmt = $pdo->prepare("SELECT mp.*, m.material_name, d.district_name 
                      FROM material_prices mp
                      JOIN materials m ON mp.material_id = m.material_id
                      JOIN districts d ON mp.district_id = d.district_id
                      WHERE m.material_name LIKE ? OR d.district_name LIKE ?
                      ORDER BY $sort $order LIMIT ? OFFSET ?");
$stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(2, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(3, (int)$items_per_page, PDO::PARAM_INT);
$stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$prices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Material Prices - Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; color: #333; line-height: 1.6; }
        .navbar { background-color: #2c3e50; padding: 15px 20px; position: fixed; top: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar h2 { color: white; margin: 0; }
        .logout-link { color: #ecf0f1; text-decoration: none; font-weight: bold; padding: 8px 15px; border-radius: 4px; transition: background-color 0.3s; }
        .logout-link:hover { background-color: #34495e; }
        .container { padding: 90px 20px 20px; max-width: 1200px; margin: 0 auto; }
        form { margin-bottom: 20px; }
        input[type="text"], input[type="number"], select { padding: 8px; width: 200px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px; margin-bottom: 10px; }
        input[type="submit"] { padding: 8px 15px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s; }
        input[type="submit"]:hover { background-color: #2980b9; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px 15px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 14px; white-space: nowrap; }
        tr:nth-child(even) { background-color: #fafafa; }
        tr:hover { background-color: #f5f5f5; }
        th a { color: #2c3e50; text-decoration: none; }
        th a:hover { color: #3498db; }
        td a { color: #3498db; text-decoration: none; margin-right: 10px; }
        td a:hover { text-decoration: underline; }
        td:nth-child(3), td:nth-child(4), td:nth-child(5) { text-align: right; font-family: monospace; }
        .container > a { display: inline-block; padding: 8px 12px; margin: 0 5px 10px 0; background-color: white; color: #3498db; text-decoration: none; border: 1px solid #ddd; border-radius: 4px; transition: all 0.3s; }
        .container > a:hover { background-color: #3498db; color: white; border-color: #3498db; }
        
        /* New styles for add/edit */
        .add-form, .edit-modal { margin-bottom: 20px; padding: 15px; background-color: white; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .edit-modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; width: 90%; max-width: 400px; }
        .edit-modal.active { display: block; }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
        .overlay.active { display: block; }
        .close-btn { float: right; color: #e74c3c; cursor: pointer; font-weight: bold; }
        input[name="delete"] { background-color: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; transition: background-color 0.3s; }
        input[name="delete"]:hover { background-color: #c0392b; }

        @media (max-width: 768px) {
            .container { padding: 70px 10px 10px; }
            table { font-size: 14px; }
            th, td { padding: 8px; }
            input[type="text"], input[type="number"], select { width: 100%; }
            .container table { display: block; overflow-x: auto; white-space: nowrap; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="../admin/menu.php" class="logout-link">MENU</a>
        <h2>Material Prices Management</h2>
        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>

    <div class="container">
        <!-- Add Price Form -->
        <div class="add-form">
            <h3>Add New Price</h3>
            <form method="POST">
                <select name="district_id" required>
                    <option value="">-- Select District --</option>
                    <?php foreach ($districts as $district): ?>
                        <option value="<?php echo $district['district_id']; ?>"><?php echo htmlspecialchars($district['district_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <select name="material_id" required>
                    <option value="">-- Select Material --</option>
                    <?php foreach ($materials as $material): ?>
                        <option value="<?php echo $material['material_id']; ?>"><?php echo htmlspecialchars($material['material_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="number" step="0.01" name="low_price" placeholder="Low Price" required>
                <input type="number" step="0.01" name="medium_price" placeholder="Medium Price" required>
                <input type="number" step="0.01" name="high_price" placeholder="High Price" required>
                <input type="submit" name="add" value="Add Price">
            </form>
        </div>

        <!-- Search Form -->
        <form method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>

        <!-- Prices Table -->
        <table>
            <tr>
                <th><a href="?sort=district_id&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">District</a></th>
                <th><a href="?sort=material_id&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Material</a></th>
                <th><a href="?sort=low_price&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Low Price</a></th>
                <th><a href="?sort=medium_price&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Medium Price</a></th>
                <th><a href="?sort=high_price&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">High Price</a></th>
                <th><a href="?sort=last_updated&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Last Updated</a></th>
                <th>Actions</th>
            </tr>
            <?php foreach ($prices as $price): ?>
                <tr>
                    <td><?php echo htmlspecialchars($price['district_name']); ?></td>
                    <td><?php echo htmlspecialchars($price['material_name']); ?></td>
                    <td><?php echo number_format($price['low_price'], 2); ?></td>
                    <td><?php echo number_format($price['medium_price'], 2); ?></td>
                    <td><?php echo number_format($price['high_price'], 2); ?></td>
                    <td><?php echo $price['last_updated']; ?></td>
                    <td>
                        <a href="#" class="edit-btn" 
                           data-district-id="<?php echo $price['district_id']; ?>" 
                           data-material-id="<?php echo $price['material_id']; ?>" 
                           data-low-price="<?php echo $price['low_price']; ?>" 
                           data-medium-price="<?php echo $price['medium_price']; ?>" 
                           data-high-price="<?php echo $price['high_price']; ?>">Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="district_id" value="<?php echo $price['district_id']; ?>">
                            <input type="hidden" name="material_id" value="<?php echo $price['material_id']; ?>">
                            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- Pagination -->
        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=$i&sort=$sort&order=$order&search=" . urlencode($search) . "'>$i</a> ";
        }
        ?>

        <!-- Edit Modal -->
        <div class="overlay"></div>
        <div class="edit-modal">
            <span class="close-btn">X</span>
            <h3>Edit Material Price</h3>
            <form method="POST">
                <input type="hidden" name="district_id" id="edit-district-id">
                <input type="hidden" name="material_id" id="edit-material-id">
                <input type="number" step="0.01" name="low_price" id="edit-low-price" required>
                <input type="number" step="0.01" name="medium_price" id="edit-medium-price" required>
                <input type="number" step="0.01" name="high_price" id="edit-high-price" required>
                <input type="submit" name="edit" value="Save Changes">
            </form>
        </div>
    </div>

    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const districtId = this.getAttribute('data-district-id');
                const materialId = this.getAttribute('data-material-id');
                const lowPrice = this.getAttribute('data-low-price');
                const mediumPrice = this.getAttribute('data-medium-price');
                const highPrice = this.getAttribute('data-high-price');

                document.getElementById('edit-district-id').value = districtId;
                document.getElementById('edit-material-id').value = materialId;
                document.getElementById('edit-low-price').value = lowPrice;
                document.getElementById('edit-medium-price').value = mediumPrice;
                document.getElementById('edit-high-price').value = highPrice;

                document.querySelector('.edit-modal').classList.add('active');
                document.querySelector('.overlay').classList.add('active');
            });
        });

        document.querySelector('.close-btn').addEventListener('click', function() {
            document.querySelector('.edit-modal').classList.remove('active');
            document.querySelector('.overlay').classList.remove('active');
        });

        document.querySelector('.overlay').addEventListener('click', function() {
            document.querySelector('.edit-modal').classList.remove('active');
            this.classList.remove('active');
        });
    </script>
</body>
</html>