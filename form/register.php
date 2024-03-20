<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Database con
    $dsn = 'mysql:host=localhost;dbname=db_bgt';
    $username_db = 'root';
    $password_db = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $error_message2 = "";
    try {
        $pdo = new PDO($dsn, $username_db, $password_db, $options);

        // Check if username or email already exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email");
        $stmt->execute(['username' => $username, 'email' => $email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error_message2 = "Username or Email already existed";
            header("Location: ../index.php");
        } else {
            
            $stmt = $pdo->prepare("INSERT INTO users (username,full_name, email, password) VALUES (:username,:full_name, :email, :password)");
            $stmt->execute(['username' => $username,'full_name' => $full_name, 'email' => $email, 'password' => $password]);
            
            header("Location: ../index.php");
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>