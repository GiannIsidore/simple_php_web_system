<!DOCTYPE html>
<html>

<head>
    <title>Login and Registration</title>
    <meta http-equiv="Cache-control" content="no-cache">
    <link rel="stylesheet" href="css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
</head>

<body>
    <div class="background"></div>
    <div class="login-container">
        <div>
            <div class="transition-text">
                <div class="line1">HELLO</div>
                <div class="line2" style="font-size: 90px;">WELCOME TO</div> <!-- Increased font size -->
                <div class="line3">BUDGET NA UY</div>
            </div>
        </div>
        <div>
            <div id="login-form" class="form-container">
                <h2 id="btn_login">Login</h2>
                <form id="loginForm" action="../hm/form/login.php" method="post">
                    <input type="text" name="username" placeholder="Username" required><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                    <button type="submit" id="myBtn">Login</button>
                </form>
                <div class="switch-form">
                    <p id="error_message" style="color: red;"></p>
                    <p id="dontACc">Don't have an account? <button onclick="showRegisterForm()"
                            id="btn_reg">Register</button></p>
                </div>
            </div>
            <div id="register-form" class="form-container" style="display: none;">
                <h2 id="btn_login">Register</h2>
                <form action="../hm/form/register.php" method="post">
                    <input type="text" name="username" placeholder="Username" required><br>
                    <input type="email" name="email" placeholder="Email" required><br>
                    <input type="text" name="full_name" placeholder="Full Name" required><br>
                    <input type="password" name="password" placeholder="Password" required><br>
                    <button type="submit">Register</button>
                </form>
                <div class="switch-form">
                    <p id="dontACc">Already have an account? <button onclick="showLoginForm()">Login</button></p>
                </div>
            </div>
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

    // Function to make AJAX request
    function fetchErrorMessage() {
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var errorMessage = xhr.responseText;
                    // Display the error message
                    document.getElementById("error_message").innerText = errorMessage;
                } else {
                    console.error('Error occurred while fetching error message.');
                }
            }
        };
        xhr.open("POST", "../hm/form/login.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(new FormData(document.getElementById("loginForm")));
    }

    // Call the function when the page loads
    window.onload = fetchErrorMessage;
    </script>
</body>

</html>