<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop</title>
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

    .shop-header {
    display: flex;
    justify-content: center;
    text-align: center;
    font-weight: bold;
    margin-bottom: -15px;
    
    }

    .category-filter-container {
    background-color: #fa89d1;
    margin-left: 525px;
    max-width: 400px;
    border-radius: 15px;
    padding: 10px;
    margin-bottom: 20px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
    }

    .category-filter-form {
    display: flex;
    flex-direction: column;
    align-items: center;
    }

    .category-filter-form label {
    margin-bottom: 5px;
    }

    .category-filter-form select, .category-filter-form input[type="submit"] {
    padding: 5px;
    border: none;
    border-radius: 5px;
    margin-bottom: 5px;
    }
    
    .shop-container table {
    margin: auto;
    }

    .user-id {
    margin-left: 695px;
    }
</style>
</head>
<body>
    <?php
        session_start();
        $userid = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; // put user id here
        $conn = mysqli_connect("localhost","root", "") or die ("Unable to connect!". mysqli_error());
        mysqli_select_db($conn, "confectionary_db");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['item']) && isset($_POST['cart']) && isset($_POST['quantity'])) { 
                $item = (int)$_POST['item']; //item id
                $cartid = (int)$_POST['cart']; //user id
                $quantity = $_POST['quantity'];
                $counter = 0; $catalogArray; $itemquantity;

                $sql1 = mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$cartid'");
                while($result1 = mysqli_fetch_assoc($sql1)) {
                    (int)$catalogArray = $result1['catalog_id'];
                }

                if(empty($catalogArray)) { //checks if there is a catalog for the user id
                    $catalogGet = mysqli_query($conn,"SELECT MAX(catalog_id) AS high FROM catalog"); 
                    while($catalogResult = mysqli_fetch_assoc($catalogGet)) {
                        (int)$catalogid = $catalogResult['high'];
                    }
                    (int)$newID = ($catalogid + 1);
                    $insertCat = "INSERT INTO catalog(catalog_id) VALUES('$newID')";
                    mysqli_query($conn, $insertCat);
                    $insertCat = "INSERT INTO cart VALUES('$cartid','$newID')";
                    mysqli_query($conn, $insertCat);
                    (int)$catalogArray = $newID;
                } else {
                    $sql = "SELECT * FROM item_list WHERE item_id='$item' AND catalog_id='$catalogArray'";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        ++$counter;
                    }
                }

                $sql2 = mysqli_query($conn,"SELECT * FROM item WHERE item_id='$item'");
                while($result2 = mysqli_fetch_assoc($sql2)) {
                    (int)$itemquantity = $result2['item_stock'];
                }

                if($counter == 0 && $quantity != 0 && !empty($quantity) && $quantity <= $itemquantity) {
                    $insertCart = "INSERT INTO item_list(catalog_id,item_id,quantity) VALUES('$catalogArray', '$item', '$quantity')"; 
                    mysqli_query($conn, $insertCart);
                    echo "item added to cart";
                } elseif ($quantity > $itemquantity) {
                    echo "quantity too high"; // goes on top of the page selection
                } elseif ($quantity == 0 || empty($quantity)) {
                    echo "add quantity to your item"; // goes on top of the page selection
                } else {
                    echo "already in cart, remove item in cart first if u want to increase quantity"; // goes on top of the page selection
                }
            }
        }
    ?>
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
    <div class="shop-header">
        <h2>Shop</h2>
    </div>
    <div class = "user-id">
    <?php
    //To twest and check if the user_id passed properly from the login.
    echo "<p>User ID: $userid</p>";
    ?>
    </div>
    <div class="category-filter-container">
    <div class="category-filter-form">
    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="category">Category Filter:</label>
        <select name="category" id="category">
            <option value="">All Categories</option>
            <?php
                $SQLQueryCategory = mysqli_query($conn, "SELECT DISTINCT category_name FROM category");
                while($SQLQueryResult = mysqli_fetch_assoc($SQLQueryCategory)) {
                    echo "<option value='" . $SQLQueryResult["category_name"] . "'>" . $SQLQueryResult["category_name"] . "</option>";
                }
            ?>
        </select>
        <input type="submit" value="Filter">
    </form>
    </div>
    </div>
    <div class = "shop-container">
    <table border="1" width='850'>
        <tr bgcolor="pink">
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Quantity</th>
        </tr>
    </div>
        <?php
            $categoryFilter = isset($_GET['category']) ? $_GET['category'] : '';
            $itemQuery = "SELECT * FROM item
                          JOIN category ON item.category_id = category.category_id ";

            if (!empty($categoryFilter)) { //If the filter is used, this adds a query to SQL that filters the category
                $itemQuery .= "WHERE category.category_name = '$categoryFilter' ";
            }
            $itemQuery .= "ORDER BY item.item_id";
            $itemResult = mysqli_query($conn, $itemQuery);
            
            while($studResult = mysqli_fetch_assoc($itemResult)) {
                echo "<tr>";
                echo "<td>". $studResult["item_name"]."</td>";
                echo "<td>". $studResult["category_name"]."</td>";
                echo "<td>". $studResult["item_desc"]."</td>";
                echo "<td>". $studResult["item_price"]."</td>";
                echo "<td>". $studResult["item_stock"]."</td>";
                echo "<td>";
                ?>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="number" name="quantity">
                    <input type="hidden" name="item" value="<?php echo $studResult["item_id"] ?>">
                    <input type="hidden" name="cart" value="<?php echo $userid ?>">
                    <input type="submit" value="Add to Cart">
                </form> 
                <?php 
                echo "</td>";
                echo "</tr>";
            }
        ?>
    </table>

    <script>
        //Code needs a javascript script to change the displayed items whenever the filter button is pressed.
        document.getElementById('category').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>
</html>