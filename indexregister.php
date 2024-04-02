<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
<style>
   #root {
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
    }

    .signin-fullscreen {
        height: 50vw;
        width: 100vw;
        background-color: pink; 
        justify-content: center;
        align-items: center;
        display: flex;
    }
    

    .form-container {
        display: flex;
        width: 884px; 
        height: 611px; 
        background: #FFFFFFFF; 
        border-radius: 8px; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        flex-direction: row;
    }

    .form-left {
        display: flex;
        width: 50%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background-color: white;
        flex-direction: column;
    }

    .form-right {
        display: flex;
        width: 100%;
        height: 100%;
        justify-content: center;
        align-items: center;
        background-color: white;
        overflow: hidden;
    }

    .sign-text { 
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 32px; 
        font-weight: 700; 
        line-height: 48px; 
        color: #171A1FFF; 
        margin-bottom: 20px;
    }

    .login-form h2 {
        margin-bottom: 20px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group input {
        width: 90%;
        padding: 10px;
        border: 1px solid darkgray; 
        background-color: #F3F4F6FF; 
        border-radius: 4px;
        color: black; 
        display: flex;
    }

    .register-button {
        width: 100%;
        padding: 10px;
        border: none;
        background-color: lightpink; 
        color: black;
        border-radius: 4px;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .back-button {
        width: 63%;
        padding: 10px;
        border: none;
        background-color: lightpink; 
        color: black;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-right img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }
</style>
</head>
<body>
    <div class="signin-fullscreen">
        <div class="form-container">
            <div class="form-left">
                <div class="sign-text">Register</div>
                <form class= "register-form" action="register.php" method="post">
                    <div class="input-group">
                        <label for="new_username">New Username:</label>
                        <input type="text" name="new_username" required><br>
                        <label for="new_password">New Password:</label>
                        <input type="password" name="new_password" required><br>
                        <?php
                            session_start();//session will be what tracks the status of a register, whether it failed or not.
                            if (isset($_SESSION['registerfail']) && $_SESSION['registerfail']) {
                                echo '<div style="color: red;">Username already exists</div>';
                                unset($_SESSION['registerfail']);
                            }
                            if (isset($_SESSION['registersuccess']) && $_SESSION['registersuccess']) {
                                echo '<div style="color: green;">Successful Registration</div>';
                                unset($_SESSION['registersuccess']);
                            }
                            ?>
                    </div>
                    <button type="submit" class="register-button">Register</button>
                </form>
                <button onclick="window.location.href='index.php'" class="back-button">Back</button>
            </div>
            <div class="form-right">
                <img src="sweet.png" alt="logo2" /> 
            </div>
        </div>
    </div>            
</body>
</html>