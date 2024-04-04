<?php
session_start();
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "confectionary";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the necessary parameters are set
    if (isset($_POST['itemId']) && isset($_POST['userid']) && isset($_POST['quantity'])) {
        // Sanitize and validate input
        $itemId = $_POST['itemId'];
        $userid = $_POST['userid'];
        $quantity = $_POST['quantity'];

        // Validate the item ID and quantity (e.g., against a database of products)
        // Assuming $conn is your database connection
        // Example validation:
        // if (isValidItem($itemId) && isValidQuantity($quantity)) {
        
        // Insert the item into the cart
        $insertCat = "INSERT INTO catalog VALUES(NULL, '$itemId', NULL)";
        
        // Execute the query
        if (mysqli_query($conn, $insertCat)) {
            // Item added successfully
            $getCat = mysqli_query($conn,"SELECT MAX(catalog_ID) AS high FROM catalog");
            while($catalogResult = mysqli_fetch_assoc($getCat)) {
                (int)$catalogid = $catalogResult['high'];
            }
            $insertCart = "INSERT INTO cart(user_ID, catalog_ID, quantity) VALUES('$userid', '$catalogid','$quantity')";
            mysqli_query($conn, $insertCart);
            http_response_code(200); // Send success status code
            exit(); // Exit PHP script
        } else {
            // Error adding item to cart
            http_response_code(500); // Send internal server error status code
            exit(); // Exit PHP script
        }
    }
}

// Invalid request method or missing parameters
http_response_code(400); // Send bad request status code
exit(); // Exit PHP script
?>
