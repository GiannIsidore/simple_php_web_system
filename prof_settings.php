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
    <link rel="stylesheet" href="css/pro_set.css">
    <style>
    /* Style for the overlay */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        /* Semi-transparent background */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        /* Ensure it appears on top of other content */
    }

    /* Style for the video element */
    .overlay video {
        max-width: 80%;
        /* Adjust as needed */
        max-height: 80%;
        /* Adjust as needed */
    }
    </style>
    <script>
    function openCamera() {
        const constraints = {
            video: true
        };

        navigator.mediaDevices.getUserMedia(constraints)
            .then(function(stream) {
                console.log('Camera access successful');
                const overlay = document.createElement('div');
                overlay.classList.add('overlay');

                const video = document.createElement('video');
                video.srcObject = stream;
                video.autoplay = true;

                overlay.appendChild(video);
                document.body.appendChild(overlay);

                const closeButton = document.createElement('button');
                const changePicButton = document.createElement('button');
                closeButton.textContent = 'Close Camera';
                changePicButton.textContent = 'Change Profile Pic';
                closeButton.onclick = function() {
                    stream.getTracks().forEach(track => track.stop());
                    document.body.removeChild(overlay); // Remove the overlay
                    window.location.href = "prof_settings.php";
                };

                changePicButton.onclick = function() {
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    // Set canvas dimensions same as video
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;

                    // Draw video frame onto canvas
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    // Convert canvas content to base64 data URL
                    const imageDataUrl = canvas.toDataURL('image/jpeg');

                    // Send the image data to server using AJAX or form submission
                    const formData = new FormData();
                    formData.append('profile_image', imageDataUrl);


                    // formData.append('user_id', <?php echo $_SESSION['user_id']; ?>);


                    fetch('update_profile_pic.php', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => {
                            if (response.ok) {

                                console.log('Profile picture updated successfully');
                            } else {
                                // Handle error
                                console.error('Failed to update profile picture');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating profile picture:', error);
                        });
                };

                overlay.appendChild(closeButton);
                overlay.appendChild(changePicButton);
            })
            .catch(function(error) {
                console.error('Error accessing camera:', error);
            });
    }
    </script>
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
                <div style="display: flex; flex-direction: row;">
                    <label for="profile_image">Profile Image: </label>
                    <input type="file" id="profile_image" name="profile_image"> <button type="button"
                        popovertarget="pop" id="webcamButton" onclick="openCamera()">Open Cam</button>


                </div>
                <div id="camera-div"></div>
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