<?php
// Establishing a database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "confectionary";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieving form data
    $itemName = $_POST["itemName"];
    $itemDesc = $_POST["itemDesc"];
    $itemPrice = $_POST["itemPrice"];
    $itemStock = $_POST["itemStock"];
    $categoryID = $_POST["category"];
    $companyID = $_POST["company"];

    // Preparing SQL statement
    $sql = "INSERT INTO item (company_ID, category_ID, item_desc, item_name, item_price, item_stock) VALUES (?, ?, ?, ?, ?, ?)";
    
    // Preparing and binding parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissii", $companyID, $categoryID, $itemDesc, $itemName, $itemPrice, $itemStock);
    
    // Executing the statement
    if ($stmt->execute() === TRUE) {
        echo "Item Name: " . $itemName . "<br>";
        echo "Item Description: " . $itemDesc . "<br>";
        echo "Item Price: " . $itemPrice . "<br>";
        echo "Item Stock: " . $itemStock . "<br>";
        echo '<a href="Admin-ItemCreation.php">Return to Item Creation</a>';
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Closing statement and connection
    $stmt->close();
    $conn->close();
}
?>
