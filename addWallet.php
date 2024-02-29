<?php
session_start();
if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "confectionary_db";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['amount'])) {
            $amount = $_POST['amount'];

            $sql = "UPDATE User SET wallet = wallet + $amount WHERE user_id = $userId";
            if ($conn->query($sql) === TRUE) {
                header("Location: cart.php");
                exit();
            } else {
                echo "Error updating wallet: " . $conn->error;
            }
        }
    }
    $conn->close();
} else {
    echo "User ID not found.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add to Wallet</title>
</head>
<body>
    <h2>Add Money to Wallet</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
        <label for="amount">Select amount to add:</label>
        <select name="amount" id="amount">
            <option value="200">200</option>
            <option value="400">400</option>
            <option value="1000">1000</option>
            <option value="2000">2000</option>
            <option value="4000">4000</option>
        </select>
        <input type="submit" value="Add to Wallet">
    </form>
</body>
</html>
