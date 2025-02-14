<?php
// Database connection
$host = 'localhost'; // Update if required
$db_name = 'Grocery';
$username = 'root'; // Use your database username
$password = ''; // Use your database password

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit;
}
?>
