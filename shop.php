<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confectionary Items</title>
    <!-- Link the external CSS file -->
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

    <?php
    session_start();
    // get user id
    $userid = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : 0;
    //$userid = 2;
    
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

    // Fetch categories for buttons
    $categoryQuery = "SELECT category_ID, category_name FROM category";
    $categoryResult = $conn->query($categoryQuery);
    $categories = array();

    if ($categoryResult->num_rows > 0) {
        while ($row = $categoryResult->fetch_assoc()) {
            $categories[$row['category_ID']] = $row['category_name'];
        }
    }

    // Display category buttons
    echo '<div class="category-buttons">
            <form method="get" action="">';

    foreach ($categories as $categoryID => $categoryName) {
        echo '<button class="category-button" type="submit" name="category" value="' . $categoryID . '">' . $categoryName . '</button>';
    }

    // Button for all items
    echo '<button class="category-button" type="submit" name="category" value="">All Items</button>';

    echo '</form>
        </div>';

    // Handle category filter
    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : null;
    $categoryFilter = $selectedCategory ? "WHERE category_ID = $selectedCategory" : "";
    
    // SQL query to retrieve items with category filter
    $sql = "SELECT item_ID, item_name, item_desc, item_stock, item_price FROM item $categoryFilter";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display items in separate boxes
        echo '<div class="item-container">';

        while ($row = $result->fetch_assoc()) {
            echo '<div class="item-box">
                    <h3>' . $row["item_name"] . '</h3>
                    <p>' . $row["item_desc"] . '</p>
                    <p>Stock: ' . $row["item_stock"] . '</p>
                    <p>Price: $' . $row["item_price"] . '</p>
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
