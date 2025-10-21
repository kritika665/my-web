<?php
$conn = new mysqli("localhost", "root", "", "myweb", 3307); // use your port

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Update visitor count
$conn->query("UPDATE visitor_count SET count = count + 1 WHERE id = 1");

// Get current count
$result = $conn->query("SELECT count FROM visitor_count WHERE id = 1");
$row = $result->fetch_assoc();
$visitorCount = $row['count'];

$conn->close();
?>