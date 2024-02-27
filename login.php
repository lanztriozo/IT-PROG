<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "confectionary_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM User WHERE user_name='$username' AND user_password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        header("Location: home.php");
        exit();
    } else {
        echo "Invalid username or password";
    }
}

$conn->close();
?>