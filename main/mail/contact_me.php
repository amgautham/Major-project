<?php
// Check for empty fields
/*if(empty($_POST['name'])  		||
   empty($_POST['email']) 		||
   empty($_POST['message'])	||
   !filter_var($_POST['email'],FILTER_VALIDATE_EMAIL))
   {
	echo "No arguments Provided!";
	return false;
   }
	
$name = $_POST['name'];
$email_address = $_POST['email'];
$message = $_POST['message'];
	
// Create the email and send the message
$to = 'yourname@yourdomain.com'; // Add your email address inbetween the '' replacing yourname@yourdomain.com - This is where the form will send a message to.
$email_subject = "Website Contact Form:  $name";
$email_body = "You have received a new message from your website contact form.\n\n"."Here are the details:\n\nName: $name\n\nEmail: $email_address\n\nMessage:\n$message";
$headers = "From: noreply@yourdomain.com\n"; // This is the email address the generated message will be from. We recommend using something like noreply@yourdomain.com.
$headers .= "Reply-To: $email_address";	
mail($to,$email_subject,$email_body,$headers);
return true;*/
session_start();
$success = "";
$error = "";
$name = $email = $message = "";
// Check if the user is logged in



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

// Initialize variables for form feedback


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['form_id'] === "contact_form") {
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";
  $name = htmlspecialchars(trim($_POST["name"] ?? ""));
  $email = htmlspecialchars(trim($_POST["email"] ?? ""));
  $message = htmlspecialchars(trim($_POST["message"] ?? ""));

  if (empty($name) || empty($email) || empty($message)) {
    echo "❌ All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Invalid email format.";
  } else {
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, status) VALUES (?, ?, ?, 'unread')");

    if ($stmt) {
      $stmt->bind_param("sss", $name, $email, $message);

      if ($stmt->execute()) {
        echo "✅ Message sent successfully!";
      } else {
        echo "❌ Error executing statement: " . $stmt->error;
      }

      $stmt->close();
    } else {
      echo "❌ Failed to prepare statement.";
    }
  }
}

$conn->close();



?>			
?>