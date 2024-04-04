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

// Fetch total price from the form submission
$totalPrice = $_POST['total_price'];

// Fetch user ID from session
$userID = $_SESSION['user_ID'];

// Fetch user's funds from the database
$userFundsSQL = "SELECT user_funds FROM `user` WHERE user_ID = $userID";
$userFundsResult = $conn->query($userFundsSQL);

if ($userFundsResult->num_rows > 0) {
    $row = $userFundsResult->fetch_assoc();
    $userFunds = $row['user_funds'];
    
    // Check if the user has enough funds to checkout
    if ($userFunds >= $totalPrice) {
        // Subtract the total price from the user's funds
        $newFunds = $userFunds - $totalPrice;

        // Update user's funds in the database
        $updateFundsSQL = "UPDATE `user` SET user_funds = $newFunds WHERE user_ID = $userID";
        $conn->query($updateFundsSQL);

        // Remove the set from the catalog and sets table
        $removeSetSQL = "DELETE FROM catalog WHERE set_ID IN (SELECT set_ID FROM `set` WHERE set_ID IN (SELECT set_ID FROM catalog WHERE item_ID IS NULL))";
        $conn->query($removeSetSQL);

        // Clear the user's cart
        $clearCartSQL = "DELETE FROM cart WHERE user_ID = $userID";
        $conn->query($clearCartSQL);

        // Redirect to the cart page with a success message
        header("Location: cart.php?checkout=success");
        exit();
    } else {
        // Redirect to the cart page with an error message
        header("Location: cart.php?error=insufficient_funds");
        exit();
    }
} else {
    // Redirect to the cart page with an error message
    header("Location: cart.php?error=user_not_found");
    exit();
}

// Close connection
$conn->close();
?>
