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
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_1),
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_2),
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_3),
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_4)
                         )
                END AS item_name,
                CASE 
                    WHEN item.item_price IS NOT NULL THEN item.item_price
                    ELSE `set`.set_price
                END AS item_price,
                cart.quantity, 
                cart.user_ID,
                cart.catalog_ID 
            FROM cart 
            LEFT JOIN catalog ON cart.catalog_ID = catalog.catalog_ID
            LEFT JOIN item ON catalog.item_ID = item.item_ID
            LEFT JOIN `set` ON catalog.set_ID = `set`.set_ID 
            WHERE cart.user_ID = $userID";
$cartResult = $conn->query($cartSQL);

// Initialize total price variable
$totalPrice = 0;

// Store cart items in an array
$cartItems = array();
if ($cartResult->num_rows > 0) {
    while ($row = $cartResult->fetch_assoc()) {
        $cartItems[] = $row;
        $catalog_ID = $row['catalog_ID'];
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
        width: 100vw;
        margin-top: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
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

    .cart-container {
        width: 70vw;
        height: 60vh;
    }

    .add-to-funds-button {
    background-color: #327344; 
    color: white;
    border: 1px solid #327344; 
    border-radius: 4px;
    padding: 8px 12px;
    cursor: pointer;
    text-align: center;
    margin-left: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }

    .remove-from-cart-button {
        background-color: #f44336;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 4px;
        cursor: pointer;
    }
    .remove-from-cart-button:hover {
        background-color: #d32f2f;
    }

    .checkout-button {
        background-color: #eb8dc8;
        color: white;
        border: none;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin-top: 10px;
        cursor: pointer;
        border-radius: 4px;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        z-index: 1;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
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
                <a href="orders.php">Orders</a>
                <?php if ($_SESSION['user_admin'] == 'Y'): ?>
                <div class="dropdown">
                    <a href="#" class="dropbtn">Admin</a>
                    <div class="dropdown-content">
                        <a href="Admin-CompanyCreation.php">Create Company</a>
                        <a href="Admin-ItemCreation.php">Create Items</a>
                        <a href="Admin-ItemListing.php">Update Items</a>
                        <a href="Admin-UserListing.php">Update Users</a>
                        <a href="Admin-ReportListing.php">Order History</a>
                    </div>
                </div>
                <?php endif; ?>
                </nav>
            </div>

        <div class="cart-container">
        <h2>My Cart</h2>
        <?php
            if (!empty($cartItems)) {
                echo '<form method="post" action="addToFunds.php">';
                echo '<input type="number" name="fundsToAdd" class="quantity-input" placeholder="Funds" min="1" max="">';
                echo '<button type="submit" class="add-to-funds-button">Add to Funds</button>';
                if (isset($_SESSION['fundsincrease']) && $_SESSION['fundsincrease']) {
                    echo '<div style="color: green;">Funds Increased</div>';
                    unset($_SESSION['fundsincrease']);
                }
                echo '</form>';
                echo '<table>';
                echo '<tr><th>Item Name</th><th>Price</th><th>Quantity</th><th>Remove</th></tr>';
                foreach ($cartItems as $item) {
                    echo '<tr>';
                    echo '<td>' . $item["item_name"] . '</td>';
                    echo '<td>₱' . $item["item_price"] . '</td>';
                    echo '<td>' . $item["quantity"] . '</td>';
                    echo '<td><form method="post" action="remove_from_cart.php">';
                    echo '<input type="hidden" name="user_ID" value="' . $item["user_ID"] . '">';
                    echo '<input type="hidden" name="catalog_ID" value="' . $catalog_ID . '">';
                    echo '<button type="submit" class="remove-from-cart-button" name="remove">Remove</button>';
                    echo '</form></td>';
                    echo '</tr>';
                }
                echo '</table>';

                echo '<p>Total Price: ₱' . $totalPrice . '</p>';

                echo '<p>Your Funds: ₱' . $userFunds . '</p>';

                // Checkout button
                echo '<form method="post" action="checkout.php">';
                echo '<input type="hidden" name="total_price" value="' . $totalPrice . '">';
                echo '<input type="hidden" name="catalog_ID" value="' . $catalog_ID . '">';
                echo '<button type="submit" class="checkout-button" name="checkout">Checkout</button>';
                echo '</form>';
            } else if (isset($_SESSION['cartpurchase']) && $_SESSION['cartpurchase']) {
                    echo '<div style="color: green;">Successful Purchase</div>';
                    unset($_SESSION['cartpurchase']);
            } else {
                echo '<p>Your cart is empty.</p>';
            }
            ?>
        </div>
    </div>
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
