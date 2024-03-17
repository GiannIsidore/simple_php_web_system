<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if user is not logged in
    header("Location: login.php");
    exit;
}

// Database connection
$dsn = 'mysql:host=localhost;dbname=db_bgt';
$username_db = 'root';
$password_db = '';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $username_db, $password_db, $options);

    // Retrieve user data based on user_id
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->execute(['user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();

    if (!$user) {
        // Redirect or display an error if user data is not found
        echo "User data not found";
        exit;
    }

    // Convert the profile image binary data to base64
    $profile_image_base64 = base64_encode($user['profile_image']);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Output the base64 encoded profile image
echo $profile_image_base64;
?>