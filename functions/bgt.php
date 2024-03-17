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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget and Expense Tracker</title>
    <!-- <link rel="stylesheet" href="../css/bgt.css"> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baumans&display=swap" rel="stylesheet">
    <style>
    body {
        margin: 0;
        padding: 0;
        background-color: #fbdd57;
    }

    .container {
        max-width: 2000px;
        margin: 70px auto 20px;
        /* Adjusted margin to account for navbar height */
        padding: 0 20px;
        background-color: #F1D55500;
    }

    #navbar {
        display: inline-flex;
        justify-content: space-between;

        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        color: white;
        background-color: #1F1F1F00;
        /* Or whatever background color you want */
        z-index: 1000;
        text-shadow: -3px 4.5px 2px #1F1F1FC2;
        /* Adjust z-index as needed to ensure it's above other content */
        transition: text-shadow 1s ease, background-color 0.3s ease, color 0.3s ease;


        /* Add smooth transition */
        padding-bottom: -30px;
        margin-bottom: -30px;
        height: fit-content;

    }

    #navbar a {
        font-size: 20px;
        color: #371A1A;
        text-decoration: none;
    }

    #navbar a:hover {
        font-size: 20px;
        color: #FFFFFF;
        text-decoration: none;
        box-shadow: #D00606;
    }

    .wrapper .btn {
        background-color: transparent;
    }

    #navbar.scrolled {
        color: #FBDD57;
        background-color: #8C1818;
        text-shadow: 2px 2px 2px #1F1F1FC2;
        /* Change to desired color */
    }

    #navbar.scrolled .wrapper {
        --font-color-dark: #FFFFFF;
        --font-color-light: #8C1818;
        --bg-color: #8C1818;
        --main-color: #FBDD57;


        /* Change to desired color */
    }



    .navbar-title {
        font-family: "Poppins", sans-serif;
        font-size: 3rem;
        font-weight: 900;
        padding-left: 30px;
    }

    .navbar-profile {
        display: flex;
        align-items: center;
    }

    .profile-image {
        margin-top: 2px;
        width: 53px;
        height: 51px;
        border-radius: 50%;
        margin-right: 80px;
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
        text-shadow: 0px 0px 0px #1F1F1F00;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        font-family: "Poppins", sans-serif;
        right: 20%;
        background-color: #ead9d9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        padding: 10px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
    }

    .dropdown-content a {
        color: #333;
        padding: 10px 20px;
        display: block;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .dropdown-content a:hover {
        background-color: #f2f2f2;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    /* Budget and Expense Tracker Styles */
    #budget-tracker,
    #expense-tracker {
        margin-top: 20px;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    #budget-tracker {
        font-family: "Poppins", sans-serif;
        background-color: rgba(0, 0, 0, 0);
        box-shadow: 0 0px 0px rgba(0, 0, 0, 0);
        display: inline-flex;
        justify-content: space-evenly;
    }

    #inputs {
        font-family: "Poppins", sans-serif;
        display: inline-flex;
        width: 250px;
        color: white;
        background-color: #1D1B1B;
        padding: 20px;
        border-radius: 14px;
        flex-direction: column;
    }

    /*  
    #bgtCal{
         margin-right: 300px;
    } */
    .inline-ni {

        margin-right: 20px;
    }

    #budget-tracker h2,
    #expense-tracker h2 {
        margin-bottom: 20px;
    }

    #budget-tracker input[type="number"],
    #budget-tracker input[type="date"],
    #expense-tracker input[type="text"],
    #expense-tracker input[type="number"],
    #expense-tracker input[type="date"] {
        width: calc(50% - 10px);
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    #budget-tracker button,
    #expense-tracker button {
        background-color: #333;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #budget-tracker button:hover,
    #expense-tracker button:hover {
        background-color: #555;
    }

    #budget-tracker button+button,
    #expense-tracker button+button {
        margin-left: 10px;
    }

    .inline {
        margin-left: -20px;
        font-size: 100px;
        padding-right: 180px;
    }

    .inline p {
        text-shadow: -8px 5px 4px #1D1B1B;
        font-weight: 800;
        margin: 0;
    }

    #hero.hero scrolled {
        text-shadow: 10px 5px 4px #1D1B1B;

    }

    .BandE {
        color: #ff0000;
        ;
        font-family: "Baumans", system-ui;
        font-weight: 400;
        font-style: normal;
    }

    #and {
        font-family: 'Indie Flower', cursive;

    }

    .hero {
        width: 100%;
        height: 53em;
        display: inline-flex;


        justify-content: center;
        align-content: space-around;
        flex-wrap: wrap;
    }

    #msg {
        font-size: 20px;
        background-color: #A10404CD;
        padding: 30px 30px 20px 30px;
        border-radius: 10px;
    }

    #Hello {
        color: #FFFFDE;
    }

    #Username {
        color: #D00606;
    }

    #pro_us {
        text-align: left;
        margin-top: -8px;
        margin-left: -30px;
        letter-spacing: 1.5px;
        font-family: "Poppins", sans-serif;
        text-shadow: 1px 2px #323232;

    }

    #msglng {
        color: #E3E3CA;
    }

    /*  */
    .wrapper {
        --font-color-dark: #323232;
        --font-color-light: #FFF;
        --bg-color: #fff;
        --main-color: #323232;
        margin-top: 19px;
        position: relative;
        width: 558px;
        height: 36px;
        background-color: var(--bg-color);
        border: 2px solid var(--main-color);
        border-radius: 34px;
        display: flex;
        flex-direction: row;
        box-shadow: 4px 4px var(--main-color);
        text-shadow: 0px 0px 0px;
        justify-content: space-around;
    }

    .option {
        width: 133.5px;
        height: 28px;
        position: relative;
        top: 2px;
        left: 2px;
    }

    .input {
        width: 100%;
        height: 100%;
        position: absolute;
        left: 0;
        top: 0;
        appearance: none;
        cursor: pointer;
    }

    .btn {
        width: 100%;
        height: 100%;
        background-color: var(--bg-color);
        border-radius: 50px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .span {
        color: var(--font-color-dark);
    }

    .input:checked+.btn {
        background-color: var(--main-color);
    }

    .input:checked+.btn .span {
        color: var(--font-color-light);
    }

    img {
        box-shadow: 0 4px 8px #1D1B1B;
        /* Horizontal offset, vertical offset, blur radius, and color */
    }

    #bgtCal {

        width: 50em;
        margin-top: -20px;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    #budget-calendar {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 5px;
    }

    .calendar-cell {
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        text-align: center;
        background-color: #f0f0f0;
    }


    #greet {
        font-family: "Poppins", sans-serif;
        padding: 20px;
        border-radius: 15px;
        background-color: #1D1B1BF1;
        margin-left: -80px;
    }

    #bg-his {
        width: 350px;
        color: whitesmoke;
        margin-left: 20px;
        background-color: black;
    }

    @media only screen and (max-width: 600px) {
        .container {
            margin-top: 120px;
        }

        .navbar {
            padding: 10px;
        }
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav id="navbar">
        <div class="navbar-title">
            BUDGET NA UY
        </div>
        <!-- <a href="#hero">Home</a>
        <a href="#scroll">BUDGET</a>
        <a href="#add-expense-form">EXPENSE</a> -->
        <div class="wrapper">
            <div class="option">
                <input class="input" type="radio" name="btn" value="hero" id="1" checked="">
                <div class="btn">
                    <label for="hero" class="btn">
                        <span class="span">Home</span>
                    </label>
                </div>
            </div>
            <div class="option">
                <input class="input" type="radio" name="btn" value="scroll" id="2">
                <div class="btn">
                    <label for="scroll" class="btn">
                        <span class="span">Budget Tracker</span>
                    </label>
                </div>
            </div>
            <div class="option">
                <input class="input" type="radio" name="btn" value="add-expense-form" id="3">
                <div class="btn">
                    <label for="add-expense-form" class="btn">
                        <span class="span">Expense Tracker</span>
                    </label>
                </div>
            </div>
        </div>


        <div class="dropdown">

            <img src="data:image/jpeg;base64,<?php echo $profile_image_base64; ?>" class="profile-image"
                onclick="toggleDropdown()">

            <div class="dropdown-content">
                <div>
                    <a href="../prof_settings.php">Profile Settings</a>
                    <br>
                    <a href="../form/logout.php">Logout</a>
                </div>
            </div>
            <p id="pro_us"><?php echo $user['username']; ?></p>
        </div>
    </nav>


    <div class="container">
        <div class="hero" id="hero">
            <div class="inline">
                <p class="BandE">Budget</p>
                <p id="and">and</p>
                <p class="BandE"> Expense Tracker</p>
            </div>
            <div id="greet">
                <h1 id="Hello">HELLO,<h1 id="Username"><?php echo $user['username']; ?></h1>
                </h1>
                <br>

                <div id="msg">

                    <p id="msglng">This Small Web System <br>for budgeting and Expense tracker. <br>This is for all
                        students
                        <br> and
                        for
                        those
                        who
                        are in a tight budget
                    </p>

                </div>
                <div id="scroll">
                    <br>
                </div>
            </div>
        </div>

        <!-- Budget tracker -->
        <div id="budget-tracker">
            <div class='inline-ni' id="inputs">
                <h2>Budget Tracker</h2>
                <!-- Input fields for bgt -->
                <label for="budget">Budget:&nbsp;&nbsp;&nbsp;&nbsp; </label>
                <input type="number" id="budget" name="budget" required><br>
                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date" name="start-date" required><br>
                <label for="end-date">End Date:&nbsp;</label>
                <input type="date" id="end-date" name="end-date" required><br>
                <button id="save-budget">Save Budget</button><br>
                <!-- Buttons bgt-->
                <button id="show-calendar">Show Budget Distribution Calendar</button><br>
                <button id="show-history">Show Budget History</button>
            </div>



            <div class='inline-ni' style="display: inline-flex;">
                <div id="bgtCal">
                    <h1>CALENDAR</h1>
                    <div id="budget-calendar">
                    </div>
                </div><br><br>
                <div id="bg-his">
                    <h1>Buget History</h1>
                    <ul id="budget-history"></ul>
                </div>

            </div>
            <!-- Budget calendar and history -->

        </div>

        <!-- Expense tracker -->
        <div id="expense-tracker">
            <h2>Expense Tracker</h2>
            <!-- Input fields for exp -->
            <form id="add-expense-form" action="add_expense.php" method="post">
                <label for="expense-name">Expense Name:</label>
                <input type="text" id="expense-name" name="expense-name" required><br>
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" required><br>
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" required><br>
                <label for="purchase-date">Purchase Date:</label>
                <input type="date" id="purchase-date" name="purchase-date" required><br>

                <!-- Hidden input field to store user ID -->
                <input type="hidden" id="user-id" name="user-id" value="<?php echo $_SESSION['user_id']; ?>">

                <button type="submit" id="add-expense">Add Expense</button>
            </form>

            <!-- exp table -->
            <table id="expense-table">
                <thead>
                    <tr>
                        <th>Expense Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Purchase Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="expense-list">
                    <?php include("fetch_expenses.php"); ?>
                </tbody>
            </table>
        </div>

        <div id="feedback-messages"></div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../js/indexJS/script.js"></script>
</body>

</html>