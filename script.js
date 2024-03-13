// // Add event listeners once the DOM is fully loaded
// function toggleDropdown() {
//   console.log("Toggle dropdown function called");
//   var dropdownMenu = document.getElementById("dropdown-menu");
//   console.log("Dropdown menu:", dropdownMenu);
//   dropdownMenu.classList.toggle("show");
//   console.log("Dropdown menu class:", dropdownMenu.className);
// }

// // Close the dropdown if the user clicks outside of it
// window.onclick = function (event) {
//   if (!event.target.matches(".profile-image")) {
//     var dropdowns = document.getElementsByClassName("dropdown-content");
//     var i;
//     for (i = 0; i < dropdowns.length; i++) {
//       var openDropdown = dropdowns[i];
//       if (openDropdown.classList.contains("show")) {
//         openDropdown.classList.remove("show");
//       }
//     }
//   }
// };

document.addEventListener("DOMContentLoaded", function () {
  document.getElementById("save-budget").addEventListener("click", saveBudget);

  document
    .getElementById("show-calendar")
    .addEventListener("click", showCalendar);

  document
    .getElementById("show-history")
    .addEventListener("click", showHistory);

  document.getElementById("add-expense").addEventListener("click", addExpense);
});

function showCalendar() {
  const budget = parseFloat(document.getElementById("budget").value);
  const startDate = new Date(document.getElementById("start-date").value);
  const endDate = new Date(document.getElementById("end-date").value);

  const daysDiff = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;

  const dailyBudget = budget / daysDiff;

  const calendar = document.getElementById("budget-calendar");
  calendar.innerHTML = "";
  for (let i = 0; i < daysDiff; i++) {
    const day = new Date(startDate);
    day.setDate(day.getDate() + i);
    const cell = document.createElement("div");
    cell.classList.add("calendar-cell");
    cell.textContent = `${day.toDateString()} - ${dailyBudget.toFixed(2)}`;
    calendar.appendChild(cell);
  }
}

function showHistory() {
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "fetch_budget_history.php", true);
  xhr.onload = function () {
    if (xhr.status == 200) {
      // Parse JSON response
      const budgetHistory = JSON.parse(xhr.responseText);

      // Generate table HTML
      const table = document.createElement("table");
      table.innerHTML =
        "<thead><tr><th>Date</th><th>Budget</th></tr></thead><tbody>";
      budgetHistory.forEach((entry) => {
        table.innerHTML += `<tr><td>${entry.start_date}</td><td>${entry.amount}</td></tr>`;
      });
      table.innerHTML += "</tbody>";

      // Display table in budget history section
      const historySection = document.getElementById("budget-history");
      historySection.innerHTML = "";
      historySection.appendChild(table);
    } else {
      console.error("Error fetching budget history:", xhr.status);
    }
  };
  xhr.send();
}

function saveBudget() {
  console.log("Saving Budget...");

  const budget = document.getElementById("budget").value;
  const startDate = document.getElementById("start-date").value;
  const endDate = document.getElementById("end-date").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "save_budget.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status == 200) {
      console.log(xhr.responseText);
    } else {
      console.error("Error saving budget:", xhr.status);
    }
  };
  xhr.send(`budget=${budget}&start_date=${startDate}&end_date=${endDate}`);
}

function addExpense() {
  console.log("Adding Expense...");

  const expenseName = document.getElementById("expense-name").value;
  const quantity = document.getElementById("quantity").value;
  const price = document.getElementById("price").value;
  const purchaseDate = document.getElementById("purchase-date").value;

  const xhr = new XMLHttpRequest();
  xhr.open("POST", "add_expense.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status == 200) {
      console.log(xhr.responseText);
    } else {
      console.error("Error adding expense:", xhr.status);
    }
  };
  xhr.send(
    `expense_name=${expenseName}&quantity=${quantity}&price=${price}&purchase_date=${purchaseDate}`
  );
}
