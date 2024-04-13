<?php
session_start();

if(isset($_POST['fundsToAdd'])) {
    $fundsToAdd = intval($_POST['fundsToAdd']);
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "confectionary";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $userID = $_SESSION['user_ID'];
    $updateFundsSQL = "UPDATE user SET user_funds = user_funds + $fundsToAdd WHERE user_ID = $userID";
    if ($conn->query($updateFundsSQL) === TRUE) {
        // Fetch updated user funds
        $userFundsSQL = "SELECT user_funds FROM user WHERE user_ID = $userID";
        $userFundsResult = $conn->query($userFundsSQL);
        if ($userFundsResult->num_rows > 0) {
            $userFunds = $userFundsResult->fetch_assoc()["user_funds"];
            $_SESSION['fundsincrease'] = true;
            header("Location: cart.php");
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>