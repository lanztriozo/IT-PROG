<?php session_start();?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Company</title>
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
        max-width: 800px;
        width: 100%;
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

    .home-fullscreen {
        text-align: center;
        margin-top: 20px; 
    }

    .home-fullscreen img {
        max-width: 25%;
        height: auto;
    }

    .company-container {
        display: flex;
        background-color: #fa89d1;
        max-width: 170px;
        border-radius: 15px;
        /*padding: 10px;*/
        margin-bottom: 20px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        justify-content: space-between;
    }

    .company-form {
    border: 2px solid #eb8dc8; 
    border-radius: 15px;
    padding: 10px;
    background-color: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
    font-size: 16px; 
    font-weight: 700; 
    line-height: 30px; 
    }

    .company-form label {
    margin-bottom: 5px;
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

    .admin-header {
    display: flex;
    justify-content: center;
    text-align: center;
    font-weight: bold;
    margin-bottom: 15px;
    }

    .add-company-container {
        width: 100vw;
        display: flex;
        justify-content: center;
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
    <div class="add-company-container">
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
        <div class="admin-header">
        <h2>Add New Company</h2>
        </div>
        <div class="company-container">
            <div class="company-form">
                <form action="Admin-CompanySubmit.php" method="post">
                    <label for="companyName">Company Name:</label><br>
                    <input type="text" id="companyName" name="companyName" required><br><br>
                    <?php
                        //session will be what tracks the status of a login, whether it failed or not.
                        if (isset($_SESSION['companycreation']) && $_SESSION['companycreation']) {
                            echo '<div style="color: black;">Company Created</div>';
                            unset($_SESSION['companycreation']);
                        }
                    ?>
                    <input class="submit-btn" type="submit" value="Submit">
                </form>
            </div>  
        </div>
    </div>
</body>
</html>
