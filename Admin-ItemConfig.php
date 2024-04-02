<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Item</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .item-box {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            width: 300px;
            display: inline-block;
        }

        .btn {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
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
            $item_id = $_POST['item_id'];

            $sql_select = "SELECT * FROM item i
                                INNER JOIN company AS c ON i.company_ID = c.company_ID
                                INNER JOIN category AS cat ON i.category_ID = cat.category_ID
                                WHERE i.item_ID = $item_id";

            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="item-box">';
                
                echo '<p><strong>Category:</strong> ' . $row["category_name"] . '</p>';
                echo '<p><strong>Item ID:</strong> ' . $row["item_ID"] . '</p>';
                echo '<p><strong>Name:</strong> ' . $row["item_name"] . '</p>';
                echo '<p><strong>Description:</strong> ' . $row["item_desc"] . '</p>';
                echo '<p><strong>Company:</strong> ' . $row["company_name"] . '</p>';
                echo '<p><strong>Price:</strong> $' . $row["item_price"] . '</p>';
                echo '<p><strong>Stock:</strong> ' . $row["item_stock"] . '</p>';

                echo '<p><strong>Item Deleted Successfully</p>';
                
                echo '<a href="Admin-ItemListing.php">Return to Item Listing</a>';
                
                echo '</div>';
            } else {
                echo '<div class="message">Item not found.</div>';
            }

            // Delete the item
            $sql_delete = "DELETE FROM item WHERE item_ID = $item_id";
            if ($conn->query($sql_delete) !== TRUE) {
                echo '<div class="message">Error deleting item: ' . $conn->error . '</div>';
            }

        }

        if(isset($_POST['update'])) {
            $item_id = $_POST['item_id'];
            
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
