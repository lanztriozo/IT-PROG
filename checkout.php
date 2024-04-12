<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "confectionary";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$totalPrice = $_POST['total_price'];

$userID = $_SESSION['user_ID'];

$userFundsSQL = "SELECT user_funds FROM `user` WHERE user_ID = $userID";
$userFundsResult = $conn->query($userFundsSQL);

$catalog_ID = $_POST['catalog_ID'];

if ($userFundsResult->num_rows > 0) {
    $row = $userFundsResult->fetch_assoc();
    $userFunds = $row['user_funds'];
    
    if ($userFunds >= $totalPrice) {
        // Subtract total price from user's funds
        $newFunds = $userFunds - $totalPrice;
        $newStock = 49;

        // Update user's funds
        $updateFundsSQL = "UPDATE `user` SET user_funds = $newFunds WHERE user_ID = $userID";
        $conn->query($updateFundsSQL);

        $updateStockSQL = "UPDATE `item` 
        INNER JOIN `catalog` ON `item`.`item_ID` = `catalog`.`item_ID`
        SET `item`.`item_stock` = `item`.`item_stock` - 1
        WHERE `catalog`.`catalog_ID` = $catalog_ID";

        if ($conn->query($updateStockSQL) === TRUE) {
        echo "Item stock updated successfully";
        } else {
        echo "Error updating item stock: " . $conn->error;
        }

        $updateStockSetSQL = "UPDATE `item`
        INNER JOIN (
            SELECT item_ID_1, item_ID_2, item_ID_3, item_ID_4
            FROM `set`
            WHERE set_ID IN (
                SELECT set_ID
                FROM catalog
                WHERE catalog_ID = $catalog_ID
            )
        ) AS set_items ON item.item_ID IN (set_items.item_ID_1, set_items.item_ID_2, set_items.item_ID_3, set_items.item_ID_4)
        SET item.item_stock = item.item_stock - 1";

        // Remove the set from the catalog and sets table
        $removeSetSQL = "DELETE FROM catalog WHERE set_ID IN (SELECT set_ID FROM `set` WHERE set_ID IN (SELECT set_ID FROM catalog WHERE item_ID IS NULL))";
        $conn->query($removeSetSQL);

        // Clear the cart
        $clearCartSQL = "DELETE FROM cart WHERE user_ID = $userID";
        $conn->query($clearCartSQL);

        // Redirect to the cart page with a success message
        $_SESSION['cartpurchase'] = true;
        header("Location: cart.php");
        exit();
    } else {
        // Redirect to the cart page with an error message
        $_SESSION['cartpurchase'] = false;
        header("Location: cart.php");
        exit();
    }
} else {
    // Redirect to the cart page with an error message
    header("Location: cart.php?error=user_not_found");
    exit();
}

// Close connection
$conn->close();
?>
