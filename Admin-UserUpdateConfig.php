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
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $admin = $_POST['admin'];

    // Check if any field has been updated
    $sql_select = "SELECT user_name, user_password, user_admin FROM user WHERE user_ID = $user_id";
    $result = $conn->query($sql_select);
    $row = $result->fetch_assoc();

    $current_username = $row['user_name'];
    $current_password = $row['user_password'];
    $current_admin = $row['user_admin'];

    $update_query = "";

    if ($username != $current_username) {
        $update_query .= "user_name='$username', ";
    }
    if ($password != $current_password) {
        $update_query .= "user_password='$password', ";
    }
    if ($admin != $current_admin) {
        $update_query .= "user_admin='$admin', ";
    }

    $update_query = rtrim($update_query, ', ');

    if (!empty($update_query)) {
        $sql_update = "UPDATE user SET $update_query WHERE user_ID = $user_id";

        if ($conn->query($sql_update) === TRUE) {
            echo '<div class="container">';
            echo '<div class="item-box">';
            echo '<p><strong>User ID:</strong> ' . $user_id . '</p>';
            echo '<p><strong>Username:</strong> ' . $username . '</p>';
            echo '<p><strong>User Password:</strong> ' . $password . '</p>';
            echo '<p><strong>Admin:</strong> ' . $admin . '</p>';
            echo '<p>User information updated successfully.</p>';
            echo '<a href="Admin-UserListing.php">Return to User Listing</a>';
            echo '</div>';
            echo '</div>';
        } else {
            echo '<div class="message">Error updating user information: ' . $conn->error . '</div>';
        }
    } else {
        echo '<div class="message">No changes made.</div>';
    }
}

$conn->close();
?>