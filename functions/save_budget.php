<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";
$user_id = $_SESSION['user_id'];
try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $budget = isset($_POST['budget']) ? $_POST['budget'] : null;
    $startDate = isset($_POST['start_date']) ? $_POST['start_date'] : null;
    $endDate = isset($_POST['end_date']) ? $_POST['end_date'] : null;

    
    if ($budget && $startDate && $endDate) {
        
        $stmt = $conn->prepare("INSERT INTO budget (user_id, amount, start_date, end_date)
                                VALUES (:user_id, :amount, :start_date, :end_date)");

        
        
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':amount', $budget);
        $stmt->bindParam(':start_date', $startDate);
        $stmt->bindParam(':end_date', $endDate);
        

        $stmt->execute();


        echo "Budget saved successfully";
    } else {

        echo "Error: Missing data";
    }
} catch(PDOException $e) {
   
    echo "Error: " . $e->getMessage();
} finally {
    
    $conn = null;
}
?>