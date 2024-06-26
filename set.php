<?php session_start(); ?>
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
        background-color: #f0f5f9;
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        width: 100vw;
        height: 100vh;
    }


    .container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 600px;
        width: 100vw;
        display: flex;
        justify-content: center;
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

    .set-container {
        display: flex;
        background-color: #ffffff; /*White background of container*/
        max-width: 800px;
        border-radius: 15px;
        padding: 10px;
        margin-bottom: 20px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        justify-content: space-between;
        border: 3px solid #eb8dc8;; /* Dark Blue Border */
        color: black;
    }

    .set-form {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 16px; 
        font-weight: 700; 
        line-height: 35px; 
    }

    .set-form label {
    margin-bottom: 5px;
    }

    .set-form select, .set-form input[type="submit"] {
        padding: 5px;
        border: none;
        border-radius: 5px;
        margin-bottom: 5px;
        color: black; /*Color of button text */
        background-color: #eb8dc8;; /*Background of buttons */
    }

    .set-header {
        display: flex;
        justify-content: center;
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .price-container {
        background-color: #ffffff;
        width: 800px;
        border-radius: 15px;
        padding: 10px;
        margin-bottom: 20px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        border: 3px solid #eb8dc8;; 
        color: black;
    }

    .set-page-container {
        height: 100vh;
        width: 100vw;
        display: flex;
        flex-direction: column;
        align-items: center;
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
    <div class="set-page-container">
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
                $result = mysqli_query($conn, "SELECT item_id, item_name, item_price FROM item WHERE category = $category AND item_stock > 0");
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='{$row['item_id']}' data-price='{$row['item_price']}'>{$row['item_name']}</option>";
                }
            }
            ?>
            <div class="set-container">
            <div class="set-form">
            <label for="chocolate">Chocolate:</label>
            <select name="chocolate" id="chocolate" onchange="updatePrice('chocolate')">
                <option>Choose a Chocolate Tooth</option>
                <?php fetchItems(1, $conn); ?>
            </select>
            <span id="chocolate_price"> Price: ₱0.00</span>
            </div>

            <div class="set-form">
            <label for="candy">Candy:</label>
            <select name="candy" id="candy" onchange="updatePrice('candy')">
                <option>Choose a Candy Tooth</option>
                <?php fetchItems(4, $conn); ?>
            </select>
            <span id="candy_price"> Price: ₱0.00</span>
            </div>

            <div class="set-form">
            <label for="cake">Cake:</label>
            <select name="cake" id="cake" onchange="updatePrice('cake')">
                <option>Choose a Cake Tooth</option>
                <?php fetchItems(3, $conn); ?>
            </select> 
            <span id="cake_price"> Price: ₱0.00</span>
            </div>

            <div class="set-form">
            <label for="pastry">Pastry:</label>
            <select name="pastry" id="pastry" onchange="updatePrice('pastry')">
                <option>Choose a Pastry Tooth</option>
                <?php fetchItems(2, $conn); ?>
            </select>
            <span id="pastry_price"> Price: ₱0.00</span>
            </div>
            </div>

            <div class="price-container">
            <div class="set-form">
            <div id="set_price">Total Set Price (90%): ₱0.00</div>
            <input type="submit" value="Submit">
            </div>
            </div>
        </form>
    </div>
    <?php mysqli_close($conn); ?>
</body>
</html>
