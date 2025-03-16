<?php
include('db.php');
session_start();

$a1 = $a2 = $a3 = $a4 = $a5 = $a6 = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $username = $_POST['username'];
  $password = $_POST['password'];


  // Check if the username is already taken
  $username_check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $username_check_stmt->bind_param("s", $username);
  $username_check_stmt->execute();
  $username_check_result = $username_check_stmt->get_result();

  if ($username_check_result->num_rows > 0) {
    $a4 = "Username already exists!";
  } else {
    // Insert new user into the database
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $user_type = 'user'; // Default user type value

    $insert_stmt = $conn->prepare("INSERT INTO users (username, password, user_type) VALUES (?, ?, ?)");
    $insert_stmt->bind_param("sss", $username, $hashed_password, $user_type);

    if ($insert_stmt->execute()) {
      $_SESSION['username'] = $username;
      $_SESSION['user_type'] = $user_type;
      header("Location: /Major-project/main/index.php");
      exit; // Redirect after successful registration
    } else {
      $a5 = "Error registering user!";
    }

    $insert_stmt->close();
  }

  $username_check_stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup Form</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="wrapper">
    <form action="signup.php" method="post" id="signupForm">
      <h2>Signup</h2>
      <div class="input-field">
        <input type="text" name="username" id="username" required>
        <?php
            echo "<p>$a4</p>";
        ?>
        <label>Enter your username</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" id="password" required>
        <label>Enter your password</label>
      </div>
      <div class="input-field">
        <input type="password" name="confirm_password" id="confirm_password" required>
        <label>ReEnter your password</label>
      </div>

      <button type="submit">Sign Up</button>
      <div class="register">
        <p>Already have an account? <a href="login.php">Login</a></p>
      </div>
    </form>
    <?php
        echo "<p>$a5</p>";
    ?>
  </div>

  <script>
    document.getElementById('signupForm').addEventListener('submit', function(event) {
      const passwordInput = document.getElementById('password');
      const confirmPasswordInput = document.getElementById('confirm_password');

      // Validate password and confirm password
      if (passwordInput.value !== confirmPasswordInput.value) {
        alert("Password and confirm password do not match.");
        event.preventDefault(); // Prevent form submission
      }
    });
  </script>
</body>

</html>
