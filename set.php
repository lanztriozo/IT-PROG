<html>
<head>
    <title>Set Creation</title>
    <script>
        function updatePrice(category) {
            var selectedItem = document.getElementById(category);
            var priceSpan = document.getElementById(category + "_price");
            var selectedOption = selectedItem.options[selectedItem.selectedIndex];
            var price = parseInt(selectedOption.getAttribute('data-price'));
            priceSpan.innerHTML = " - Price: ₱" + price.toFixed(2);

            calculateTotalPrice();
        }

        function calculateTotalPrice() {
            var chocolatePrice = getPrice('chocolate');
            var candyPrice = getPrice('candy');
            var cakePrice = getPrice('cake');
            var pastryPrice = getPrice('pastry');

            var totalPrice = chocolatePrice + candyPrice + cakePrice + pastryPrice;
            var discountedPrice = 0.9 * totalPrice;

            document.getElementById('set_price').innerHTML = "Total Set Price (90%): ₱" + discountedPrice.toFixed(2);
        }

        function getPrice(category) {
            var selectedItem = document.getElementById(category);
            var selectedOption = selectedItem.options[selectedItem.selectedIndex];
            return parseInt(selectedOption.getAttribute('data-price')) || 0;
        }
    </script>
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
    text-align: center;
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    font-size: 16px; 
    font-weight: 700; 
    line-height: 25px; 
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

    .set-header {
    display: flex;
    justify-content: center;
    text-align: center;
    font-weight: bold;
    margin-bottom: 15px;
    }
    </style>
</head>
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
    <div class="set-header">
    <h2>Select Confectionaries</h2>
    </div>
    <form method="post" action="process_form.php">

        <?php
        $host = "localhost";
        $username = "root";
        $password = "";
        $database = "confectionary";

        $conn = mysqli_connect($host, $username, $password, $database);

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        function fetchItems($category, $conn) {
            $result = mysqli_query($conn, "SELECT item_id, item_name, item_price FROM Item WHERE category_id = $category AND item_stock > 0");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['item_id']}' data-price='{$row['item_price']}'>{$row['item_name']}</option>";
            }
        }
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
        ?>
        
    </form>

    <?php mysqli_close($conn); ?>
</body>
</html>
