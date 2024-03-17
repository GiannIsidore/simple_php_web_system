<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle unauthorized access
    exit("Unauthorized access");
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
    // Redirect to an error page or display a friendly error message
    exit("Connection failed: " . $conn->connect_error);
}

// Retrieve data from POST request and perform basic validation
$expense_name = isset($_POST['expense-name']) ? trim($_POST['expense-name']) : ''; // Trim removes leading and trailing whitespace
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';
$purchase_date = isset($_POST['purchase-date']) ? $_POST['purchase-date'] : '';
$user_id = $_SESSION['user_id'];

// Validate input data (e.g., ensure numeric fields are numeric and the product name is not empty)
if (empty($expense_name) || !is_numeric($quantity) || !is_numeric($price)) {
    exit("Please fill out all fields correctly.");
}

// Insert new expense into database using prepared statement to prevent SQL injection
$sql = "INSERT INTO expenses (product, quantity, price, purchase_date, user_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sdisi", $expense_name, $quantity, $price, $purchase_date, $user_id);

if ($stmt->execute()) {
    // Redirect to the desired page after successful insertion
    header('Location: bgt.php');
    exit(); // Make sure to stop script execution after the redirect
} else {
    // Handle database error (e.g., redirect to an error page or display a message)
    exit("Error: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>