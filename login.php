<?php
session_start();
$conn = mysqli_connect("localhost","root", "") or die ("Unable to connect!". mysqli_error());
mysqli_select_db($conn, "confectionary");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE user_name='$username' AND user_password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_ID'] = $user['user_ID'];
        $_SESSION['user_admin'] = $user['user_admin']; // Set the session variable for admin status
        header("Location: home.php");
        exit();
    } else {
        $_SESSION['loginfail'] = true;
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>