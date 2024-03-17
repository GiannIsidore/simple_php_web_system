<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized access");
}

if (!isset($_GET['expense_id'])) {
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

// Fetch expense details
$expenseId = $_GET['expense_id'];
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM expenses WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $expenseId, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    // Expense found, display edit form
    $expense = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Expense</title>
    <link rel="stylesheet" href="../css/edit_ex.css">
</head>

<body>
    <div id="container">
        <h2>Edit Expense</h2>
        <form action="update_expense.php" method="POST">
            <input type="hidden" name="expense_id" value="<?php echo $expense['id']; ?>">
            Expense Name: <input type="text" name="expense_name" value="<?php echo $expense['product']; ?>"><br>
            Quantity: <input type="number" name="quantity" value="<?php echo $expense['quantity']; ?>"><br>
            Price: <input type="number" name="price" value="<?php echo $expense['price']; ?>"><br>
            Purchase Date: <input type="date" name="purchase_date" value="<?php echo $expense['purchase_date']; ?>"><br>
            <input type="submit" value="Update Expense"><br>


        </form>
    </div>

</body>


</html>
<?php
} else {
    // Expense not found or unauthorized access
    exit("Expense not found or unauthorized access");
}

$stmt->close();
$conn->close();
?>