<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin List of Users</title>
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
            float: left;
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
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo '<div class="item-box">';

                echo '<p><strong>User ID:</strong> ' . $row["user_ID"] . '</p>';
                echo '<p><strong>Username:</strong> ' . $row["user_name"] . '</p>';
                echo '<p><strong>User Password:</strong> ' . $row["user_password"] . '</p>';
                echo '<p><strong>Admin Verification:</strong> ' . $row["user_admin"] . '</p>';
                
                echo '<form action="Admin-UserConfig.php" method="post">';
                echo '<input type="hidden" name="user_id" value="' . $row["user_ID"] . '">';
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
