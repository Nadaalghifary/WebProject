<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['google_id'])) {
    header('Location: login.php');
    exit();
}

echo "Hello, " . $_SESSION['name'];
echo "<br><br>";
echo "Email: " . $_SESSION['email'];
echo "<br><br>";
echo "<a href='logout.php'>Logout</a>";
?>
