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

$userID = $_SESSION['user_ID'];

$cartSQL = "SELECT 
                CASE 
                    WHEN item.item_name IS NOT NULL THEN item.item_name
                    ELSE CONCAT_WS(', ', 
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_1),
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_2),
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_3),
                            (SELECT item_name FROM item WHERE item_ID = `set`.item_ID_4)
                         )
                END AS item_name,
                CASE 
                    WHEN item.item_price IS NOT NULL THEN item.item_price
                    ELSE `set`.set_price
                END AS item_price,
                cart.quantity, 
                cart.user_ID,
                cart.catalog_ID 
            FROM cart 
            LEFT JOIN catalog ON cart.catalog_ID = catalog.catalog_ID
            LEFT JOIN item ON catalog.item_ID = item.item_ID
            LEFT JOIN `set` ON catalog.set_ID = `set`.set_ID 
            WHERE cart.user_ID = $userID";
$cartResult = $conn->query($cartSQL);

$totalPrice = $_POST['total_price'];

$cartItems = array();
if ($cartResult->num_rows > 0) {
    while ($row = $cartResult->fetch_assoc()) {
        $cartItems[] = $row;
        $catalog_ID = $row['catalog_ID'];
        // Calculate total price
        $totalPrice += $row["item_price"] * $row["quantity"];
    }
}

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

        if ($conn->query($updateStockSetSQL) === TRUE) {
            echo "Item stock updated successfully";
            } else {
            echo "Error updating item stock: " . $conn->error;
            }

            //Insert into reports sql
            $userID = $_SESSION['user_ID'];
            $reportSQL = "INSERT INTO `reports` (user_ID, catalog_ID, quantity, price) VALUES ";

            foreach ($cartItems as $item) {
                $itemID = isset($item['item_ID']) ? $item['item_ID'] : null;
                $setID = isset($item['set_ID']) ? $item['set_ID'] : null;
                $catalogID = $item['catalog_ID'];
                $quantity = $item['quantity'];
                $price = $item['item_price'];
    
                // If the item is a set, the price needs to be fetched from the set table
                if ($setID !== null) {
                    $setPriceSQL = "SELECT set_price FROM `set` WHERE set_ID = $setID";
                    $setPriceResult = $conn->query($setPriceSQL);
                    if ($setPriceResult->num_rows > 0) {
                        $price = $setPriceResult->fetch_assoc()['set_price'];
                    }
                }
    
                $reportSQL .= "($userID, $catalogID, $quantity, $price),";
            }
    
            $reportSQL = rtrim($reportSQL, ",");
    
            if ($conn->query($reportSQL) === TRUE) {
                echo "Report created successfully";
            } else {
                echo "Error creating report: " . $conn->error;
            }

        // remove set from the catalog and sets table
        $clearCartSQL = "DELETE FROM cart WHERE user_ID = $userID";
        $conn->query($clearCartSQL);

        $_SESSION['cartpurchase'] = true;
        header("Location: cart.php");
        exit();
    } else {
        $_SESSION['cartpurchase'] = false;
        header("Location: cart.php");
        exit();
    }
} else {
    header("Location: cart.php?error=user_not_found");
    exit();
}

$conn->close();
?>
