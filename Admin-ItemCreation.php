
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
    <style>
         body {
     margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    }

    .container { /*TWhere navbar is contained so that it doesn't take up entire page */
        text-align: center;
        max-width: 800px;
        width: 100%;
    }

    .add-item-container {
        width: 100vw;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
    }
    
    .navbar { /*Navigation Bar for Home, Shop, Set, Cart */
        text-align: center;
        font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        font-size: 16px; 
        font-weight: 700; 
        line-height: 25px; 
        background-color: #fa89d1;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        border-radius: 50px;
        margin-top: 40px; 
    }

    nav {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    nav a {
        text-decoration: none;
        margin: 0 15px; 
        padding: 10px;
    }

    .home-fullscreen {
        text-align: center;
        margin-top: 20px; 
    }

    .home-fullscreen img {
        max-width: 25%;
        height: auto;
    }

    .item-container {
        display: flex;
        background-color: #fa89d1;
        width: 50%;
        border-radius: 15px;
        padding: 10px;
        margin-bottom: 20px;
        box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
        justify-content: space-between;
    }

    form {
            width: 98%;
        }
    
    form input[type="text"],
    form input[type="number"],
    form select {
            width: 100%;
            padding: 5px;
            border: none;
            border-radius: 5px;
            margin-bottom: 5px;
        }

    .itemcreation-header {
    display: flex;
    justify-content: center;
    text-align: center;
    font-weight: bold;
    margin-bottom: 15px;
    }
    </style>
</head>
<body>
    <div class="add-item-container">
        <div class ="container">
            <div class="navbar">
                <nav>
                    <a href="adminhome.php">Home</a>
                    <a href="Admin-CompanyCreation.php">Create Company</a>
                    <a href="Admin-ItemCreation.php">Create Items</a>
                    <a href="Admin-ItemListing.php">Update Items</a>
                    <a href="Admin-UserListing.php">Update Users</a>
                </nav>
            </div>
        </div>
        <div class="itemcreation-header">
        <h2>Add New Item</h2>
        </div>
        <div class="item-container">
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
            </div>
            <div class="item-container">
            <input type="submit" value="Submit">
            </div>
        </form>
    </div>
</body>
</html>
