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
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'material_id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Handle delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM materials WHERE material_id = ?");
    $stmt->execute([$id]);
}

// Handle add
if (isset($_POST['add'])) {
    $material_name = $_POST['material_name'];
    $stmt = $pdo->prepare("INSERT INTO materials (material_name) VALUES (?)");
    $stmt->execute([$material_name]);
}

// Handle edit
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $material_name = $_POST['material_name'];
    $stmt = $pdo->prepare("UPDATE materials SET material_name = ? WHERE material_id = ?");
    $stmt->execute([$material_name, $id]);
}

$allowed_sorts = ['material_id', 'material_name'];
$sort = in_array($sort, $allowed_sorts) ? $sort : 'material_id';

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM materials WHERE material_name LIKE ?");
$count_stmt->execute(["%$search%"]);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

$stmt = $pdo->prepare("SELECT * FROM materials WHERE material_name LIKE ? ORDER BY $sort $order LIMIT ? OFFSET ?");
$stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(2, (int)$items_per_page, PDO::PARAM_INT);
$stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$materials = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Materials - Admin</title>
    <style>
        /* Existing CSS remains the same with additions below */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; color: #333; line-height: 1.6; }
        .navbar { background-color: #2c3e50; padding: 15px 20px; position: fixed; top: 0; width: 100%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .navbar h2 { color: white; margin: 0; }
        .logout-link { color: #ecf0f1; text-decoration: none; font-weight: bold; padding: 8px 15px; border-radius: 4px; transition: background-color 0.3s; }
        .logout-link:hover { background-color: #34495e; }
        .container { padding: 90px 20px 20px; max-width: 1200px; margin: 0 auto; }
        form { margin-bottom: 20px; }
        input[type="text"] { padding: 8px; width: 200px; border: 1px solid #ddd; border-radius: 4px; margin-right: 10px; }
        input[type="submit"] { padding: 8px 15px; background-color: #3498db; color: white; border: none; border-radius: 4px; cursor: pointer; transition: background-color 0.3s; }
        input[type="submit"]:hover { background-color: #2980b9; }
        table { border-collapse: collapse; width: 100%; margin: 20px 0; background-color: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { border: 1px solid #ddd; padding: 12px 15px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; text-transform: uppercase; font-size: 14px; }
        tr:nth-child(even) { background-color: #fafafa; }
        tr:hover { background-color: #f5f5f5; }
        th a { color: #2c3e50; text-decoration: none; }
        th a:hover { color: #3498db; }
        td a { color: #3498db; text-decoration: none; margin-right: 10px; }
        td a:hover { text-decoration: underline; }
        input[name="delete"] { background-color: #e74c3c; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; transition: background-color 0.3s; }
        input[name="delete"]:hover { background-color: #c0392b; }
        .container > a { display: inline-block; padding: 8px 12px; margin: 0 5px 10px 0; background-color: white; color: #3498db; text-decoration: none; border: 1px solid #ddd; border-radius: 4px; transition: all 0.3s; }
        .container > a:hover { background-color: #3498db; color: white; border-color: #3498db; }

        /* New styles for add/edit functionality */
        .add-form, .edit-modal { margin-bottom: 20px; padding: 15px; background-color: white; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .edit-modal { display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; width: 90%; max-width: 400px; }
        .edit-modal.active { display: block; }
        .overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999; }
        .overlay.active { display: block; }
        .edit-modal input[type="text"] { width: 100%; margin-bottom: 10px; }
        .close-btn { float: right; color: #e74c3c; cursor: pointer; font-weight: bold; }

        @media (max-width: 768px) {
            .container { padding: 70px 10px 10px; }
            table { font-size: 14px; }
            th, td { padding: 8px; }
            input[type="text"] { width: 100%; margin-bottom: 10px; }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Materials Management</h2>
        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>

    <div class="container">
        <!-- Add Material Form -->
        <div class="add-form">
            <h3>Add New Material</h3>
            <form method="POST">
                <input type="text" name="material_name" placeholder="Material Name" required>
                <input type="submit" name="add" value="Add Material">
            </form>
        </div>

        <!-- Search Form -->
        <form method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>

        <!-- Materials Table -->
        <table>
            <tr>
                <th><a href="?sort=material_id&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">ID</a></th>
                <th><a href="?sort=material_name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Name</a></th>
                <th>Actions</th>
            </tr>
            <?php foreach ($materials as $material): ?>
                <tr>
                    <td><?php echo $material['material_id']; ?></td>
                    <td><?php echo htmlspecialchars($material['material_name']); ?></td>
                    <td>
                        <a href="#" class="edit-btn" data-id="<?php echo $material['material_id']; ?>" data-name="<?php echo htmlspecialchars($material['material_name']); ?>">Edit</a>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $material['material_id']; ?>">
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
            <h3>Edit Material</h3>
            <form method="POST">
                <input type="hidden" name="id" id="edit-id">
                <input type="text" name="material_name" id="edit-name" required>
                <input type="submit" name="edit" value="Save Changes">
            </form>
        </div>
    </div>

    <script>
        // JavaScript for edit functionality
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-name').value = name;
                
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