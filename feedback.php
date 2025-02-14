<?php
require_once 'db_connection.php'; // Include database connection

// Get feedback data from the POST request
$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'];
$email = $data['email'];
$message = $data['message'];

// Insert the feedback into the database
$stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $name, $email, $message);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Feedback submitted successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error submitting feedback.']);
}
?>
