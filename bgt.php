<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget and Expense Tracker</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <nav class="navbar">
        <div class="navbar-title">
            Budget and Expense Tracker
        </div>
        <div class="dropdown">
            <img src="Noah.jpg" class="profile-image" onclick="toggleDropdown()">
            <div class="dropdown-content">
                <div>
                    <a href="prof_settings.php">Profile Settings</a>
                    <br>
                    <a href="logout.php">Logout</a>
                </div>


            </div>

        </div>
    </nav>
    <div class="container">
        <h1>Budget and Expense Tracker</h1>
        <!-- Budget tracker -->
        <div id="budget-tracker">
            <h2>Budget Tracker</h2>
            <!-- Input fields for bgt -->
            <label for="budget">Budget:</label>
            <input type="number" id="budget" name="budget" required>
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" name="start-date" required>
            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" name="end-date" required>
            <button id="save-budget">Save Budget</button>

            <!-- Buttons bgt-->
            <button id="show-calendar">Show Budget Distribution Calendar</button>
            <button id="show-history">Show Budget History</button>

            <!-- Budget calendar and history -->
            <div id="budget-calendar"></div>
            <ul id="budget-history"></ul>
        </div>

        <!-- Expense tracker -->
        <div id="expense-tracker">
            <h2>Expense Tracker</h2>
            <!-- Input fields for exp -->
            <label for="expense-name">Expense Name:</label>
            <input type="text" id="expense-name" name="expense-name" required>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" required>
            <label for="purchase-date">Purchase Date:</label>
            <input type="date" id="purchase-date" name="purchase-date" required>
            <button id="add-expense" onclick="addExpense()">Add Expense</button>
            <!-- exp table -->
            <table id="expense-table">
                <thead>
                    <tr>
                        <th>Expense Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Purchase Date</th>
                    </tr>
                </thead>
                <tbody id="expense-list"></tbody>
            </table>
        </div>

        <div id="feedback-messages"></div>
    </div>
    <script src="script.js"></script>
</body>

</html>