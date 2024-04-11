<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $item_name = $_POST["item_name"];
    $item_desc = $_POST["item_desc"];
    $item_price = $_POST["item_price"];
    $item_stock = $_POST["item_stock"];
    $category = $_POST["category"];
    $company_ID = $_POST["company"];

    // Connect to the database
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $database   = "confectionary";

    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement to insert data into the item table
    $sql = "INSERT INTO item (company_ID, category, item_desc, item_name, item_price, item_stock) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute the statement
    $stmt->bind_param("isssii", $company_ID, $category, $item_desc, $item_name, $item_price, $item_stock);
    if ($stmt->execute()) {
        echo "Item created successfully.";
    }
        
    $stmt->close();
    $conn->close();
}
?>