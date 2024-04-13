<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Item Configuration</title>
    <style>
        body {
            background-color: #f0f5f9;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            width: 100vw;
            height: 100vh;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .item-box {
            border: 2px solid #eb8dc8;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 300px;
            display: inline-block;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            background-color: #FFFFFFFF;
        }

        .update-btn {
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

        .nav-container { /*TWhere navbar is contained so that it doesn't take up entire page */
            text-align: center;
            max-width: 800px;
            width: 100%;
            margin-bottom: 20px;
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

        .user-page-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            flex-direction: column;
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
<div class="user-page-container">
        <div class="nav-container">
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
    <div class="container">
        <?php
        // Database connection
        $servername = "localhost";
        $username   = "root";
        $password   = "";
        $database   = "confectionary";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if(isset($_POST['delete'])) {
            $item_id = $_POST['item_id'];

            $sql_select = "SELECT * FROM item i
                                INNER JOIN company AS c ON i.company_ID = c.company_ID
                                WHERE i.item_ID = $item_id";

            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="item-box">';
                
                echo '<p><strong>Item ID:</strong> '        . $row["item_ID"] .         '</p>';
                echo '<p><strong>Category:</strong> '       . $row["category"] .        '</p>';
                echo '<p><strong>Name:</strong> '           . $row["item_name"] .       '</p>';
                echo '<p><strong>Description:</strong> '    . $row["item_desc"] .       '</p>';
                echo '<p><strong>Company:</strong> '        . $row["company_name"] .    '</p>';
                echo '<p><strong>Price:</strong> $'         . $row["item_price"] .      '</p>';
                echo '<p><strong>Stock:</strong> '          . $row["item_stock"] .      '</p>';

                echo '<p><strong>Item Deleted Successfully</p>';
                
                echo '<a href="Admin-ItemListing.php">Return to Item Listing</a>';
                
                echo '</div>';
            } else {
                echo '<div class="message">Item not found.</div>';
            }

            // Delete the item
            $sql_delete = "DELETE FROM item WHERE item_ID = $item_id";
            if ($conn->query($sql_delete) !== TRUE) {
                echo '<div class="message">Error deleting item: ' . $conn->error . '</div>';
            }

        }

        if(isset($_POST['update'])) {
            $item_id = $_POST['item_id'];
            
            $sql_select = "SELECT * FROM item i
                                INNER JOIN company AS c ON i.company_ID = c.company_ID
                                WHERE i.item_ID = $item_id";

            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="item-box">';
                
                echo '<p><strong>Item ID:</strong> '        . $row["item_ID"] .         '</p>';
                echo '<p><strong>Category:</strong> '       . $row["category"] .        '</p>';
                echo '<p><strong>Name:</strong> '           . $row["item_name"] .       '</p>';
                echo '<p><strong>Description:</strong> '    . $row["item_desc"] .       '</p>';
                echo '<p><strong>Price:</strong> $'         . $row["item_price"] .      '</p>';
                echo '<p><strong>Stock:</strong> '          . $row["item_stock"] .      '</p>';
                echo '<p><strong>Company:</strong> '        . $row["company_name"] .    '</p>'; 

                echo '</div>';
            }

        echo '<div class="item-box">';

        echo '<form method="post" action="Admin-ItemUpdateConfig.php">';
        echo '<input type="hidden" name="item_id" value="' . $row["item_ID"] . '">'; // Keep the user ID hidden but accessible for update



        echo '<br><label for="category">Category:</label>';
        echo '<select id="category" name="category">
                <option value="Cake">Cake</option>
                <option value="Candy">Candy</option>
                <option value="Chocolate">Chocolate</option>
                <option value="Pastry">Pastry</option>
              </select>';
            
        echo '<br><br><label for="item_name">Item Name:</label>';
        echo '<input type="text" id="item_name" name="item_name" value="' . $row["item_name"] . '">';
            
        echo '<br><br><label for="item_desc">Item Description:</label>';
        echo '<input type="text" id="item_desc" name="item_desc" value="' . $row["item_desc"] . '">';
            
        echo '<br><br><label for="item_price">Item Price:</label>';
        echo '<input type="number" id="item_price" name="item_price" value="' . $row["company_name"] . '">';
        
        echo '<br><br><label for="item_stock">Item Stock:</label>';
        echo '<input type="number" id="item_stock" name="item_stock" value="' . $row["category"] . '">';

        echo '<br><br><label for="company">Company:</label>';
        echo '<select id="company" name="company">';

        $sql = "SELECT company_ID, company_name FROM company";
        $result = $conn->query($sql);

        // Populate dropdown options
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<option value='" . $row["company_ID"] . "'>" . $row["company_name"] . "</option>";
            }
        }
            
        echo '<input type="submit" class="update-btn" name="update_submit" value="Update">';
        
        echo '</form>';
            
        echo '</div>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
