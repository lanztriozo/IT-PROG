<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List of Users</title>
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
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 300px;
            float: left;
            background-color: #ffffff;
            border: 2px solid #eb8dc8;
            border-radius: 5px;
            display: inline-block;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
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
    function getItemName($conn, $itemID) {
        $Setquery = "SELECT item_name FROM item WHERE item_ID = $itemID";
        $result = $conn->query($Setquery);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["item_name"];
        } else {
            return "NULL";
        }
    }

    function CatalogItemName($conn, $catalogID) {
        $query = "SELECT item.item_name
                  FROM item
                  INNER JOIN catalog ON item.item_ID = catalog.item_ID
                  WHERE catalog.catalog_ID = $catalogID";
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row["item_name"];
        } else {
            return "NULL";
        }
    }

    // get user id
    $userID = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : 0;
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "confectionary";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $Setquery = "SELECT reports.report_ID, reports.user_ID, reports.catalog_ID, reports.quantity, reports.price, item.item_name, user.user_name, set_table.item_ID_1, set_table.item_ID_2, set_table.item_ID_3, set_table.item_ID_4, catalog.set_ID
    FROM reports
    INNER JOIN item ON reports.catalog_ID = item.item_ID
    INNER JOIN user ON reports.user_ID = user.user_ID
    INNER JOIN catalog ON reports.catalog_ID = catalog.catalog_ID
    INNER JOIN `set` AS set_table ON catalog.set_ID = set_table.set_ID
    WHERE reports.user_ID = $userID";

    $result = $conn->query($Setquery);

if ($result->num_rows > 0) {
    $set_items = array();

    while ($row = $result->fetch_assoc()) {
        $set_id = $row['set_ID']; 
        if (!isset($set_items[$set_id])) {
            $set_items[$set_id] = array();
        }
        $set_items[$set_id][] = $row;
    }

    foreach ($set_items as $set_id => $items) {
        echo '<div class="item-container">';
        foreach ($items as $item) {
            if ($item['set_ID']) {
                // If set, display all items found in set
                echo '<div class="item-box">';
                echo '<h3>User Name: ' . $item["user_name"] . '</h3>';
                echo '<p>Set ID: ' . $item["set_ID"] . '</p>';
                echo '<p>Set Items:</p>';
                echo '<ul>';
                echo '<li>' . getItemName($conn, $item["item_ID_1"]) . '</li>';
                echo '<li>' . getItemName($conn, $item["item_ID_2"]) . '</li>';
                echo '<li>' . getItemName($conn, $item["item_ID_3"]) . '</li>';
                echo '<li>' . getItemName($conn, $item["item_ID_4"]) . '</li>';
                echo '</ul>';
                echo '<p>Quantity: ' . $item["quantity"] . '</p>';
                echo '<p>Price: ₱' . $item["price"] . '</p>';
                echo '</div>';
                }
            }
            echo '</div>';
        }
    } else {
        echo "";
    }

    $query = "SELECT reports.report_ID, reports.user_ID, reports.catalog_ID, reports.quantity, reports.price, item.item_name, user.user_name, catalog.item_ID
    FROM reports
    INNER JOIN item ON reports.catalog_ID = item.item_ID
    INNER JOIN user ON reports.user_ID = user.user_ID
    INNER JOIN catalog ON reports.catalog_ID = catalog.catalog_ID
    WHERE reports.user_ID = $userID";

    $itemresult = $conn->query($query);

    if ($itemresult->num_rows > 0) {
        echo '<div class="item-container">';
        while ($row = $itemresult->fetch_assoc()) {
            echo '<div class="item-box">
                    <h3>User Name: ' . $row["user_name"] . '</h3>
                    <p>User ID: ' . $row["user_ID"] . '</p>
                    <p>Item ID: ' . $row["item_ID"] . '</p>
                    <p>Item Name: ' . CatalogItemName($conn, $row["catalog_ID"]) . '</p>
                    <p>Catalog ID: ' . $row["catalog_ID"] . '</p>
                    <p>Quantity: ' . $row["quantity"] . '</p>
                    <p>Price: ₱' . $row["price"] . '</p>
                </div>';
        }
        echo '</div>';
    } else {
        echo "";
    }

$conn->close();

?>
</body>
</html>