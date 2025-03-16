<?php
include('db.php');
session_start();

$a1 = $a2 = $a3 = $a4 = $a5 = $a6 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
        // Login Logic
        $username = $_POST['usernamelog'];
        $password = $_POST['passwordlog'];

        // Prepare and execute a SQL query to retrieve the hashed password and user type for the provided username
        $stmt = $conn->prepare("SELECT password, user_type FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $stored_password_hash = $row['password'];
            $admin = $row['user_type'];

            // Verify if the provided password matches the hashed password from the database
            if (password_verify($password, $stored_password_hash)) {
                $_SESSION['username'] = $username;
                
                $_SESSION['user_type'] = $admin;

                // Redirect based on user type
                if ($admin === 'admin') {
                    header("Location: /Majorpro/admin/menu.php");
                } else {
                    header("Location: /Major-project/main/index.php");
                }
                exit;
            } else {
                $a1 = "Wrong username or password!";
            }
        } else {
            $a1 = "User not found!";
        }

        $stmt->close(); // Close the statement
    
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title> Login Form </title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="wrapper">
    <form action="login.php" method="post">
      <h2>Login</h2>
        <div class="input-field">
        <input type="text" name="usernamelog" required>
        <?php
            echo "<p>$a2</p>" ;
        ?>
        <label>Enter your email</label>
      </div>
      <div class="input-field">
        <input type="password" name="passwordlog" required>
        <label>Enter your password</label>
      </div>
        <?php
            echo "<h2>$a1</h2>" ;
        ?>
      <div class="forget">
        
        
      </div>
      <button type="submit">Log In</button>
      <div class="register">
        <p>Don't have an account? <a href="signup.php">Register</a></p>
      </div>
    </form>
  </div>
</body>
</html>