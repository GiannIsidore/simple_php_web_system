<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Return an error response indicating that the user is not logged in
    header("HTTP/1.1 401 Unauthorized");
    echo json_encode(array("error" => "User not logged in"));
    exit;
}

// Get the logged-in user's ID
$user_id = $_SESSION['user_id'];

// Establish database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Modify the SQL query to filter records based on user ID
    $stmt = $conn->prepare("SELECT start_date, amount FROM budget WHERE user_id = :user_id ORDER BY start_date DESC");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $budgetHistory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Set response header to indicate JSON content type
    header("Content-Type: application/json");
    echo json_encode($budgetHistory);
} catch(PDOException $e) {
    // Return an error response if an exception occurs
    header("HTTP/1.1 500 Internal Server Error");
    echo json_encode(array("error" => "Error fetching budget history: " . $e->getMessage()));
}

// Close connection
$conn = null;
?>