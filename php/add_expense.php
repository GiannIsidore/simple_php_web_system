<?php
session_start();

$user_id = $_SESSION['user_id'];
try {
    

    

   
    $expenseName = isset($_POST['expense_name']) ? $_POST['expense_name'] : null;
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : null;
    $price = isset($_POST['price']) ? $_POST['price'] : null;
    $purchaseDate = isset($_POST['purchase_date']) ? $_POST['purchase_date'] : null;

    // Check if all required data is present
    if ($expenseName && $quantity && $price && $purchaseDate) {
        // Prepare SQL statement to insert expense into database
        $stmt = $conn->prepare("INSERT INTO expenses (user_id, product, quantity, price, purchase_date)
                                VALUES (:user_id, :product, :quantity, :price, :purchase_date)");

        // You need to set user_id based on your authentication logic
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':product', $expenseName);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':purchase_date', $purchaseDate);
        
        
        $stmt->execute();

        
        echo "Expense added successfully";
    } else {
       
        echo "Error: Missing data";
    }
} catch(PDOException $e) {
    
    echo "Error: " . $e->getMessage();
} finally {
    
    $conn = null;
}
?>