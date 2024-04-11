<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List of Items</title>
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

    .update-btn {
        background-color: #34D52F;
        color: white;
        border: none;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }

    .delete-btn {
        background-color: #DE4A38;
        color: white;
        border: none;
        padding: 8px 16px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
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
<div class ="container">
        <div class="navbar">
            <nav>
                <a href="home.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="set.php">Set</a>
                <a href="cart.php">Cart</a>
                <?php if ($_SESSION['user_admin'] == 'Y'): ?>
                <div class="dropdown">
                    <a href="#" class="dropbtn">Admin</a>
                    <div class="dropdown-content">
                        <a href="Admin-CompanyCreation.php">Create Company</a>
                        <a href="Admin-ItemCreation.php">Create Items</a>
                        <a href="Admin-ItemListing.php">Update Items</a>
                        <a href="Admin-UserListing.php">Update Users</a>
                    </div>
                </div>
                <?php endif; ?>
            </nav>
        </div>
    </div>
    <div class="item-container">
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

        // Fetching items from the database
        $sql = "SELECT * FROM item i
                    INNER JOIN company AS c ON i.company_ID = c.company_ID
                ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="item-box">';

                echo '<p><strong>Item ID:</strong> '        . $row["item_ID"] .         '</p>';
                echo '<p><strong>Category:</strong> '       . $row["category"] .        '</p>';
                echo '<p><strong>Name:</strong> '           . $row["item_name"] .       '</p>';
                echo '<p><strong>Description:</strong> '    . $row["item_desc"] .       '</p>';
                echo '<p><strong>Company:</strong> '        . $row["company_name"] .    '</p>';
                echo '<p><strong>Price:</strong> $'         . $row["item_price"] .      '</p>';
                echo '<p><strong>Stock:</strong> '          . $row["item_stock"] .      '</p>';

                echo '<form action="Admin-ItemConfig.php" method="post">';

                echo '<input type="hidden" name="item_id" value="' . $row["item_ID"] . '">';
                
                echo '<input type="submit" class="update-btn" name="update" value="Update">';
                echo '<input type="submit" class="delete-btn" name="delete" value="Delete">';

                echo '</form>';
                echo '</div>';
            }
        }
        
        $conn->close();
        ?>
    </div>
</body>
</html>