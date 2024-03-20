<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['user_id'])) {
    
    exit("Unauthorized access");
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    
    exit("Connection failed: " . $conn->connect_error);
}


$expense_name = isset($_POST['expense-name']) ? trim($_POST['expense-name']) : '';
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : '';
$price = isset($_POST['price']) ? $_POST['price'] : '';


echo "Purchase Date (before conversion): " . $purchase_date . "<br>";


$purchase_date = isset($_POST['purchase-date']) ? $_POST['purchase-date'] : '';
if (!empty($purchase_date)) {
    
    $timestamp = strtotime($purchase_date);
    if ($timestamp === false) {
        exit("Invalid purchase date.");
    }
    
    $purchase_date = date('Y-m-d', $timestamp);
} else {
    exit("Purchase date is required.");
}


echo "Purchase Date (after conversion): " . $purchase_date . "<br>";

$user_id = $_SESSION['user_id'];


if (empty($expense_name) || !is_numeric($quantity) || !is_numeric($price)) {
    exit("Please fill out all fields correctly.");
}

$sql = "INSERT INTO expenses (product, quantity, price, purchase_date, user_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    exit("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("sddsi", $expense_name, $quantity, $price, $purchase_date, $user_id);


if (!$stmt->execute()) {
    
    exit("Error: (" . $stmt->errno . ") " . $stmt->error);
}


echo "<script>window.location.href='bgt.php#add-expense-form'</script>";
exit(); 


$stmt->close();
$conn->close();
?>