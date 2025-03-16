<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "buildwise";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// Check for empty fields
if (
  empty($_POST['name'])      ||
  empty($_POST['email'])     ||
  empty($_POST['message'])  ||
  !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)
) {
  echo "No arguments Provided!";
  return false;
}

$name = htmlspecialchars($_POST['name']);
$email_address = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);


$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, status) VALUES (?, ?, ?, 'unread')");
if ($stmt) {
  $stmt->bind_param("sss", $name, $email_address, $message);


  if ($stmt->execute()) {
    echo "✅ Message sent successfully!";
  } else {
    echo "❌ Error executing statement: " . $stmt->error;
  }

  $stmt->close();
} else {
  echo "❌ Failed to prepare statement.";
}
$conn->close();

return true;


// Check if the user is logged in
