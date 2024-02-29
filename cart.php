<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
</head>
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
<body>
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
    <div>
        <?php
        session_start();
        $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
        if(isset($_SESSION['user_id'])) {
            $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

            $conn = mysqli_connect("localhost","root", "") or die ("Unable to connect!". mysqli_error());
            mysqli_select_db($conn, "confectionary_db");


            $sql = "SELECT wallet FROM User WHERE user_id = $userId";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $wallet = $row["wallet"];
                $_SESSION['user_wallet']= $wallet;
                echo "<p>User's Wallet: $wallet</p>";
            }

            $sql = "SELECT Cart.quantity, Catalog.catalog_id, Catalog.set_id, Catalog.item_id, Item.item_name, Item.item_price
                    FROM Cart
                    INNER JOIN Catalog ON Cart.catalog_id = Catalog.catalog_id
                    LEFT JOIN Item ON Catalog.item_id = Item.item_id
                    WHERE Cart.user_id = $userId";
            $result = $conn->query($sql);

            $totalPrice = 0;

            if ($result->num_rows > 0) {
                echo "<table>";
                echo "<tr><th>Quantity</th><th>Item/Set</th><th>Price</th><th>Action</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["quantity"] . "</td>";
                    echo "<td>";
                    if ($row["set_id"] != null) {
                        echo "Set";
                    } else {
                        echo $row["item_name"];
                    }
                    echo "</td>";
                    echo "<td>" . $row["item_price"] * $row["quantity"] . "</td>";
                    echo "<td><a href='remove_item.php?catalog_id=" . $row["catalog_id"] . "'>Remove</a></td>";
                    echo "</tr>";
 
                    $totalPrice += $row["item_price"] * $row["quantity"];
                    $_SESSION['total_price'] = $totalPrice;
                }
                echo "</table>";
            } else {
                echo "No items in the cart.";
            }

            $conn->close();
        } else {
            echo "<br>id not found";
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
