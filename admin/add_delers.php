<?php
require_once 'db_connect.php';

// Check if user is admin
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header("Location: /Major-project/admin/login.php");
    exit();
}

// Pagination
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'dealer_id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Handle actions (delete, add, edit)
$message = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['dealer_id'])) {
        $dealer_id = (int)$_POST['dealer_id'];
        if ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM dealers WHERE dealer_id = ?");
            $stmt->execute([$dealer_id]);
            $message = "Dealer deleted successfully.";
        } elseif ($_POST['action'] === 'update') {
            $name = htmlspecialchars(trim($_POST['name']));
            $address = htmlspecialchars(trim($_POST['address']));
            $phone = htmlspecialchars(trim($_POST['phone']));
            $rating = floatval($_POST['rating']) > 5.0 ? 5.0 : floatval($_POST['rating']); // Cap rating at 5.0
            $stmt = $pdo->prepare("UPDATE dealers SET name = ?, address = ?, phone = ?, rating = ? WHERE dealer_id = ?");
            $stmt->execute([$name, $address, $phone, $rating, $dealer_id]);
            $message = "Dealer updated successfully.";
        }
    } elseif (isset($_POST['add_dealer'])) {
        $district_id = (int)$_POST['district_id'];
        $name = htmlspecialchars(trim($_POST['new_name']));
        $address = htmlspecialchars(trim($_POST['new_address']));
        $phone = htmlspecialchars(trim($_POST['new_phone']));
        $rating = floatval($_POST['new_rating']) > 5.0 ? 5.0 : floatval($_POST['new_rating']);
        $stmt = $pdo->prepare("INSERT INTO dealers (district_id, name, address, phone, rating) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$district_id, $name, $address, $phone, $rating]);
        $message = "Dealer added successfully.";
    }
}

// Validate sort and order
$allowed_sorts = ['dealer_id', 'name', 'address', 'rating', 'phone', 'created_at'];
$sort = in_array($sort, $allowed_sorts) ? $sort : 'dealer_id';
$allowed_orders = ['ASC', 'DESC'];
$order = in_array(strtoupper($order), $allowed_orders) ? $order : 'ASC';

