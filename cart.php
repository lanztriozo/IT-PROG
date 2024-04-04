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

// Fetch user funds
$userID = $_SESSION['user_ID'];
$userFundsSQL = "SELECT user_funds FROM user WHERE user_ID = $userID";
$userFundsResult = $conn->query($userFundsSQL);
$userFunds = 0;
if ($userFundsResult->num_rows > 0) {
    $userFunds = $userFundsResult->fetch_assoc()["user_funds"];
}

// Fetch cart items for the logged-in user, including set items
$cartSQL = "SELECT 
                CASE 
                    WHEN item.item_name IS NOT NULL THEN item.item_name
                    ELSE CONCAT_WS(', ', 
                            (SELECT item_name FROM item WHERE item_ID = chocolate.chocolate_item_ID),
                            (SELECT item_name FROM item WHERE item_ID = pastry.pastry_item_ID),
                            (SELECT item_name FROM item WHERE item_ID = cake.cake_item_ID),
                            (SELECT item_name FROM item WHERE item_ID = candy.candy_item_ID)
                         )
                END AS item_name,
                CASE 
                    WHEN item.item_price IS NOT NULL THEN item.item_price
                    ELSE `set`.set_price
                END AS item_price,
                cart.quantity, 
                cart.user_ID 
            FROM cart 
            JOIN (catalog 
                LEFT JOIN item ON catalog.item_ID = item.item_ID 
                LEFT JOIN `set` ON catalog.set_ID = `set`.set_ID 
                LEFT JOIN chocolate ON `set`.chocolate_item_ID = chocolate.chocolate_item_ID
                LEFT JOIN pastry ON `set`.pastry_item_ID = pastry.pastry_item_ID
                LEFT JOIN cake ON `set`.cake_item_ID = cake.cake_item_ID
                LEFT JOIN candy ON `set`.candy_item_ID = candy.candy_item_ID
            ) ON cart.catalog_ID = catalog.catalog_ID 
            WHERE cart.user_ID = $userID";
$cartResult = $conn->query($cartSQL);

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
    <style>
        body {
        background-color: #f0f5f9; /* Pastel Blue Background */
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
    }

    .container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 600px;
        width: 100%;
        margin-left: 450px;
        margin-top: 40px;
    }
    
    .navbar { /*Navigation Bar for Home, Shop, Set, Cart */
        text-align: center;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 16px; 
        font-weight: 700; 
        line-height: 25px; 
        background-color: #fa89d1;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        border-radius: 50px;
        margin-top: 10px; 
    }

    nav {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    nav a {
        text-decoration: none;
        margin: 0 15px; 
        padding: 10px;
    }
    </style>
</head>
<body>
<main>
<div class ="container">
        <div class="navbar">
            <nav>
                <a href="home.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="set.php">Set</a>
                <a href="cart.php">Cart</a>
            </nav>
        </div>
    </div>
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

        // Display total price
        echo '<p>Total Price: $' . $totalPrice . '</p>';

        // Display user funds
        echo '<p>Your Funds: $' . $userFunds . '</p>';

        // Checkout button
        echo '<form method="post" action="checkout.php">';
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
