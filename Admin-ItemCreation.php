<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Creation Form</title>
</head>
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
        width: 100%;
        margin-bottom: 10px;
        margin-left: 400px;
    }
    
    .submit-btn {
            background-color: #5071e6;
            color: white;
            border: none;
            padding: 8px 10px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 15px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
    }

    .navbar { /* Navigation Bar for Home, Shop, Set, Cart */
        text-align: center;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 16px; 
        font-weight: 700; 
        line-height: 25px; 
        background-color: #fa89d1;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        border-radius: 50px;
        margin-top: 10px; 
        margin-left: 10px;
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

    .item-container {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-around;
    width: 80%;
    margin-top: 20px;
    overflow-y: auto;
    flex-direction: row;
    margin-left: 120px;
    }

    .item-box {
    width: 25%;
    background-color: #ffffff; 
    border: 2px solid #eb8dc8; 
    border-radius: 8px;
    margin: 10px;
    padding: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    overflow-y: auto;
    }

    .item-header {
        display: flex;
        justify-content: center;
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
    }s

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
    <div class= "item-header">
    <h2>Item Creation Form</h2>
    </div>
    <div class = "item-container">
    <div class = "item-box">
    <form action="Admin-ItemProcess.php" method="POST">
        <label for="item_name">Item Name:</label><br>
        <input type="text" id="item_name" name="item_name"><br><br>

        <label for="item_desc">Item Description:</label><br>
        <input type="text" id="item_desc" name="item_desc"><br><br>

        <label for="item_price">Item Price:</label><br>
        <input type="number" id="item_price" name="item_price"><br><br>

        <label for="item_stock">Item Stock:</label><br>
        <input type="number" id="item_stock" name="item_stock"><br><br>

        <label for="category">Category:</label><br>
        <select id="category" name="category">
            <option value="Cake">Cake</option>
            <option value="Candy">Candy</option>
            <option value="Chocolate">Chocolate</option>
            <option value="Pastry">Pastry</option>
        </select><br><br>

        <label for="company">Company:</label><br>
        <select id="company" name="company">
            <?php
            $servername = "localhost";
            $username   = "root";
            $password   = "";
            $database   = "confectionary";

            $conn = new mysqli($servername, $username, $password, $database);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to fetch company names
            $sql = "SELECT company_ID, company_name FROM company";
            $result = $conn->query($sql);

            // Populate dropdown options
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["company_ID"] . "'>" . $row["company_name"] . "</option>";
                }
            }
            $conn->close();
            ?>
            <?php
                if (isset($_SESSION['itemcreation']) && $_SESSION['itemcreation']) {
                echo '<div style="color: black;">Item Created</div>';
                unset($_SESSION['itemcreation']);
                }
            ?>
        </select><br><br>

        <input class="submit-btn" type="submit" value="Create Item">
    </form>
    </div>
    </div>

</body>
</html>