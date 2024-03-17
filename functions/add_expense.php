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
$expense_name = isset($_POST['expense-name']) ? trim($_POST['expense-name']) : '';
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';

// Debugging: Output the value of purchase_date before and after conversion
echo "Purchase Date (before conversion): " . $purchase_date . "<br>";

// Validate and format the purchase date
$purchase_date = isset($_POST['purchase-date']) ? $_POST['purchase-date'] : '';
if (!empty($purchase_date)) {
    // Validate the date format
    $timestamp = strtotime($purchase_date);
    if ($timestamp === false) {
        exit("Invalid purchase date.");
    }
    // Convert to MySQL date format (YYYY-MM-DD)
    $purchase_date = date('Y-m-d', $timestamp);
} else {
    exit("Purchase date is required.");
}

// Debugging: Output the value of purchase_date after conversion
echo "Purchase Date (after conversion): " . $purchase_date . "<br>";

$user_id = $_SESSION['user_id'];

// Validate input data (e.g., ensure numeric fields are numeric and the product name is not empty)
if (empty($expense_name) || !is_numeric($quantity) || !is_numeric($price)) {
    exit("Please fill out all fields correctly.");
}

// Insert new expense into database using prepared statement to prevent SQL injection
$sql = "INSERT INTO expenses (product, quantity, price, purchase_date, user_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    exit("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("sddsi", $expense_name, $quantity, $price, $purchase_date, $user_id);


if (!$stmt->execute()) {
    // Handle database error (e.g., redirect to an error page or display a message)
    exit("Error: (" . $stmt->errno . ") " . $stmt->error);
}

// Redirect to the desired page after successful insertion
echo "<script>window.location.href='bgt.php#add-expense-form'</script>";
exit(); // Make sure to stop script execution after the redirect

// Close the statement and database connection
$stmt->close();
$conn->close();
?>