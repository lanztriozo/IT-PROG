<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $item_name = $_POST["item_name"];
    $item_desc = $_POST["item_desc"];
    $item_price = $_POST["item_price"];
    $item_stock = $_POST["item_stock"];
    $category = $_POST["category"];
    $company_ID = $_POST["company"];

    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $database   = "confectionary";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO item (company_ID, category, item_desc, item_name, item_price, item_stock) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $stmt->bind_param("isssii", $company_ID, $category, $item_desc, $item_name, $item_price, $item_stock);
    if ($stmt->execute() === TRUE) {
        $_SESSION['itemcreation'] = true;
        header("Location: Admin-ItemCreation.php");
    }
        
    $stmt->close();
    $conn->close();
}
?>