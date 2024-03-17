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
        // Output each expense as a table row
        echo "<tr>";
        echo "<td>" . $row["product"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["price"] . "</td>";
        echo "<td>" . $row["purchase_date"] . "</td>";
        // Add "Edit" and "Delete" buttons with unique identifiers
        echo "<td>";
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