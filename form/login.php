<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $usernameOrEmail = $_POST["username"];
        $password = $_POST["password"];

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

            // Check if username or email exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :usernameOrEmail OR email = :email");

            $stmt->execute([
                'usernameOrEmail' => $usernameOrEmail,
                'email' => $usernameOrEmail // Pass the same value for email
            ]);

            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login_success'] = true; // Set session variable for successful login
                sleep(1.5);
                header("Location:../functions/bgt.php");
                exit;
            } else {
                echo "Invalid username/email or password";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Username/email or password not provided";
    }
} else {
    echo "Invalid request method";
}
?>