<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List of Items</title>
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        .item-page-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            flex-direction: column;
        }

        .item-box {
            border: 2px solid #eb8dc8;
            border-radius: 5px;
            padding: 10px;
            margin: 10px;
            width: 300px;
            display: inline-block;
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }

        .btn {
            margin-right: 5px;
        }

        .nav-container { /*TWhere navbar is contained so that it doesn't take up entire page */
            text-align: center;
            max-width: 800px;
            width: 100%;
            margin-bottom: 20px;
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

        .update-btn {
            background-color: #34D52F;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }

        .delete-btn {
            background-color: #DE4A38;
            color: white;
            border: none;
            padding: 8px 16px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="item-page-container">
        <div class="nav-container">
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
                    echo '<input type="submit" class="update-btn" name="update" value="Update">';
                    echo '<input type="submit" class="delete-btn" name="delete" value="Delete">';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "0 results";
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>
