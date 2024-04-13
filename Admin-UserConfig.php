<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
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
        $username = "root";
        $password = "";
        $database = "confectionary";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if(isset($_POST['delete'])) {
            $user_id = $_POST['user_id'];

            $sql_select = "SELECT * FROM user WHERE user_ID = $user_id";

            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="item-box">';
                
                echo '<p><strong>User ID:</strong> ' . $row["user_ID"] . '</p>';
                echo '<p><strong>Username:</strong> ' . $row["user_name"] . '</p>';
                echo '<p><strong>User Password:</strong> ' . $row["user_password"] . '</p>';
                echo '<p><strong>Admin:</strong> ' . $row["user_admin"] . '</p>';

                echo '<p><strong>User Deleted Successfully</p>';
                
                echo '<a href="Admin-UserListing.php">Return to User Listing</a>';
                
                echo '</div>';
            } else {
                echo '<div class="message">User not found.</div>';
            }

            // Delete the iUser
            $sql_delete = "DELETE FROM user WHERE user_ID = $user_id";
            if ($conn->query($sql_delete) !== TRUE) {
                echo '<div class="message">Error deleting user: ' . $conn->error . '</div>';
            }

        }

        if(isset($_POST['update'])) {
            $user_id = $_POST['user_id'];
            
            $sql_select = "SELECT * FROM user WHERE user_ID = $user_id";

            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="item-box">';
                
                echo '<p><strong>User ID:</strong> ' . $row["user_ID"] . '</p>';
                echo '<p><strong>Username:</strong> ' . $row["user_name"] . '</p>';
                echo '<p><strong>User Password:</strong> ' . $row["user_password"] . '</p>';
                echo '<p><strong>Admin:</strong> ' . $row["user_admin"] . '</p>';
                
                echo '</div>';
            } else {
                echo '<div class="message">User not found.</div>';
            }

        echo '<div class="item-box">';

        echo '<form method="post" action="Admin-UserUpdateConfig.php">';
        echo '<input type="hidden" name="user_id" value="' . $row["user_ID"] . '">'; // Keep the user ID hidden but accessible for update
            
        echo '<label for="username">Username:</label>';
        echo '<input type="text" id="username" name="username" value="' . $row["user_name"] . '"><br>';
            
        echo '<label for="password">Password:</label>';
        echo '<input type="text" id="password" name="password" value="' . $row["user_password"] . '"><br>';
            
        echo '<label for="admin">Admin:</label>';
        echo '<input type="radio" id="admin_yes" name="admin" value="Y" ' . ($row["user_admin"] == 'Y' ? 'checked' : '') . '>';
        echo '<label for="admin_yes">Yes</label>';
        echo '<input type="radio" id="admin_no" name="admin" value="N" ' . ($row["user_admin"] == 'N' ? 'checked' : '') . '>';
        echo '<label for="admin_no">No</label><br>';
            
        echo '<input type="submit" class="update-btn" name="update_submit" value="Update">';
        echo '</form>';
            
        echo '</div>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>