<?php
session_start();

// Check if user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_bgt";

try {

    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
   
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
  
    $stmt = $conn->prepare("SELECT username, email FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();
    
    // Fetch  data
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Set session data for username and email
    $_SESSION['username'] = $userData['username'];
    $_SESSION['email'] = $userData['email'];
} catch(PDOException $e) {
    
    echo "Error: " . $e->getMessage();
} finally {
 
    $conn = null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <h1>Profile Settings</h1>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $_SESSION['username']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Save Changes</button>
            </div>
        </form>
        <?php if (isset($_GET['success'])): ?>
        <div class="success-message">Profile updated successfully!</div>
        <?php endif; ?>
    </div>
</body>

</html>