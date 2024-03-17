<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "db_bgt"; // Replace with your database name

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    // Set PDO attributes for error mode and emulated prepared statements
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    // If connection fails, display error message
    echo "Connection failed: " . $e->getMessage();
    // You might want to handle this error more gracefully in a production environment
    // For example, logging the error and displaying a user-friendly message
}
?>