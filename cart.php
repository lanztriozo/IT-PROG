<?php
session_start();

// Simulate being logged in as userID 1 for testing purposes
$_SESSION['user_ID'] = 1;

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

// Function to handle checkout process
function checkout($userID, $totalPrice, $conn) {
    // Clear the cart
    $clearCartSQL = "DELETE FROM cart WHERE user_ID = $userID";
    $conn->query($clearCartSQL);

    // Subtract the purchased quantity from item_stock
    $updateStockSQL = "UPDATE item JOIN catalog ON item.item_ID = catalog.item_ID 
                       JOIN cart ON catalog.catalog_ID = cart.catalog_ID 
                       SET item.item_stock = item.item_stock - cart.quantity 
                       WHERE cart.user_ID = $userID";
    $conn->query($updateStockSQL);

    // Remove associated set
    $removeSetSQL = "DELETE FROM cart WHERE catalog_ID IN (SELECT set_ID FROM `set`)";
    $conn->query($removeSetSQL);

    // Subtract the total price from user's funds
    $updateFundsSQL = "UPDATE user SET user_funds = user_funds - $totalPrice WHERE user_ID = $userID";
    $conn->query($updateFundsSQL);
}

// Check if checkout button is clicked
if (isset($_POST['checkout'])) {
    // Fetch total price and user ID
    $userID = $_SESSION['user_ID'];
    $totalPrice = $_POST['total_price'];

    // Call checkout function
    checkout($userID, $totalPrice, $conn);
}

// Fetch cart items and user's funds for the logged-in user
$userID = $_SESSION['user_ID'];
$cartSQL = "SELECT item.item_name, item.item_price, cart.quantity, cart.user_ID FROM cart JOIN catalog ON cart.catalog_ID = catalog.catalog_ID JOIN item ON catalog.item_ID = item.item_ID WHERE cart.user_ID = $userID";
$cartResult = $conn->query($cartSQL);

$userSQL = "SELECT user_funds FROM user WHERE user_ID = $userID";
$userResult = $conn->query($userSQL);
$userRow = $userResult->fetch_assoc();
$userFunds = $userRow["user_funds"];

// Initialize total price variable
$totalPrice = 0;

// Store cart items in an array
$cartItems = array();
if ($cartResult->num_rows > 0) {
    while ($row = $cartResult->fetch_assoc()) {
        $cartItems[] = $row;
        // Calculate total price
        $totalPrice += $row["item_price"] * $row["quantity"];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <!-- Link the external CSS file -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <h1>Shopping Cart</h1>
<main>
    <h2>My Cart</h2>
    <?php
    if (!empty($cartItems)) {
        echo '<table>';
        echo '<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Remove</th></tr>';
        foreach ($cartItems as $item) {
            echo '<tr>';
            echo '<td>' . $item["item_name"] . '</td>';
            echo '<td>$' . $item["item_price"] . '</td>';
            echo '<td>' . $item["quantity"] . '</td>';
            echo '<td><form method="post" action="remove_from_cart.php">';
            echo '<input type="hidden" name="user_ID" value="' . $item["user_ID"] . '">';
            echo '<input type="hidden" name="item_name" value="' . $item["item_name"] . '">';
            echo '<button type="submit" class="remove-from-cart-button" name="remove">Remove</button>';
            echo '</form></td>';
            echo '</tr>';
        }
        echo '</table>';

        // Display user's funds and total price of the cart
        echo '<p>User Funds: $' . $userFunds . '</p>';
        echo '<p>Total Price: $' . $totalPrice . '</p>';

        // Checkout button
        echo '<form method="post" action="">';
        echo '<input type="hidden" name="total_price" value="' . $totalPrice . '">';
        echo '<button type="submit" class="checkout-button" name="checkout">Checkout</button>';
        echo '</form>';
    } else {
        echo '<p>Your cart is empty.</p>';
    }
    ?>
</main>
<footer>
    <p>&copy; 2024 Online Candy Shop</p>
</footer>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
