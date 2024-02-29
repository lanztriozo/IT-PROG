<?php
session_start();


        $totalPrice = $_SESSION['total_price'];
        $userID = $_SESSION['user_id'];
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "confectionary_db";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        // Retrieve user's wallet balance
        $sql = "SELECT wallet FROM User WHERE user_id = $userID";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $walletBalance = $row["wallet"];
        }
        if($walletBalance >= $totalPrice){
            $newWallet = $walletBalance - $totalPrice;
            $sql = "UPDATE User SET wallet = $newWallet WHERE user_id = $userID";
            $conn->query($sql);
            $sql = "DELETE FROM Cart WHERE user_id = $userID";
            $conn->query($sql);
            header("home.php");
            exit();
        }
        else{
            echo "<br>Transaction Failed<br>";
            $back = "cart.php";
            echo '<button onclick="window.location.href=\'' . $back . '\';">Go Back</button>';
        }
    