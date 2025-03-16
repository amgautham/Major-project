<?php
session_start(); // Start the session

// Database connection
$conn = new mysqli("localhost", "root", "", "buildwise");

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: /Major-project/login/login.php"); // Redirect to login if not logged in
    exit();
}

// Fetch all districts for the dropdown
$districts_query = "SELECT * FROM districts ORDER BY district_name";
$districts_result = $conn->query($districts_query);
$districts = $districts_result->fetch_all(MYSQLI_ASSOC);

// Determine the selected district (default to none)
$selected_district_id = isset($_GET['district_id']) && $_GET['district_id'] !== '' ? (int)$_GET['district_id'] : null;
$selected_district_name = null;
$dealers = [];

// Fetch dealers if a district is selected
if ($selected_district_id) {
    $stmt = $conn->prepare("SELECT d.*, dist.district_name 
                           FROM dealers d 
                           JOIN districts dist ON d.district_id = dist.district_id 
                           WHERE d.district_id = ? 
                           ORDER BY d.name");
    $stmt->bind_param("i", $selected_district_id);
    $stmt->execute();
    $dealers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Get the selected district name
    foreach ($districts as $district) {
        if ($district['district_id'] == $selected_district_id) {
            $selected_district_name = $district['district_name'];
            break;
        }
    }
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

        .filter-form {
            margin-bottom: 20px;
            width: 100%;
            max-width: 300px;
            text-align: left;
        }

        label {
            font-weight: bold;
            font-size: 14px;
            display: block;
            color: #1E3A8A;
            margin-bottom: 8px;
        }

        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #A3CFFA;
            border-radius: 6px;
            font-size: 14px;
            outline: none;
            background-color: #f9faff;
            transition: border-color 0.3s ease;
        }

        select:focus {
            border-color: #4A90E2;
        }

        .dealer-section {
            width: 100%;
            margin-bottom: 30px;
        }

        .dealer-section h2 {
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

            .filter-form {
                max-width: 100%;
            }

            .dealer-section h2 {
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

        <!-- District Filter Dropdown -->
        <form method="GET" class="filter-form">
            <label for="district_id">Select District:</label>
            <select name="district_id" id="district_id" onchange="this.form.submit()">
                <option value="">-- Select a District --</option>
                <?php foreach ($districts as $district): ?>
                    <option value="<?php echo $district['district_id']; ?>" 
                            <?php echo $selected_district_id == $district['district_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($district['district_name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($selected_district_id === null): ?>
            <p>Please select a district to view dealers.</p>
        <?php elseif (empty($dealers)): ?>
            <p>No dealers found in <?php echo htmlspecialchars($selected_district_name); ?>.</p>
        <?php else: ?>
            <div class="dealer-section">
                <h2><?php echo htmlspecialchars($selected_district_name); ?> Dealers</h2>
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
        <?php endif; ?>

        <a href="../login/logout.php" class="logout-link">Logout</a>
    </div>
</body>
</html>
<?php $conn->close(); ?>