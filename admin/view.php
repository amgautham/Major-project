<?php
$servername = "localhost";
$username = "root"; // Change if needed
$password = ""; // Change if needed
$database = "low_cost_housing"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch table names, excluding 'user' table
$sql = "SHOW TABLES";
$result = $conn->query($sql);

// Store all table names in an array
$tables = [];
while ($row = $result->fetch_array()) {
    if ($row[0] !== 'user') { // Exclude 'user' table
        $tables[] = $row[0];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Tables</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
        }
        h2 {
            color: #333;
            margin-top: 40px;
        }
        table {
            margin: auto;
            border-collapse: collapse;
            width: 90%;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 40px;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007bff;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        p {
            color: #777;
            font-size: 18px;
        }
    </style>
</head>
<body>

    <h1>Database: <?php echo htmlspecialchars($database); ?></h1>

    <?php
    if (empty($tables)) {
        echo "<p>No tables found in the database.</p>";
    } else {
        foreach ($tables as $table) {
            echo "<h2>Table: " . htmlspecialchars($table) . "</h2>";

            // Query to fetch all data from each table
            $query = "SELECT * FROM `$table`";
            $data_result = $conn->query($query);

            if ($data_result->num_rows > 0) {
                echo "<table border='1'>";
                
                // Fetch column names
                $fields = $data_result->fetch_fields();
                echo "<tr>";
                foreach ($fields as $field) {
                    echo "<th>" . htmlspecialchars($field->name) . "</th>";
                }
                echo "</tr>";

                // Fetch and display table data
                while ($row = $data_result->fetch_assoc()) {
                    echo "<tr>";
                    foreach ($row as $cell) {
                        echo "<td>" . htmlspecialchars($cell) . "</td>";
                    }
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "<p>No data found in this table.</p>";
            }
        }
    }

    $conn->close();
    ?>

</body>
</html>
