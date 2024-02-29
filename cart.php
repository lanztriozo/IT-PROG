<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
<style>
    body {
        margin: 0;
    padding: 0;
    }
    .container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 600px;
        width: 100%;
        margin-left: 450px;
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
        margin-top: 40px; 
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
    <div class="container">
        <div class="navbar">
            <nav>
                <a href="home.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="set.php">Set</a>
                <a href="cart.php">Cart</a>
            </nav>
        </div>
    </div>
    <?php
    session_start();
    
    if (isset($_SESSION['user_id'])) {
        $userId = $_SESSION['user_id'];
        $totalPrice = 0; // Initialize total price variable

        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "confectionary_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Retrieve user's wallet balance
        $sqlWallet = "SELECT wallet FROM User WHERE user_id = $userId";
        $resultWallet = $conn->query($sqlWallet);
        $walletBalance = 0;

        if ($resultWallet->num_rows > 0) {
            $row = $resultWallet->fetch_assoc();
            $walletBalance = $row["wallet"];
        }

        // Retrieve sets in the cart
        $sqlSets = "SELECT Catalog.catalog_id, Catalog.set_id, `Set`.set_price
                    FROM Catalog
                    INNER JOIN `Set` ON Catalog.set_id = `Set`.set_id
                    WHERE Catalog.catalog_id IN (SELECT catalog_id FROM Cart WHERE user_id = $userId)";
        $resultSets = $conn->query($sqlSets);

        // Retrieve individual items in the cart
        $sqlItems = "SELECT Catalog.catalog_id, Item.item_name, Item.item_price, item_list.quantity
                    FROM Catalog
                    INNER JOIN item_list ON Catalog.catalog_id = item_list.catalog_id
                    INNER JOIN Item ON item_list.item_id = Item.item_id
                    WHERE Catalog.catalog_id IN (SELECT catalog_id FROM Cart WHERE user_id = $userId)";
        $resultItems = $conn->query($sqlItems);

        echo "<h2>Cart Items:</h2>";
        echo "<table border='1'>";
        echo "<tr><th>Item</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr>";

        // Display sets and their prices
        while ($row = $resultSets->fetch_assoc()) {
            echo "<tr>";
            echo "<td>Set ID: " . $row["set_id"] . "</td>";
            echo "<td>$" . $row["set_price"] . "</td>";
            echo "<td>1</td>";
            echo "<td>$" . $row["set_price"] . "</td>";
            echo "</tr>";
            $totalPrice += $row["set_price"];
        }

        // Display individual items and their prices
        while ($row = $resultItems->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["item_name"] . "</td>";
            echo "<td>$" . $row["item_price"] . "</td>";
            echo "<td>" . $row["quantity"] . "</td>";
            echo "<td>$" . ($row["item_price"] * $row["quantity"]) . "</td>";
            echo "</tr>";
            $totalPrice += $row["item_price"] * $row["quantity"];
            $_SESSION['total_price'] = $totalPrice;
        }

        echo "<tr><td colspan='3'>Total Price:</td><td>$totalPrice</td></tr>";
        echo "<tr><td colspan='3'>Wallet Balance:</td><td>$walletBalance</td></tr>";
        echo "</table>";

        $conn->close();
    } else {
        echo "User ID not found.";
    }
    ?>
        </div>
        <div>
            <p>Total Price: <?php echo $totalPrice; ?></p>
            <?php if($totalPrice > 0) { ?>
                <form action="checkout.php" method="post">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                    <input type="submit" value="Checkout">
                </form>
            <?php } ?>
            <form action="addWallet.php" method="post">
            <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
            <input type="submit" value="Add to Wallet">
        </form>
        </div>
</body>
</html>
