<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "confectionary";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['remove'])) {
    $userID = $_POST['user_ID'];
    $catalogID = $_POST['catalog_ID'];

    // SQL to delete cart item
    $sql = "DELETE FROM cart WHERE user_ID = $userID AND catalog_ID = '$catalogID'";

    if ($conn->query($sql) === TRUE) {
        echo "Item removed from cart successfully";
    } else {
        echo "Error removing item from cart: " . $conn->error;
    }
}

header("Location: cart.php");
exit();

$conn->close();
?>
