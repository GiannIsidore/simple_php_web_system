window.addEventListener("scroll", function () {
  var navbar = document.getElementById("navbar");
  var hero = document.getElementById("hero");
  if (window.scrollY > 0) {
    console.log("scrolled");
    navbar.classList.add("scrolled");
    hero.classList.add("scrolled");
  } else {
    navbar.classList.remove("scrolled");
    hero.classList.remove("scrolled");
  }
});

document.addEventListener("DOMContentLoaded", function () {
  const inputs = document.querySelectorAll(".input");

  inputs.forEach((input) => {
    input.addEventListener("change", function () {
      const targetId = this.value;
      let targetElement;
      if (targetId === "con") {
        targetElement = document.getElementById("con");
      } else if (targetId === "scroll") {
        targetElement = document.getElementById("scroll");
      } else if (targetId === "add-expense-form") {
        targetElement = document.getElementById("add-expense-form");
      }

      if (targetElement) {
        targetElement.scrollIntoView({ behavior: "smooth" });
      }
    });
  });
});

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
  console.log("showing history Budget...");
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "../functions/fetch_budget_history.php", true);
  xhr.onload = function () {
    if (xhr.status == 200) {
      // Parse JSON response
      const budgetHistory = JSON.parse(xhr.responseText);

      // Generate table HTML
      const table = document.createElement("table");
      table.innerHTML =
        "<thea class='tthead'd><tr class='ttrow'><th class='thead'>Date</th><th class='thead'>Budget</th></tr></thea><tbody>";
      budgetHistory.forEach((entry) => {
        table.innerHTML += `<tr class='trow'><td class='tdata'>${entry.start_date}</td><td class='tdata'>${entry.amount}</td></tr>`;
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
  xhr.open("POST", "../functions/save_budget.php", true);
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

// Assuming you're using jQuery for simplicity
$(document).ready(function () {
  // Edit button click event handler
  $(".edit-btn").click(function () {
    var expenseId = $(this).data("expense-id");
    // Redirect to edit expense page with the expense ID
    window.location.href = "edit_expense.php?expense_id=" + expenseId;
  });

  // Delete button click event handler
  $(".delete-btn").click(function () {
    if (confirm("Are you sure you want to delete this expense?")) {
      var expenseId = $(this).data("expense-id");
      // Send AJAX request to delete expense
      $.ajax({
        url: "delete_expense.php",
        method: "POST",
        data: { expense_id: expenseId },
        success: function (response) {
          // Reload the page after successful deletion
          location.reload();
        },
        error: function (xhr, status, error) {
          // Handle error, if any
          console.error(xhr.responseText);
        },
      });
    }
  });
});

// function addExpense() {
//   console.log("Adding Expense...");

//   const product = document.getElementById("expense-name").value;
//   const quantity = document.getElementById("quantity").value;
//   const price = document.getElementById("price").value;
//   const purchaseDate = document.getElementById("purchase-date").value;
//   console.log(product, quantity, price, purchaseDate);
//   const xhr = new XMLHttpRequest();
//   xhr.open("POST", "../functions/add_expense.php", true);
//   xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
//   xhr.onload = function () {
//     if (xhr.status == 200) {
//       console.log(xhr.responseText);
//       // Refresh the table after adding expense
//       fetchExpenses();
//     } else {
//       console.error("Error adding expense:", xhr.status);
//     }
//   };
//   xhr.send(
//     `product=${product}&quantity=${quantity}&price=${price}&purchase-date=${purchaseDate}`
//   );
// }
// Fetch expenses and append them to the table
// function fetchExpenses() {
//   fetch("../functions/fetch_expenses.php")
//     .then((response) => response.text())
//     .then((data) => {
//       // Append the fetched HTML code to the expense-list section
//       document.getElementById("expense-list").innerHTML = data;
//     })
//     .catch((error) => console.error("Error:", error));
// }

// // Call the fetchExpenses function when the page loads
// document.addEventListener("DOMContentLoaded", function () {
//   fetchExpenses();
// });
