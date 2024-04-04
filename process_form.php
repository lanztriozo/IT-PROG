<?php

session_start();
        $userid = isset($_SESSION['user_ID']) ? $_SESSION['user_ID'] : 0;

$host = "localhost";
$username = "root";
$password = "";
$database = "confectionary";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get selected items from the form
$chocolate_id = $_POST['chocolate'];
$pastry_id = $_POST['pastry'];
$cake_id = $_POST['cake'];
$candy_id = $_POST['candy'];

// Calculate set price (10% of the total price of selected items)
$total_price = 0;

$result = mysqli_query($conn, "SELECT item_price FROM Item WHERE item_id IN ($chocolate_id, $pastry_id, $cake_id, $candy_id)");
while ($row = mysqli_fetch_assoc($result)) {
    $total_price += $row['item_price'];
}

$set_price = $total_price * 0.9; // 90% of the total price

// Find the most recent set_id
$latest_set_query = mysqli_query($conn, "SELECT MAX(set_id) AS latest_set_id FROM `Set`");
$latest_set_row = mysqli_fetch_assoc($latest_set_query);
$latest_set_id = $latest_set_row['latest_set_id'];

// Increment the most recent set_id by 1
$new_set_id = $latest_set_id + 1;

// Insert a new instance of the Set with the new_set_id
mysqli_query($conn, "INSERT INTO `Set` (set_id, chocolate_item_id, pastry_item_id, cake_item_id, candy_item_id, set_price) VALUES ($new_set_id, $chocolate_id, $pastry_id, $cake_id, $candy_id, $set_price)");

// Insert a new instance of Catalog with the created Set
$latest_catalog_query = mysqli_query($conn, "SELECT MAX(catalog_id) AS latest_catalog_id FROM Catalog");
$latest_catalog_row = mysqli_fetch_assoc($latest_catalog_query);
$latest_catalog_id = $latest_catalog_row['latest_catalog_id'];
$new_catalog_id = $latest_catalog_id + 1;

mysqli_query($conn, "INSERT INTO Catalog (catalog_id, set_id) VALUES ($new_catalog_id, $new_set_id)");

// Insert a new instance into Cart with user_id and catalog_id
mysqli_query($conn, "INSERT INTO Cart (user_id, catalog_id) VALUES ($userid, $new_catalog_id)");

// Close the database connection
mysqli_close($conn);

// Redirect or perform other actions after successful submission
header("Location: home.php");
exit();
?>