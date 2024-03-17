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
    <!-- Head content remains the same -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget and Expense Tracker</title>
    <!-- <link rel="stylesheet" href="../css/indexCSS/styles.css"> -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Baumans&display=swap" rel="stylesheet">
    <style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FBDD57;
    }

    .container {
        max-width: 2000px;
        margin: 70px auto 20px;
        /* Adjusted margin to account for navbar height */
        padding: 0 20px;
        background-color: #FBDD57;
    }

    .navbar {

        position: fixed;
        top: 0;
        width: 100%;
        background-color: #333;
        color: #fff;
        padding: 10px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        z-index: 1000;
    }

    .navbar-title {
        font-size: 3rem;
        font-family: 'Poppins', sans-serif;
    }


    .navbar-profile {
        display: flex;
        align-items: center;
    }

    .profile-image {
        width: 80px;
        /* Adjust the width as desired */
        height: 80px;
        /* Adjust the height as desired */
        border-radius: 50%;
        margin-right: 20px;
        /* Adjust the margin as desired */
        cursor: pointer;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 20%;
        background-color: #ce1818;
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

    #budget-tracker h2,
    #expense-tracker h2 {
        margin-bottom: 20px;
    }

    #budget-tracker input[type="number"],
    #budget-tracker input[type="date"],
    #expense-tracker input[type="text"],
    #expense-tracker input[type="number"],
    #expense-tracker input[type="date"] {
        width: calc(100% - 10px);
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
        display: inline-block;
        margin: 47px;
        font-size: 37px;
    }

    .BandE {
        font-family: "Baumans", system-ui;
        font-weight: 400;
        font-style: normal;
    }

    #and {
        font-family: 'Poppins', sans-serif;
    }

    .hero {
        width: 100%;
        height: 42em;
        display: inline-flex;
        width: 100%;
        height: 42em;
        justify-content: center;
        align-content: space-around;
        flex-wrap: wrap;
    }

    #msg {
        font-size: 20px;

    }



    @media only screen and (max-width: 600px) {
        .container {
            margin-top: 120px;
        }

        .navbar {
            padding: 10px;
        }

        .navbar-title {
            font-size: 1.2rem;
        }
    }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-title">
            BUDGET NA UY
        </div>
        <div class="dropdown">
            <!-- Use the base64 encoded profile image data -->
            <img src="data:image/jpeg;base64,<?php echo $profile_image_base64; ?>" class="profile-image"
                onclick="toggleDropdown()">
            <div class="dropdown-content">
                <div>
                    <a href="../prof_settings.php">Profile Settings</a>
                    <br>
                    <a href="../form/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Rest of the HTML content remains the same -->
</body>

</html>


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Head content remains the same -->
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="navbar-title">
            BUDGET NA UY
        </div>
        <div class="dropdown">
            <!-- Use the fetched profile image URL -->
            <img src="<?php echo $profile_image_url; ?>" class="profile-image" onclick="toggleDropdown()">
            <div class="dropdown-content">
                <div>
                    <a href="../prof_settings.php">Profile Settings</a>
                    <br>
                    <a href="../form/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Rest of the HTML content remains the same -->
</body>

</html>

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
    <nav class="navbar">
        <div class="navbar-title">
            BUDGET NA UY
        </div>
        <div class="dropdown">
            <!-- Use the base64 encoded profile image data -->
            <img src="data:image/jpeg;base64,<?php echo $profile_image_base64; ?>" class="profile-image"
                onclick="toggleDropdown()">
            <div class="dropdown-content">
                <div>
                    <a href="../prof_settings.php">Profile Settings</a>
                    <br>
                    <a href="../form/logout.php">Logout</a>
                </div>
            </div>
        </div>
    </nav>


    <div class="container">
        <div class="hero">
            <div class="inline">
                <p class="BandE">Budget</p>
                <p id="and">and</p>
                <p class="BandE"> Expense Tracker</p>
            </div>
            <div class="inline" id="msg">
                <p>A Small Web System <br>for budgeting and Expense tracker. <br>This is for all students <br> and for
                    those
                    who
                    are in a tight budget</p>
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
                    <div id="budget-calendar"></div>
                </div><br><br>

                <ul id="budget-history"></ul>
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