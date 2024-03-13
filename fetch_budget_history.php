<?php
// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";

try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $stmt = $conn->prepare("SELECT start_date, amount FROM budget ORDER BY start_date DESC");

   
    $stmt->execute();

    
    $budgetHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header("Content-Type: application/json");
    echo json_encode($budgetHistory);
} catch(PDOException $e) {
    
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("error" => "Error fetching budget history: " . $e->getMessage()));
}

// Close connection
$conn = null;
?>