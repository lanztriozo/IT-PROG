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

    .home-fullscreen {
        text-align: center;
        margin-top: 20px; 
    }

    .home-fullscreen img {
        max-width: 100%;
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
                <a href="home.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="set.php">Set</a>
                <a href="cart.php">Cart</a>
            </nav>
        </div>
    </div>
    <div class="home-fullscreen img">
            <img src="sweet.png" alt="logo" /> 
        </div>

</body>
</html>