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

// Fetching categories
$category_query = "SELECT * FROM category";
$category_result = $conn->query($category_query);

// Fetching companies
$company_query = "SELECT * FROM company";
$company_result = $conn->query($company_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Item</title>
</head>
<body>
    <h2>Add New Item</h2>
    <form action="Admin-ItemProcess.php" method="post">
        <label for="itemName">Item Name:</label><br>
        <input type="text" id="itemName" name="itemName" required><br><br>

        <label for="itemDesc">Item Description:</label><br>
        <input type="text" id="itemDesc" name="itemDesc" required><br><br>

        <label for="itemPrice">Item Price:</label><br>
        <input type="number" id="itemPrice" name="itemPrice" required><br><br>

        <label for="itemStock">Item Stock:</label><br>
        <input type="number" id="itemStock" name="itemStock" required><br><br>

        <label for="category">Category:</label><br>
        <select id="category" name="category" required>
            <option value="">Select Category</option>
            <?php
            if ($category_result->num_rows > 0) {
                while ($row = $category_result->fetch_assoc()) {
                    echo "<option value='" . $row['category_ID'] . "'>" . $row['category_name'] . "</option>";
                }
            }
            ?>
        </select><br><br>

        <label for="company">Company:</label><br>
        <select id="company" name="company" required>
            <option value="">Select Company</option>
            <?php
            if ($company_result->num_rows > 0) {
                while ($row = $company_result->fetch_assoc()) {
                    echo "<option value='" . $row['company_ID'] . "'>" . $row['company_name'] . "</option>";
                }
            }
            ?>
        </select><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
