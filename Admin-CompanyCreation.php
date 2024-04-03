<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Company</title>
    <style>
     body {
     margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    }

    .container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 800px;
        width: 100%;
        margin-left: 365px;
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
    margin-left: 650px;
    max-width: 170px;
    border-radius: 15px;
    padding: 10px;
    margin-bottom: 20px;
    box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
    justify-content: space-between;
    }

    .company-form {
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

    .company-form select, .company-form input[type="submit"] {
    padding: 5px;
    border: none;
    border-radius: 5px;
    margin-bottom: 5px;
    }

    .admin-header {
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
                <a href="adminhome.php">Home</a>
                <a href="Admin-CompanyCreation.php">Create Company</a>
                <a href="Admin-ItemCreation.php">Create Items</a>
                <a href="Admin-ItemListing.php">Update Items</a>
                <a href="Admin-UserListing.php">Update Users</a>
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
            session_start();//session will be what tracks the status of a login, whether it failed or not.
            if (isset($_SESSION['companycreation']) && $_SESSION['companycreation']) {
                echo '<div style="color: black;">Company Created</div>';
                unset($_SESSION['companycreation']);
            }
        ?>
        <input type="submit" value="Submit">
    </form>
    </div>  
    </div>
</body>
</html>
