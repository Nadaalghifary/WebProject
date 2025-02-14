<?php
// Database connection details
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP (leave empty)
$dbname = "world_cup_booking";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$matchs = $_POST['matchs'];
$category = $_POST['category'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO tickets (matchs, category, name, email, phone) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $matchs, $category, $name, $email, $phone);

// Execute the statement
if ($stmt->execute()) {
    echo "Booking successful! A confirmation email has been sent to your email.";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
