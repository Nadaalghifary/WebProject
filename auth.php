<?php
require_once 'vendor/autoload.php'; // Composer's autoload

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

use Google\Client;

// Database connection details
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your database password
$dbname = "google_auth";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['status' => 'error', 'message' => 'Database connection failed.']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $credential = $_POST['credential'] ?? '';

    if (empty($credential)) {
        echo json_encode(['status' => 'error', 'message' => 'Credential is missing.']);
        exit;
    }

    try {
        // Initialize Google Client
        $client = new Google_Client();
        $client->setClientId('YOUR_CLIENT_ID'); // Replace with your Google Client ID

        // Verify the token
        $payload = $client->verifyIdToken($credential);

        if ($payload) {
            // Extract user info
            $google_id = $payload['sub'];
            $email = $payload['email'];
            $name = $payload['name'];

            // Check if user exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE google_id = ?");
            $stmt->bind_param("s", $google_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // User exists
                $user = $result->fetch_assoc();
                echo json_encode(['status' => 'success', 'message' => 'Login successful.', 'user' => $user]);
            } else {
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (google_id, name, email) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $google_id, $name, $email);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Sign up successful.']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $stmt->error]);
                }
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID token.']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error verifying token: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}
