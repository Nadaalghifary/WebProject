<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // Leave blank if using XAMPP's default configuration
$dbname = "world_cup"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['fan_name'];
$comment = $_POST['fan_comment'];

// Prepare and bind statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO comments (name, comment) VALUES (?, ?)");
$stmt->bind_param("ss", $name, $comment);

if ($stmt->execute()) {
    echo "Comment submitted successfully!";
} else {
    echo "Error: " . $stmt->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>
