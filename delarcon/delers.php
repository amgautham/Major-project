<?php
session_start(); // Start the session

// Database connection
$conn = new mysqli("localhost", "root", "", "buildwise");

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    // Redirect to the login page
    header("Location: /Major-project/login/login.php");
    exit();
  }

// Fetch dealers with their district names
$query = "SELECT d.*, dist.district_name 
          FROM dealers d 
          JOIN districts dist ON d.district_id = dist.district_id 
          ORDER BY dist.district_name, d.name";
$result = $conn->query($query);

// Group dealers by district
$dealers_by_district = [];
while ($row = $result->fetch_assoc()) {
    $district_name = $row['district_name'];
    if (!isset($dealers_by_district[$district_name])) {
        $dealers_by_district[$district_name] = [];
    }
    $dealers_by_district[$district_name][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Dealers</title>
    <style>
        .container {
            max-width: 1000px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            text-align: center;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        h1 {
            font-size: 28px;
            font-weight: 700;
            color: #1E3A8A;
            margin-bottom: 20px;
        }

        .district-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .district-section h2 {
            color: #1E3A8A;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            text-align: left;
            border-bottom: 2px solid #A3CFFA;
            padding-bottom: 5px;
        }

        .dealer-list {
            width: 100%;
        }

        .dealer-item {
            background-color: #f9faff;
            padding: 15px;
            border: 1px solid #A3CFFA;
            border-radius: 6px;
            margin-bottom: 10px;
            text-align: left;
            transition: background-color 0.3s ease;
        }

        .dealer-item:hover {
            background-color: #E6F0FA;
        }

        .dealer-item h3 {
            color: #1E3A8A;
            margin: 0 0 10px 0;
            font-size: 18px;
        }

        .dealer-item p {
            color: #1E3A8A;
            margin: 5px 0;
            font-size: 14px;
        }

        a.logout-link {
            color: #4A90E2;
            text-decoration: none;
            font-weight: 500;
            margin-top: 20px;
            display: inline-block;
        }

        a.logout-link:hover {
            color: #1E3A8A;
        }

        @media (max-width: 600px) {
            .container {
                margin: 10px;
                padding: 20px;
            }

            .district-section h2 {
                font-size: 18px;
            }

            .dealer-item {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>View Dealers</h1>

        <?php if (empty($dealers_by_district)): ?>
            <p>No dealers found.</p>
        <?php else: ?>
            <?php foreach ($dealers_by_district as $district_name => $dealers): ?>
                <div class="district-section">
                    <h2><?php echo htmlspecialchars($district_name); ?> Dealers</h2>
                    <div class="dealer-list">
                        <?php foreach ($dealers as $dealer): ?>
                            <div class="dealer-item">
                                <h3><?php echo htmlspecialchars($dealer['name']); ?></h3>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($dealer['address']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($dealer['phone']); ?></p>
                                <p><strong>Rating:</strong> <?php echo htmlspecialchars($dealer['rating'] ?? 'N/A'); ?>/5</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>