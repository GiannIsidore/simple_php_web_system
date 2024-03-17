<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized access");
}

// Check if expense_id is provided
if (!isset($_POST['expense_id'])) {
    exit("Expense ID is missing");
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
$expenseId = $_POST['expense_id'];

// Prepare and execute SQL to delete the expense
$sql = "DELETE FROM expenses WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $expenseId);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    // Expense deleted successfully
    echo "Expense deleted successfully";
} else {
    // Failed to delete expense
    echo "Failed to delete expense";
}

$stmt->close();
$conn->close();
?>