<?php
session_start();
// Establishing a database connection
$servername = "localhost";
$username = "root";
$password = "";
$database = "confectionary";

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieving form data
    $companyName = $_POST["companyName"];

    // Preparing SQL statement
    $sql = "INSERT INTO company (company_name) VALUES (?)";
    
    // Preparing and binding parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $companyName);
    
    // Executing the statement
    if ($stmt->execute() === TRUE) {
        $_SESSION['companycreation'] = true;
        //echo "New Company: " . $companyName . "<br>";
        //echo '<a href="Admin-CompanyCreation.php">Return to Company Creation</a>';
        header("Location: Admin-CompanyCreation.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Closing statement and connection
    $stmt->close();
    $conn->close();
}
?>
