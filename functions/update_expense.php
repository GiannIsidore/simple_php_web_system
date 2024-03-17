<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    exit("Unauthorized access");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
    $expenseName = $_POST['expense_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $purchaseDate = $_POST['purchase_date'];

    // Prepare and execute SQL to update the expense
    $sql = "UPDATE expenses SET product = ?, quantity = ?, price = ?, purchase_date = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sidsi", $expenseName, $quantity, $price, $purchaseDate, $expenseId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Expense updated successfully
        echo "Expense updated successfully";
        header("Location: bgt.php");
    } else {
        // Failed to update expense
        echo "Failed to update expense";
    }

    $stmt->close();
    $conn->close();
}
?>