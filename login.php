<?php
session_start();
$conn = mysqli_connect("localhost","root", "") or die ("Unable to connect!". mysqli_error());
        mysqli_select_db($conn, "confectionary");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM User WHERE user_name='$username' AND user_password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        session_start();
        if ($user['user_admin'] == 'N') {
            $_SESSION['user_id'] = $result->fetch_assoc()['user_id'];
            header("Location: home.php");
            exit();
        } elseif ($user['user_admin'] == 'Y') {
            header("Location: adminhome.php");
            exit();
        }
    } else {
        //tracks what session passed and if credentials didn't match then loginfail becomes true then the red text displays
        $_SESSION['loginfail'] = true;
        header("Location: index.php");
        exit();
    }
}

$conn->close();
?>