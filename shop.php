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
    
</style>
</head>
<body>
    <?php
        session_start();
        $userid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0; // put user id here
        $conn = mysqli_connect("localhost","root", "") or die ("Unable to connect!". mysqli_error());
        mysqli_select_db($conn, "confectionary_db");

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['item']) && isset($_POST['cart']) && isset($_POST['quantity'])) { 
                $item = $_POST['item']; //item id
                $cartid = $_POST['cart']; //user id
                $quantity = $_POST['quantity'];
                $counter = 0; $catalogArray = array();

                $sql1 = mysqli_query($conn,"SELECT * FROM cart WHERE user_id='$cartid'");
                while($result1 = mysqli_fetch_assoc($sql1)) {
                    $catalogArray = $result1['catalog_id'];
                }

                if(is_array($catalogArray) && !is_null($catalogArray)) {
                    foreach ($catalogArray as $value) {
                        $sql2 = "SELECT * FROM catalog WHERE catalog_id='$value' AND item_id='$item'";
                        $result2 = $conn->query($sql2);
                        if ($result2->num_rows > 0) {
                            ++$counter;
                        }
                    }
                } elseif(!is_null($catalogArray)) {
                    $sql2 = "SELECT * FROM catalog WHERE catalog_id='$catalogArray' AND item_id='$item'";
                    $result2 = $conn->query($sql2);
                    if ($result2->num_rows > 0) {
                        ++$counter;
                    }
                }

                // adds catalog
                if($counter == 0 && $quantity != 0) {
                    $catalogGet = mysqli_query($conn,"SELECT * FROM catalog"); //adds another catalog even if a duplication error occurs in the cart
                    while($catalogResult = mysqli_fetch_assoc($catalogGet)) {
                        $catalogid = $catalogResult['catalog_id'];
                    }
                    $newID = ($catalogid + 1);
                    $insertCat = "INSERT INTO catalog(catalog_id,item_id) VALUES('$newID','$item')";
                    mysqli_query($conn, $insertCat);

                    //adds cart
                    $insertCart = "INSERT INTO cart VALUES('$cartid', '$quantity', '$newID')";  //causes duplication error if picking another item
                        
                    mysqli_query($conn, $insertCart);
                } elseif ($quantity == 0) {
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
    <h2>Shop</h2>
    <?php
    //To twest and check if the user_id passed properly from the login.
    echo "<p>User ID: $userid</p>";
    ?>
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

    <table border="1" width='750'>
        <tr bgcolor="pink">
            <th>Name</th>
            <th>Category</th>
            <th>Description</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Quantity</th>
        </tr>

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