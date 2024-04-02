<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
    body {
        margin: 0;
    padding: 0;
    }

    .container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 700px;
        width: 100%;
        margin-left: 425px;
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

    .left-sidebar { 
        flex: 1;
        background-color: #333; 
        height: 100vh;
       overflow: hidden;
    }

    .left-sidebar img {
        width: 100%;
        height: auto;
        display: block;
    }
    </style>
</head>
<body>

    <div class ="container">
        <div class="navbar">
            <nav>
                <a href="Admin-CompanyCreation.php">Create Company</a>
                <a href="Admin-ItemCreation.php">Create Items</a>
                <a href="Admin-ItemListing.php">Update Items</a>
                <a href="Admin-UserListing.php">Update Users</a>
            </nav>
        </div>
    </div>
    <div class="home-fullscreen img">
            <img src="admin.jpg" alt="logo" /> 
        </div>

</body>
</html>