<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confectionary Items</title>
    <link rel="stylesheet" href="styles.css">
</head>
<style>
    .container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 600px;
        width: 100%;
        margin-left: 13px;
        margin-bottom: 10px;
    }
    
    .user-container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 600px;
        width: 100%;
        margin-left: 13px;
        margin-bottom: 10px;
    }
    
    .navbar { /*Navigation Bar for Home, Shop, Set, Cart */
        position: fixed;
        z-index: 1000;
        text-align: center;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 16px; 
        font-weight: 700; 
        line-height: 25px; 
        background-color: #fa89d1;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        border-radius: 50px;
        margin-left: 80px;
        margin-top: 20px;
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
        font-weight: bold;
    }

    .category-button {
    margin-top: 40px;
    margin-bottom: 50px;
    margin-right: 10px;
    background-color: #eb8dc8;
    color: black; 
    border: 1px solid #eb8dc8; 
    border-radius: 4px;
    padding: 8px 12px;
    cursor: pointer;
    text-align: center;
    }

    .item-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    width: 80%;
    margin-top: 80px;
    overflow-y: auto;
    }

    .item-box {
    width: 20%;
    background-color: #ffffff; 
    border: 2px solid #eb8dc8; 
    border-radius: 8px;
    margin: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    overflow-y: auto;
    }

    .add-to-cart-button {
    background-color: #eb8dc8; 
    color: black;
    border: 1px solid #eb8dc8; 
    border-radius: 4px;
    padding: 8px 12px;
    cursor: pointer;
    text-align: center;
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
<body>
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
                        <a href="Admin-UserListing.php">Order History</a>
                    </div>
                </div>
                <?php endif; ?>
            </nav>
        </div>
    </div>
    <div class="user-container">
    </div>
    <h2>TO BE CHANGED ORDERS WILL USE SAME FORMAT and LAYOUT AS SHOP</h2>
    <?php
    // get user id
    $userid = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : 0;
    //$userid = 2;
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "confectionary";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $categoryQuery = "SELECT DISTINCT category FROM item"; // Use DISTINCT to get unique categories only
    $categoryResult = $conn->query($categoryQuery);
    $categories = array();

    if ($categoryResult) {
        if ($categoryResult->num_rows > 0) {
            while ($row = $categoryResult->fetch_assoc()) {
                $categories[] = $row['category']; // Append category to the array
            }
        } else {
            echo "No categories found";
        }
    } else {
        echo "Error executing category query: " . $conn->error;
    }

    // Remove duplicates from the array
    $uniqueCategories = array_unique($categories);

    // Display category buttons
    echo '<div class="category-buttons">';

    echo 'TO BE CHANGED, ORDERS WILL USE SAME FORMAT and LAYOUT AS SHOP';
    echo  '</div>';

    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
    $categoryFilter = "";
    $parameters = array();
    
    if ($selectedCategory) {
        $categoryFilter = " WHERE category = ?";
        $parameters[] = $selectedCategory;
    }
    
    // SQL query to retrieve items with category filter
    $sql = "SELECT item_ID, item_name, item_desc, item_stock, item_price FROM item" . $categoryFilter;
    
    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    
    // Bind parameters if necessary
    if ($selectedCategory) {
        $stmt->bind_param("s", $selectedCategory); // Assuming category is a string, change "s" if it's a different data type
    }
    
    // Execute the statement
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Display items in separate boxes
        echo '<div class="item-container">';

        while ($row = $result->fetch_assoc()) {
            echo '<div class="item-box">
                    <h3>' . $row["item_name"] . '</h3>
                    <p>' . $row["item_desc"] . '</p>
                    <p>Stock: ' . $row["item_stock"] . '</p>
                    <p>Price: ₱' . $row["item_price"] . '</p>
                    <input type="number" class="quantity-input" placeholder="Quantity" min="1" max="' . $row["item_stock"] . '" id="quantity' . $row["item_ID"] . '">
                    <button class="add-to-cart-button" onclick="addToCart(' . $row["item_ID"] . ', ' . $row["item_stock"] . ', '. $userid.')">Add to Cart</button>
                    <div class="error-message" id="error' . $row["item_ID"] . '"></div>
                </div>';
        }

        echo '</div>';
    } else {
        echo "0 results";
    }

    // Close connection
    $conn->close();
    ?>
    <script>
        function addToCart(itemId, maxStock, userid) {
            var quantityInput = document.getElementById('quantity' + itemId);
            var errorDiv = document.getElementById('error' + itemId);
            var quantity = quantityInput.value;

            if (quantity > 0 && quantity <= maxStock) {
                /*alert('Added ' + quantity + ' item(s) to cart for Item ID: ' + itemId;
                errorDiv.innerHTML = ''; // Clear any previous error message*/
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'addToCart.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert('Added ' + quantity + ' item(s) to cart for Item ID: ' + itemId + ' ' + userid);
                            errorDiv.innerHTML = ''; // Clear any previous error message
                        } else {
                            alert('Error adding item to cart.');
                            errorDiv.innerHTML = 'Error adding item to cart.';
                        }
                    }
                };
                xhr.send('&itemId=' + encodeURIComponent(itemId) + '&userid=' + encodeURIComponent(userid) + '&quantity=' + encodeURIComponent(quantity));
            } else {
                errorDiv.innerHTML = 'Please enter a valid quantity (1-' + maxStock + ').';
            }
        }
    </script>
</body>
</html>
