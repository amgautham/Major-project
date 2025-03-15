<?php
require_once 'db_connect.php';
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to the login page if not set or not admin
    header("Location: /Major-project/admin/login.php");
    exit();
}
// Pagination
$items_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC';

// Handle delete
if (isset($_POST['delete'])) {
    $id = $_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
}

$allowed_sorts = ['id', 'username', 'user_type'];
$sort = in_array($sort, $allowed_sorts) ? $sort : 'id';
$allowed_orders = ['ASC', 'DESC'];
$order = in_array(strtoupper($order), $allowed_orders) ? $order : 'ASC';

$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username LIKE ?");
$count_stmt->execute(["%$search%"]);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Use numbered placeholders and bind parameters explicitly
$stmt = $pdo->prepare("SELECT * FROM users WHERE username LIKE ? ORDER BY $sort $order LIMIT ? OFFSET ?");
$stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(2, (int)$items_per_page, PDO::PARAM_INT);
$stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Users - Admin</title>

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
        form {
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

        /* Action buttons/links */
        td a {
            color: #3498db;
            text-decoration: none;
            margin-right: 10px;
        }

        td a:hover {
            text-decoration: underline;
        }

        input[name="delete"] {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[name="delete"]:hover {
            background-color: #c0392b;
        }

        /* Pagination styling */
        .container>a {
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

        .container>a:hover {
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
        }
    </style>
</head>

<body>
    <div class="navbar">
        <h2>Users Management</h2>
        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>

    <div class="container">
        <form method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>

        <table>
            <tr>
                <th><a href="?sort=id&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">ID</a></th>
                <th><a href="?sort=username&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Username</a></th>
                <th><a href="?sort=user_type&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">User Type</a></th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['user_type']); ?></td>
                    <td>
                        
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <input type="submit" name="delete" value="Delete" onclick="return confirm('Are you sure?');">
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
    </div>
</body>

</html>