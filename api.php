<?php
// Connect to database
$conn = new mysqli('localhost', 'root', '', 'Grocery');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the request method
$method = $_SERVER['REQUEST_METHOD'];

// Process the request
switch ($method) {
    case 'POST': // Add Product
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'];
        $description = $data['description'];
        $price = $data['price'];
        $category_id = $data['category_id'];
        $sql = "INSERT INTO products (name, description, price, category_id, created) 
                VALUES ('$name', '$description', '$price', '$category_id', NOW())";
        echo $conn->query($sql) ? "Product added successfully" : "Error: " . $conn->error;
        break;

    case 'GET': // Show Products
        $result = $conn->query("SELECT * FROM products");
        $products = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($products);
        break;

    case 'DELETE': // Delete Product
        $id = $_GET['id'];
        $sql = "DELETE FROM products WHERE id = $id";
        echo $conn->query($sql) ? "Product deleted successfully" : "Error: " . $conn->error;
        break;

    default:
        echo "Invalid request method!";
        break;
}
$conn->close();
?>
