<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "confectionary";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if(isset($_POST['delete'])) {
    $user_id = $_POST['user_id'];
    $sql = "DELETE FROM user WHERE user_ID = $user_id";
    $conn->query($sql);
}

if(isset($_POST['update'])) {
    $user_id = $_POST['user_id'];
    
}

$conn->close();
// Redirect back to the listing page
header("Location: Admin-UserListing.php");
exit();
?>
