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

if(isset($_POST['update_submit'])) {
    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $item_desc = $_POST['item_desc'];
    $item_price = $_POST['item_price'];
    $item_stock = $_POST['item_stock'];
    $category = $_POST['category'];
    $company = $_POST['company'];

    // Check if any field has been updated
    $sql_select = "SELECT company_ID, category, item_desc, item_name, item_price, item_stock FROM item WHERE item_ID = $item_id";
    $result = $conn->query($sql_select);
    $row = $result->fetch_assoc();

    $curcompany_ID = $row['company_ID'];
    $curcategory = $row['category'];
    $curitem_desc = $row['item_desc'];
    $curitem_name = $row['item_name'];
    $curitem_price = $row['item_price'];
    $curitem_stock = $row['item_stock'];

    $update_query = "";

    if ($company != $curcompany_ID) {
        $update_query .= "company_ID='$company', ";
    }
    if ($category != $curcategory) {
        $update_query .= "category='$category', ";
    }
    if ($item_desc != $curitem_desc) {
        $update_query .= "item_desc='$item_desc', ";
    }
    if ($item_name != $curitem_name) {
        $update_query .= "item_name='$item_name', ";
    }
    if ($item_price != $curitem_price) {
        $update_query .= "item_price='$item_price', ";
    }
    if ($item_stock != $curitem_stock) {
        $update_query .= "item_stock='$item_stock', ";
    }

    $update_query = rtrim($update_query, ', ');

    if (!empty($update_query)) {
        $sql_update = "UPDATE item SET $update_query WHERE item_ID = $item_id";
        $company_select = "SELECT company_name FROM company WHERE company_ID = $company";
        $companyresult = $conn->query($company_select);
        if ($companyresult->num_rows > 0) {
            while($extrarow = $companyresult->fetch_assoc()) {
                    $company_name = $extrarow["company_name"];
                }
            }

        if ($conn->query($sql_update) === TRUE) {
            echo '<div class="container">';
            echo '<div class="item-box">';
            echo '<p><strong>Item ID:</strong> ' . $item_id . '</p>';
            echo '<p><strong>Item Name:</strong> ' . $item_name . '</p>';
            echo '<p><strong>Item Description:</strong> ' . $item_desc . '</p>';
            echo '<p><strong>Item Price:</strong> ' . $item_price . '</p>';
            echo '<p><strong>Item Stock:</strong> ' . $item_stock . '</p>';
            echo '<p><strong>Item Category:</strong> ' . $category . '</p>';
            echo '<p><strong>Company:</strong> ' . $company_name . '</p>';
            echo '<p>User information updated successfully.</p>';
            echo '<a href="Admin-UserListing.php">Return to User Listing</a>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="message">Error updating item information: ' . $conn->error . '</div>';
        }
    } else {
        echo '<div class="message">No changes made.</div>';
    }
}

$conn->close();
?>
