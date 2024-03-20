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
    die("Connection failed: " . $conn->connect_error);
}


$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM expenses WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    
    while($row = $result->fetch_assoc()) {
        
        echo "<tr id='trnia'>";
        echo "<td class='tdnia'>" . $row["product"] . "</td>";
        echo "<td class='tdnia'>" . $row["quantity"] . "</td>";
        echo "<td class='tdnia'>" . $row["price"] . "</td>";
        echo "<td class='tdnia'>" . $row["purchase_date"] . "</td>";
        
        echo "<td class='tdAct'>";
        echo "<button class='edit-btn' data-expense-id='" . $row["id"] . "'>Edit</button>";
        echo "<button class='delete-btn' data-expense-id='" . $row["id"] . "'>Delete</button>";
        echo "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>No expenses found</td></tr>";
}

$conn->close();
?>