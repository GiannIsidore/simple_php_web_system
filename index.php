<!DOCTYPE html>
<html>

<head>
    <title>Login and Registration</title>
    <link rel="stylesheet" type="text/css" href="styless.css">
</head>

<body>
    <div class="login-container">
        <div id="login-form">
            <h2>Login</h2>
            <form action="php/login.php" method="post">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <button onclick="showRegisterForm()">Register</button></p>
        </div>
        <div id="register-form" style="display: none;">
            <h2>Register</h2>
            <form action="php/register.php" method="post">
                <input type="text" name="username" placeholder="Username" required><br>
                <input type="email" name="email" placeholder="Email" required><br>
                <input type="text" name="full_name" placeholder="Full Name" required><br>
                <input type="password" name="password" placeholder="Password" required><br>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <button onclick="showLoginForm()">Login</button></p>
        </div>
    </div>

    <script>
    function showRegisterForm() {
        document.getElementById('login-form').style.display = 'none';
        document.getElementById('register-form').style.display = 'block';
    }

    function showLoginForm() {
        document.getElementById('login-form').style.display = 'block';
        document.getElementById('register-form').style.display = 'none';
    }
    </script>
</body>

</html>