<html>
<head>
    <title>Set Creation</title>
</head>
<body>

<h2>Select Confectionaries</h2>

<form method="post" action="process_form.php">
    <?php
 
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "confectionary_db";

    // Create connection
    $conn = mysqli_connect($host, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Function to fetch items based on category
    function fetchItems($category, $conn) {
        $result = mysqli_query($conn, "SELECT item_id, item_name, item_price FROM Item WHERE category_id = $category AND item_stock > 0");
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['item_id']}' data-price='{$row['item_price']}'>{$row['item_name']}</option>";
        }
    }
    ?>

    <label for="chocolate">Chocolate:</label>
    <select name="chocolate" id="chocolate" onchange="updatePrice('chocolate')">
        <option>Choose a Chocolate Tooth</option>
        <?php fetchItems(1, $conn); ?>
    </select>
    <span id="chocolate_price"></span>
    <br>

    <label for="candy">Candy:</label>
    <select name="candy" id="candy" onchange="updatePrice('candy')">
        <option>Choose a Candy Tooth</option>
        <?php fetchItems(4, $conn); ?>
    </select>
    <span id="candy_price"></span>
    <br>

    <label for="cake">Cake:</label>
    <select name="cake" id="cake" onchange="updatePrice('cake')">
        <option>Choose a Cake Tooth</option>
        <?php fetchItems(3, $conn); ?>
    </select> 
    <span id="cake_price"></span>
    <br>

    <label for="pastry">Pastry:</label>
    <select name="pastry" id="pastry" onchange="updatePrice('pastry')">
        <option>Choose a Pastry Tooth</option>
        <?php fetchItems(2, $conn); ?>
    </select>
    <span id="pastry_price"></span>
    <br>

    <input type="submit" value="Submit">
</form>

<script>
function updatePrice(category) {
    var selectedItem = document.getElementById(category);
    var priceSpan = document.getElementById(category + "_price");
    var selectedOption = selectedItem.options[selectedItem.selectedIndex];
    var price = selectedOption.getAttribute('data-price');
    priceSpan.innerHTML = " - Price: $" + price;
}
</script>

<?php
// Close the database connection
mysqli_close($conn);
?>

</body>
</html>
