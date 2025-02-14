<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = ""; // Leave blank if using XAMPP's default configuration
$dbname = "world_cup";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch comments
$sql = "SELECT name, comment, created_at FROM comments ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<p><strong>" . htmlspecialchars($row['name']) . "</strong> (" . $row['created_at'] . "): " . htmlspecialchars($row['comment']) . "</p>";
    }
} else {
    echo "<p>No comments yet.</p>";
}

// Close connection
$conn->close();
?>
