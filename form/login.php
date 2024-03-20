<?php
session_start();
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["username"]) && isset($_POST["password"])) {
        $usernameOrEmail = $_POST["username"];
        $password = $_POST["password"];
        
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

            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :usernameOrEmail OR email = :email");

            $stmt->execute([
                'usernameOrEmail' => $usernameOrEmail,
                'email' => $usernameOrEmail 
            ]);

            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['login_success'] = true;
            
                header("Location:../functions/bgt.php");
                exit;
            } else {
                $errorMessage = "Incorrect Username or Password";
            }
        } catch (PDOException $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    } else {
        $errorMessage = "Username/email or password not provided";
    }
}


echo $errorMessage;
?>