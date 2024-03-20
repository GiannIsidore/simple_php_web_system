<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(array("error" => "User not logged in"));
    exit;
}


$user_id = $_SESSION['user_id'];


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $stmt = $conn->prepare("SELECT start_date, amount FROM budget WHERE user_id = :user_id ORDER BY start_date DESC");
    $stmt->bindParam(':user_id', $user_id);
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