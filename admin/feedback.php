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
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'status'; // Default sort by status
$order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; // Default order ASC (unread first)

// Handle actions (mark as read/unread or delete)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action']) && isset($_POST['message_id'])) {
        $message_id = (int)$_POST['message_id'];
        if ($_POST['action'] === 'toggle_status') {
            // Get current status
            $stmt = $pdo->prepare("SELECT status FROM contact_messages WHERE message_id = ?");
            $stmt->execute([$message_id]);
            $current_status = $stmt->fetchColumn();
            $new_status = ($current_status === 'read') ? 'unread' : 'read';
            
            // Update status
            $stmt = $pdo->prepare("UPDATE contact_messages SET status = ? WHERE message_id = ?");
            $stmt->execute([$new_status, $message_id]);
        } elseif ($_POST['action'] === 'delete') {
            $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE message_id = ?");
            $stmt->execute([$message_id]);
        }
    }
}

// Validate sort and order
$allowed_sorts = ['message_id', 'name', 'email', 'status', 'submitted_at'];
$sort = in_array($sort, $allowed_sorts) ? $sort : 'status'; // Default to status if invalid
$allowed_orders = ['ASC', 'DESC'];
$order = in_array(strtoupper($order), $allowed_orders) ? $order : 'ASC';

// Count total messages for pagination
$count_stmt = $pdo->prepare("SELECT COUNT(*) FROM contact_messages WHERE name LIKE ? OR email LIKE ?");
$count_stmt->execute(["%$search%", "%$search%"]);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);

// Fetch messages with pagination, search, and sorting
// Default sorting: unread first (status ASC), then by submitted_at DESC
$default_order = "ORDER BY status ASC, submitted_at DESC";
if ($sort !== 'status' || $order !== 'ASC') {
    $order_by = "$sort $order";
} else {
    $order_by = $default_order;
}
$stmt = $pdo->prepare("SELECT * FROM contact_messages WHERE name LIKE ? OR email LIKE ? $order_by LIMIT ? OFFSET ?");
$stmt->bindValue(1, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(2, "%$search%", PDO::PARAM_STR);
$stmt->bindValue(3, (int)$items_per_page, PDO::PARAM_INT);
$stmt->bindValue(4, (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Messages - Admin</title>
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
        td button {
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-right: 5px;
        }

        .status-btn {
            background-color: #3498db;
        }

        .status-btn:hover {
            background-color: #2980b9;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
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
        <a href="../admin/menu.php" class="logout-link">MENU</a>
        <h2>Messages Management</h2>
        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>

    <div class="container">
        <form method="GET">
            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <input type="submit" value="Search">
        </form>

        <table>
            <tr>
                <th><a href="?sort=message_id&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">ID</a></th>
                <th><a href="?sort=name&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Name</a></th>
                <th><a href="?sort=email&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Email</a></th>
                <th>Message</th>
                <th><a href="?sort=status&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Status</a></th>
                <th><a href="?sort=submitted_at&order=<?php echo $order === 'ASC' ? 'DESC' : 'ASC'; ?>">Submitted At</a></th>
                <th>Actions</th>
            </tr>
            <?php foreach ($messages as $message): ?>
                <tr>
                    <td><?php echo htmlspecialchars($message['message_id']); ?></td>
                    <td><?php echo htmlspecialchars($message['name']); ?></td>
                    <td><?php echo htmlspecialchars($message['email']); ?></td>
                    <td><?php echo htmlspecialchars($message['message']); ?></td>
                    <td><?php echo htmlspecialchars($message['status']); ?></td>
                    <td><?php echo htmlspecialchars($message['submitted_at']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>">
                            <input type="hidden" name="action" value="toggle_status">
                            <button type="submit" class="status-btn"><?php echo $message['status'] === 'read' ? 'Mark Unread' : 'Mark Read'; ?></button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="message_id" value="<?php echo $message['message_id']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="delete-btn" onclick="return confirm('Are you sure?');">Delete</button>
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