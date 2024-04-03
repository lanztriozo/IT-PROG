<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "confectionary";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the remove button was clicked
if (isset($_POST['remove'])) {
    // Get the cart ID from the form
    $userID = $_POST['user_ID'];

    // SQL to delete cart item
    $sql = "DELETE FROM cart WHERE user_ID = $userID";

    if ($conn->query($sql) === TRUE) {
        echo "Item removed from cart successfully";
    } else {
        echo "Error removing item from cart: " . $conn->error;
    }
}

// Redirect back to cart page
header("Location: cart.php");
exit();

// Close connection
$conn->close();
?>
