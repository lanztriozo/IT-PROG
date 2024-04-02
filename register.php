<?php
session_start();
$conn = mysqli_connect("localhost","root", "") or die ("Unable to connect!". mysqli_error());
mysqli_select_db($conn, "confectionary");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = $_POST["new_username"];
    $new_password = $_POST["new_password"];

    $sqlcheck = "SELECT * FROM `user` WHERE user_name='$new_username'";
    $sqlresult = $conn->query($sqlcheck);

    if ($sqlresult->num_rows > 0) {
        $_SESSION['registerfail'] = true;
        header("Location: indexregister.php");
        exit();
    } else {

        $sqlinsert = "INSERT INTO `user` (user_name, user_password, user_admin, user_funds) VALUES ('$new_username', '$new_password', 'N', 0)";
        if ($conn->query($sqlinsert) === TRUE) {
            $_SESSION['registersuccess'] = true;
            header("Location: indexregister.php");
            exit();
        }
    }
}

$conn->close();
?>