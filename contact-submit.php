<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";


// Connect to database
$conn = new mysqli("localhost", "root", "", "myweb");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
$fullname = htmlspecialchars($_POST['fullname']);
$email    = htmlspecialchars($_POST['email']);
$phone    = htmlspecialchars($_POST['phone']);
$subject = isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : '';
$message  = htmlspecialchars($_POST['message']);

// Insert into database
$sql = "INSERT INTO contact_form (fullname, email, phone, subject, message)
        VALUES ('$fullname', '$email', '$phone', '$subject', '$message')";

if ($conn->query($sql) === TRUE) {
  header("Location: contacting.html");
  exit();
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>