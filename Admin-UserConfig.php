<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
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
            $user_id = $_POST['user_id'];

            $sql_select = "SELECT * FROM user WHERE user_ID = $user_id";

            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="item-box">';
                
                echo '<p><strong>User ID:</strong> ' . $row["user_ID"] . '</p>';
                echo '<p><strong>Username:</strong> ' . $row["user_name"] . '</p>';
                echo '<p><strong>User Password:</strong> ' . $row["user_password"] . '</p>';
                echo '<p><strong>Admin:</strong> ' . $row["user_admin"] . '</p>';

                echo '<p><strong>User Deleted Successfully</p>';
                
                echo '<a href="Admin-UserListing.php">Return to User Listing</a>';
                
                echo '</div>';
            } else {
                echo '<div class="message">User not found.</div>';
            }

            // Delete the iUser
            $sql_delete = "DELETE FROM user WHERE user_ID = $user_id";
            if ($conn->query($sql_delete) !== TRUE) {
                echo '<div class="message">Error deleting user: ' . $conn->error . '</div>';
            }

        }

        if(isset($_POST['update'])) {
            $user_id = $_POST['user_id'];
            
            $sql_select = "SELECT * FROM user WHERE user_ID = $user_id";

            $result = $conn->query($sql_select);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo '<div class="item-box">';
                
                echo '<p><strong>User ID:</strong> ' . $row["user_ID"] . '</p>';
                echo '<p><strong>Username:</strong> ' . $row["user_name"] . '</p>';
                echo '<p><strong>User Password:</strong> ' . $row["user_password"] . '</p>';
                echo '<p><strong>Admin:</strong> ' . $row["user_admin"] . '</p>';
                
                echo '</div>';
            } else {
                echo '<div class="message">User not found.</div>';
            }

        echo '<div class="item-box">';

        echo '<form method="post" action="Admin-UserUpdateConfig.php">';
        echo '<input type="hidden" name="user_id" value="' . $row["user_ID"] . '">'; // Keep the user ID hidden but accessible for update
            
        echo '<label for="username">Username:</label>';
        echo '<input type="text" id="username" name="username" value="' . $row["user_name"] . '"><br>';
            
        echo '<label for="password">Password:</label>';
        echo '<input type="text" id="password" name="password" value="' . $row["user_password"] . '"><br>';
            
        echo '<label for="admin">Admin:</label>';
        echo '<input type="radio" id="admin_yes" name="admin" value="Y" ' . ($row["user_admin"] == 'Y' ? 'checked' : '') . '>';
        echo '<label for="admin_yes">Yes</label>';
        echo '<input type="radio" id="admin_no" name="admin" value="N" ' . ($row["user_admin"] == 'N' ? 'checked' : '') . '>';
        echo '<label for="admin_no">No</label><br>';
            
        echo '<input type="submit" name="update_submit" value="Update">';
        echo '</form>';
            
        echo '</div>';
        }

        $conn->close();
        ?>
    </div>
</body>
</html>