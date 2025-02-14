<?php
require_once 'db_connection.php'; // Include database connection

// Get the city from the POST request
$data = json_decode(file_get_contents("php://input"), true);
$city = $data['city'];

// Insert the poll vote into the database
$stmt = $conn->prepare("INSERT INTO poll_votes (city) VALUES (?)");
$stmt->bind_param("s", $city);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Vote registered successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error registering vote.']);
}
?>
