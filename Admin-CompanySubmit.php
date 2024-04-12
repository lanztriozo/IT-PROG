<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$database = "confectionary";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $companyName = $_POST["companyName"];

    
    $sql = "INSERT INTO company (company_name) VALUES (?)";
    
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $companyName);
    
    if ($stmt->execute() === TRUE) {
        $_SESSION['companycreation'] = true;
        //echo "New Company: " . $companyName . "<br>";
        //echo '<a href="Admin-CompanyCreation.php">Return to Company Creation</a>';
        header("Location: Admin-CompanyCreation.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
