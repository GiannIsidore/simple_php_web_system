<?php
session_start();

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

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update user data in the database
    $full_name = $_POST['full_name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // New password

    // Check if a new profile image is uploaded
    if ($_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp_name = $_FILES['profile_image']['tmp_name'];
        $image_data = file_get_contents($image_tmp_name); // Read image data

        // Update profile image data in the database
        $stmt = $pdo->prepare("UPDATE users SET profile_image = :image_data WHERE id = :user_id");
        $stmt->execute(['image_data' => $image_data, 'user_id' => $_SESSION['user_id']]);
    }

    // Update other user data
    $stmt = $pdo->prepare("UPDATE users SET full_name = :full_name, username = :username, email = :email WHERE id = :user_id");
    $stmt->execute(['full_name' => $full_name, 'username' => $username, 'email' => $email, 'user_id' => $_SESSION['user_id']]);

    // If a new password is provided, update it
    if (!empty($password)) {
        // Hash the new password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE id = :user_id");
        $stmt->execute(['password' => $hashed_password, 'user_id' => $_SESSION['user_id']]);
    }

    // Redirect to profile settings page after changes
    header("Location:../hm/prof_settings.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <style>
    /* CSS styles remain the same as provided in the previous example */
    body {
        font-family: Arial, sans-serif;
    }

    .container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .profile-img {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        /* creates circular shape */
        object-fit: cover;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"] {
        width: 100%;
        padding: 10px;
        border-radius: 5px;
        border: 1px solid #ccc;
        box-sizing: border-box;
    }

    input[type="submit"],
    input[type="button"] {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        cursor: pointer;
    }

    input[type="submit"]:hover,
    input[type="button"]:hover {
        background-color: #0056b3;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Profile Settings</h2>
        <!-- Profile Image -->
        <?php if (!empty($user['profile_image'])): ?>
        <img class="profile-img" src="data:image/jpeg;base64,<?php echo base64_encode($user['profile_image']); ?>"
            alt="Profile Image">
        <?php else: ?>
        <!-- If user doesn't have a profile image, you can display a default image -->
        <img class="profile-img" src="default_profile_image.jpg" alt="Default Profile Image">
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <!-- Profile Image Upload -->
            <div class="form-group">
                <label for="profile_image">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image">
            </div>
            <!-- Full Name -->
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $user['full_name']; ?>">
            </div>
            <!-- Username -->
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo $user['username']; ?>">
            </div>
            <!-- Email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>">
            </div>
            <!-- New Password -->
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password">
            </div>
            <!-- Submit Button -->
            <div class="form-group">
                <input type="submit" value="Save Changes">
                <input type="button" value="Back" onclick="window.location.href='../hm/functions/bgt.php'">
            </div>
        </form>
    </div>
</body>

</html>