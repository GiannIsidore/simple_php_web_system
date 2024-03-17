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
    die("Connection failed: " . $conn->connect_error);
}

// Fetch expenses for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM expenses WHERE user_id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        // Generate HTML code for each expense
        echo "<tr>";
        echo "<td>" . $row["product"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "<td>" . $row["purchase_date"] . "</td>";
        echo "</tr>";
    }
} else {
    // If no expenses found, display a message
    echo "<tr><td colspan='4'>No expenses found</td></tr>";
}
$conn->close();
?>