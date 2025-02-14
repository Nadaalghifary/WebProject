<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Set content type to JSON
header("Content-Type: application/json");

// Check if the request is valid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['credential'])) {
    $token = $_POST['credential'];

    // Verify the token with Google's API
    $url = "https://oauth2.googleapis.com/tokeninfo?id_token=" . $token;
    $response = file_get_contents($url);

    if ($response === false) {
        echo json_encode(["status" => "error", "message" => "Unable to verify token."]);
        exit;
    }

    // Parse user data from Google's response
    $userData = json_decode($response, true);

    if (isset($userData['email'])) {
        $email = $userData['email'];
        $name = $userData['name'];

        // Database connection
        $host = "localhost";
        $db_user = "root"; // Replace with your DB username
        $db_pass = "";     // Replace with your DB password
        $db_name = "your_database_name"; // Replace with your database name

        $conn = new mysqli($host, $db_user, $db_pass, $db_name);

        // Check if the connection was successful
        if ($conn->connect_error) {
            echo json_encode(["status" => "error", "message" => "Database connection failed."]);
            exit;
        }

        // Check if the user exists
        $query = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        if ($result->num_rows > 0) {
            // User exists; login successful
            echo json_encode(["status" => "success", "message" => "Welcome back, $name"]);
        } else {
            // User does not exist; create a new account
            $query = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
            $query->bind_param("ss", $name, $email);

            if ($query->execute()) {
                echo json_encode(["status" => "success", "message" => "Account created for $name"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to create user account."]);
            }
        }

        // Close connections
        $query->close();
        $conn->close();
    } else {
        // Invalid token
        echo json_encode(["status" => "error", "message" => "Invalid Google token."]);
    }
} else {
    // Invalid request
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
?>
