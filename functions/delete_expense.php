<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized access");
}


if (!isset($_POST['expense_id'])) {
    exit("Expense ID is missing");
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$expenseId = $_POST['expense_id'];


$sql = "DELETE FROM expenses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expenseId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    
    echo "Expense deleted successfully";
} else {
    
    echo "Failed to delete expense";
}

$stmt->close();
$conn->close();
?>