// Count total dealers for pagination
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM dealers WHERE name LIKE ? OR address LIKE ? OR phone LIKE ?");
$count_stmt->execute(["%$search%", "%$search%", "%$search%"]);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Fetch dealers with pagination, search, and sorting
$stmt = $pdo->prepare("SELECT * FROM dealers WHERE name LIKE ? OR address LIKE ? OR phone LIKE ? ORDER BY $sort $order LIMIT ? OFFSET ?");
$stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(2, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(3, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(4, (int)$items_per_page, PDO::PARAM_INT);
$stmt->bindValue(5, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$dealers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch districts for the add/edit form
$districts_stmt = $pdo->query("SELECT district_id, district_name FROM districts");
$districts = $districts_stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dealers - Admin</title>
    <style>
        /* Reset default margins and padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            line-height: 1.6;
        }

        /* Navbar styling */
        .navbar {
            background-color: #2c3e50;
            padding: 15px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .navbar h2 {
            color: white;
            margin: 0;
        }

        .logout-link {
            color: #ecf0f1;
            text-decoration: none;
            font-weight: bold;
            padding: 8px 15px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .logout-link:hover {
            background-color: #34495e;
        }

        /* Container styling */
        .container {
            padding: 90px 20px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Search form styling */
        .search-form {
            margin-bottom: 20px;
        }

        input[type="text"] {
            padding: 8px;
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }

        input[type="submit"] {
            padding: 8px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        /* Table styling */
        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px 15px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        /* Links in table header */
        th a {
            color: #2c3e50;
            text-decoration: none;
        }

        th a:hover {
            color: #3498db;
        }

        /* Action buttons */
        .action-btn {
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: #3498db;
        }

        .edit-btn:hover {
            background-color: #2980b9;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        /* Add Dealer Form */
        .add-form {
            margin: 20px 0;
            background-color: white;
            padding: 20px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .add-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .add-form input,
        .add-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .add-form button {
            background-color: #2ecc71;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .add-form button:hover {
            background-color: #27ae60;
        }

        /* Edit Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border-radius: 4px;
            width: 80%;
            max-width: 500px;
            position: relative;
        }

        .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 24px;
            cursor: pointer;
        }

        .modal-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .modal-form input,
        .modal-form select {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .modal-form button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .modal-form button:hover {
            background-color: #2980b9;
        }

        /* Pagination styling */
        .container > a {
            display: inline-block;
            padding: 8px 12px;
            margin: 0 5px 10px 0;
            background-color: white;
            color: #3498db;
            text-decoration: none;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .container > a:hover {
            background-color: #3498db;
            color: white;
            border-color: #3498db;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 70px 10px 10px;
            }

            table {
                font-size: 14px;
            }

            th,
            td {
                padding: 8px;
            }

            input[type="text"] {
                width: 100%;
                margin-bottom: 10px;
            }

            .add-form {
                padding: 15px;
            }

            .modal-content {
                width: 90%;
                margin: 20% auto;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="../admin/menu.php" class="logout-link">MENU</a>
        <h2>Dealers Management</h2>
        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>

    <div class="container">
        <?php if ($message): ?>
            <p style="color: #2ecc71; margin-bottom: 10px;"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="GET" class="search-form">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by name, address, or phone">
            <input type="submit" value="Search">
        </form>

        <div class="add-form">
            <h3>Add New Dealer</h3>
            <form method="POST">
                <label for="district_id">District:</label>
                <select name="district_id" id="district_id" required>
                    <option value="">-- Select District --</option>
                    <?php foreach ($districts as $district): ?>
                        <option value="<?php echo $district['district_id']; ?>"><?php echo htmlspecialchars($district['district_name']); ?></option>
                    <?php endforeach; ?>
                </select>
                <label for="new_name">Name:</label>
                <input type="text" name="new_name" id="new_name" required>
                <label for="new_address">Address:</label>
                <input type="text" name="new_address" id="new_address" required>
                <label for="new_phone">Phone:</label>
                <input type="text" name="new_phone" id="new_phone" required>
                <label for="new_rating">Rating (0-5):</label>
                <input type="number" step="0.1" name="new_rating" id="new_rating" min="0" max="5" required>
                <button type="submit" name="add_dealer">Add Dealer</button>
            </form>
        </div>

        <table>
            <tr>
                <th><a href="?sort=dealer_id&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">ID</a></th>
                <th><a href="?sort=name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Name</a></th>
                <th><a href="?sort=address&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Address</a></th>
                <th><a href="?sort=rating&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Rating</a></th>
                <th><a href="?sort=phone&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Phone</a></th>
                <th><a href="?sort=created_at&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Created At</a></th>
                <th>Actions</th>
            </tr>
            <?php foreach ($dealers as $dealer): ?>
                <tr>
                    <td><?php echo htmlspecialchars($dealer['dealer_id']); ?></td>
                    <td><?php echo htmlspecialchars($dealer['name']); ?></td>
                    <td><?php echo htmlspecialchars($dealer['address']); ?></td>
                    <td><?php echo htmlspecialchars($dealer['rating'] ?? 'N/A'); ?></td>
                    <td><?php echo htmlspecialchars($dealer['phone']); ?></td>
                    <td><?php echo htmlspecialchars($dealer['created_at']); ?></td>
                    <td>
                        <button class="action-btn edit-btn" onclick="openEditModal(<?php echo $dealer['dealer_id']; ?>, '<?php echo addslashes($dealer['name']); ?>', '<?php echo addslashes($dealer['address']); ?>', '<?php echo addslashes($dealer['phone']); ?>', '<?php echo $dealer['rating'] ?? ''; ?>')">Edit</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            <input type="hidden" name="dealer_id" value="<?php echo $dealer['dealer_id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="action-btn delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php
        for ($i = 1; $i <= $total_pages; $i++) {
            echo "<a href='?page=$i&sort=$sort&order=$order&search=" . urlencode($search) . "'>$i</a> ";
        }
        ?>

        <!-- Edit Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h3>Edit Dealer</h3>
                <form method="POST" class="modal-form">
                    <input type="hidden" name="dealer_id" id="edit_dealer_id">
                    <label for="edit_name">Name:</label>
                    <input type="text" name="name" id="edit_name" required>
                    <label for="edit_address">Address:</label>
                    <input type="text" name="address" id="edit_address" required>
                    <label for="edit_phone">Phone:</label>
                    <input type="text" name="phone" id="edit_phone" required>
                    <label for="edit_rating">Rating (0-5):</label>
                    <input type="number" step="0.1" name="rating" id="edit_rating" min="0" max="5" required>
                    <button type="submit" name="action" value="update">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openEditModal(dealerId, name, address, phone, rating) {
            document.getElementById('edit_dealer_id').value = dealerId;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_phone').value = phone;
            document.getElementById('edit_rating').value = rating || 0;
            document.getElementById('editModal').style.display = 'block';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>