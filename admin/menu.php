<?php
session_start(); // Start the session

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    // Redirect to the login page if not set or not admin
    header("Location: /Major-project/admin/login.php");
    exit();
}

// Database connection (assuming db_connect.php is used elsewhere; we'll use a direct PDO connection here for simplicity)
$host = 'localhost';
$dbname = 'buildwise';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Count unread messages
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'");
    $stmt->execute();
    $unread_count = $stmt->fetchColumn();
} catch (PDOException $e) {
    // Handle error (log it or display a generic message in production)
    $unread_count = 0; // Default to 0 if query fails
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/x-icon" href="favicon.jfif">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        /* Reset default styles */
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
            background-color: #06134b; /* Dark blue */
            padding: 15px 20px;
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: all 0.5s ease;
        }

        .navbar h2 {
            color: #fff;
            margin: 0;
            font-size: 20px;
        }

        .logout-link {
            color: #fff;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            background-color: #00008B; /* Darker blue for logout */
            transition: background-color 0.3s;
            font-weight: bold;
            font-size: 14px;
        }

        .logout-link:hover {
            background-color: #00FFFF; /* Cyan on hover */
        }

        /* Hamburger menu (if needed in future) */
        .buttoncontainer {
            display: inline-block;
            cursor: pointer;
            float: right;
        }

        .bar1, .bar2, .bar3 {
            width: 35px;
            height: 5px;
            background-color: #e8e8e8;
            margin: 8px 10px;
            transition: 0.4s;
        }

        .change .bar1 {
            transform: translate(0, 13px) rotate(-45deg);
        }

        .change .bar2 {
            opacity: 0;
        }

        .change .bar3 {
            transform: translate(0, -13px) rotate(45deg);
        }

        /* Container styling */
        .container {
            padding-top: 70px; /* Space for fixed navbar */
            max-width: 1200px;
            margin: 0 auto;
            padding: 70px 20px 20px;
        }

        /* Tile container */
        .tile-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px 0;
        }

        /* Tile styling */
        .tile {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative; /* For notification badge positioning */
        }

        .tile:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            background-color: #3498db; /* Blue on hover */
            color: white;
        }

        .tile h2 {
            font-size: 18px;
            margin: 0;
            color: #2c3e50; /* Dark blue */
            transition: color 0.3s;
            position: relative; /* Ensure text stays above badge */
            z-index: 1; /* Keep text above badge */
        }

        .tile:hover h2 {
            color: white;
        }

        /* Notification badge */
        .notification-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #e74c3c; /* Red for unread notifications */
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            display: <?php echo $unread_count > 0 ? 'flex' : 'none'; ?>;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .navbar {
                padding: 10px 15px;
            }

            .navbar h2 {
                font-size: 18px;
            }

            .logout-link {
                padding: 8px 12px;
                font-size: 12px;
            }

            .container {
                padding: 60px 10px 10px;
            }

            .tile-container {
                grid-template-columns: 1fr;
            }

            .tile {
                padding: 15px;
            }

            .tile h2 {
                font-size: 16px;
            }

            .notification-badge {
                top: 5px;
                right: 5px;
                width: 18px;
                height: 18px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Welcome Admin</h2>
        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>
    
    <div class="container">
        <div class="tile-container">
            <div class="tile" onclick="navigateTo('../admin/users.php')">
                <h2>Users</h2>
            </div>
            <div class="tile" onclick="navigateTo('../admin/districts.php')">
                <h2>Districts</h2>
            </div>
            <div class="tile" onclick="navigateTo('../admin/materials.php')">
                <h2>Materials</h2>
            </div>
            <div class="tile" onclick="navigateTo('../admin/material_prices.php')">
                <h2>Material Prices</h2>
            </div>
            <div class="tile" onclick="navigateTo('../admin/feedback.php')">
                <h2>FeedBacks</h2>
                <?php if ($unread_count > 0): ?>
                    <div class="notification-badge"><?php echo $unread_count; ?></div>
                <?php endif; ?>
            </div>
            <div class="tile" onclick="navigateTo('../admin/add_delers.php')">
                <h2>Add Dealers</h2>
            </div>
        </div> 
    </div>

    <script>
        function navigateTo(url) {
            window.location.href = url;
        }
        // Hamburger menu function (not used currently but kept for future)
        function myFunction(x) {
            x.classList.toggle("change");
        }
    </script>
</body>
</html>