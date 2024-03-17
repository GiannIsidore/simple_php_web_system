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
    <link rel="stylesheet" href="../css/bgt.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baumans&display=swap" rel="stylesheet">

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
                <input class="input" type="radio" name="btn" value="con" id="1" checked="">
                <div class="btn">
                    <label for="con" class="btn">
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


    <div class="container" id="con">
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
            <div>
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
            </div>


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