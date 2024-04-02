<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List of Items</title>
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

        // Fetching items from the database
        $sql = "SELECT * FROM item i
                    INNER JOIN company AS c ON i.company_ID = c.company_ID
                    INNER JOIN category AS cat ON i.category_ID = cat.category_ID;
                ";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="item-box">';

                echo '<p><strong>Category:</strong> ' . $row["category_name"] . '</p>';
                echo '<p><strong>Item ID:</strong> ' . $row["item_ID"] . '</p>';
                echo '<p><strong>Name:</strong> ' . $row["item_name"] . '</p>';
                echo '<p><strong>Description:</strong> ' . $row["item_desc"] . '</p>';
                echo '<p><strong>Company:</strong> ' . $row["company_name"] . '</p>';
                echo '<p><strong>Price:</strong> $' . $row["item_price"] . '</p>';
                echo '<p><strong>Stock:</strong> ' . $row["item_stock"] . '</p>';

                echo '<form action="Admin-ItemConfig.php" method="post">';
                echo '<input type="hidden" name="item_id" value="' . $row["item_ID"] . '">';
                echo '<input type="submit" class="btn" name="update" value="Update">';
                echo '<input type="submit" class="btn" name="delete" value="Delete">';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>
</body>
</html>
