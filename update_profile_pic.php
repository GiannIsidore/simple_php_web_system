<?php
session_start();

// Database connection
$dsn = 'mysql:host=localhost;dbname=db_bgt';
$username_db = 'root';
$password_db = '';

try {
    $pdo = new PDO($dsn, $username_db, $password_db, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // Assuming the base64 image data is sent as a POST parameter named 'profile_image'
    if (isset($_POST['profile_image'])) {
        $profileImageData = $_POST['profile_image'];
        // Decode the base64 image data
        $decodedImage = base64_decode(str_replace('data:image/jpeg;base64,', '', $profileImageData));
        
        // Get the current user's profile image data
        $userId = $_SESSION['user_id'];
        $stmt = $pdo->prepare("SELECT profile_image FROM users WHERE id = :user_id");
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $previousImageData = $stmt->fetchColumn();
        
        // Delete the previous image data if it exists
        if ($previousImageData !== false) {
            // Proceed with deletion only if there is a previous image
            $stmt = $pdo->prepare("UPDATE users SET profile_image = NULL WHERE id = :user_id");
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
        }
        
        // Update the user's profile image in the database
        $stmt = $pdo->prepare("UPDATE users SET profile_image = :profile_image WHERE id = :user_id");
        $stmt->bindParam(':profile_image', $decodedImage, PDO::PARAM_LOB); // PDO::PARAM_LOB is used for binary data
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        
        // Optionally, you can redirect back to the profile settings page after updating
        header("Location: ../hm/prof_settings.php");
        exit;
    } else {
        // Handle the case where profile image data is not received
        echo "No profile image data received.";
    }
} catch (PDOException $e) {
    // Handle database connection or query errors
    echo "Error: " . $e->getMessage();
}
?>