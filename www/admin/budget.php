<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Budget Management</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F4F6F9;
            color: #34495E;
            line-height: 1.6;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 85px;
            background: linear-gradient(45deg, #2C3E50 0%, #4F46E5 100%);
            color: white;
            padding: 0 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .title {
            font-size: 2em;
            margin: 0;
        }

        .card-container {
            display: flex;
            justify-content: center;
            margin: 20px;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            flex: 1;
            max-width: 400px; /* Limit card width */
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .total-budget-card {
            background: #4F46E5; /* Premium color */
            color: white;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            flex: 1;
            max-width: 600px; /* Wider card */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .total-budget-card h3 {
            margin: 0 0 10px;
        }

        .total-budget-card p {
            margin: 5px 0;
            font-size: 1.2em;
        }

        .button {
            background-color: #28A745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 20px;
        }

        .button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }

        .table-section {
            margin: 20px;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #BDC3C7;
        }

        th {
            background-color: #4F46E5;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #e1e1e1;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            background-color: #E74C3C;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }

        .action-buttons button:hover {
            background-color: #C0392B;
            transform: scale(1.05);
        }

        /* Modal styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Tooltip styles */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%; /* Position above the tooltip */
            left: 50%;
            margin-left: -60px; /* Center the tooltip */
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2 class="title"><i class="fas fa-money-bill-wave"></i> Budget Management</h2>
    </div>

    <div class="card-container">
        <div class="total-budget-card">
            <h3>Total Budget</h3>
            <p id="total-budget">$10,000</p>
            <p>Remaining Budget: <span id="remaining-budget">$5,000</span></p>
            <select id="term-select" required>
                <option value="">Select Term</option>
                <option value="first">First Term</option>
                <option value="second">Second Term</option>
                <option value="third">Third Term</option>
            </select>
        </div>
        <div class="card">
            <h3>Add New Expense</h3>
            <button class="button" id="open-modal-button"><i class="fas fa-plus-circle"></i> Add Expense</button>
        </div>
    </div>

    <section class="table-section">
        <h3>Current Expenses</h3>
        <table id="expenses-table">
            <thead>
                <tr>
                    <th>Expense ID</th>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Payment Method</th>
                    <th>Receipt Uploaded</th>
                    <th>Status</th>
                    <th>Budget Allocated</th>
                    <th>Remaining Budget</th>
                    <th>Approved By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Example Row -->
                <tr class="within-budget">
                    <td>1</td>
                    <td>04/20/2025</td>
                    <td>Supplies</td>
                    <td>Office Supplies</td>
                    <td>$200</td>
                    <td>Credit Card</td>
                    <td><i class="fas fa-check-circle"></i></td>
                    <td class="tooltip">Approved<span class="tooltiptext">Approved by John Doe</span></td>
                    <td>$1,000</td>
                    <td>$800</td>
                    <td>Jane Smith</td>
                    <td class="action-buttons">
                        <button class="edit-button"><i class="fas fa-edit"></i> Edit</button>
                        <button class="delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
                    </td>
                </tr>
                <!-- More rows will be populated here -->
            </tbody>
        </table>
    </section>

    <!-- Modal for Adding Expense -->
    <div id="expenseModal" class="modal">
        <div class="modal-content">
            <span class="close" id="close-modal-button">&times;</span>
            <h3><i class="fas fa-plus-circle"></i> Add Expense</h3>
            <input type="date" id="expense-date" required>
            <select id="expense-category" required>
                <option value="">Select Category</option>
                <option value="supplies">Supplies</option>
                <option value="travel">Travel</option>
                <option value="services">Services</option>
                <option value="other">Other</option>
            </select>
            <input type="text" id="expense-description" placeholder="Description" required>
            <input type="number" id="expense-amount" placeholder="Amount" required>
            <select id="payment-method" required>
                <option value="">Select Payment Method</option>
                <option value="credit_card">Credit Card</option>
                <option value="cash">Cash</option>
                <option value="bank_transfer">Bank Transfer</option>
            </select>
            <button class="button" id="submit-expense-button">Submit Expense</button>
        </div>
    </div>

    <script>
        // Open the modal
        document.getElementById('open-modal-button').onclick = function() {
            document.getElementById('expenseModal').style.display = 'block';
        }

        // Close the modal
        document.getElementById('close-modal-button').onclick = function() {
            document.getElementById('expenseModal').style.display = 'none';
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            if (event.target == document.getElementById('expenseModal')) {
                document.getElementById('expenseModal').style.display = 'none';
            }
        }

        // Add expense functionality
        document.getElementById('submit-expense-button').addEventListener('click', () => {
            const date = document.getElementById('expense-date').value;
            const category = document.getElementById('expense-category').value;
            const description = document.getElementById('expense-description').value;
            const amount = document.getElementById('expense-amount').value;
            const paymentMethod = document.getElementById('payment-method').value;

            if (date && category && description && amount && paymentMethod) {
                // Simulate adding expense to the server
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>2</td> <!-- This should be dynamically generated -->
                    <td>${date}</td>
                    <td>${category}</td>
                    <td>${description}</td>
                    <td>$${parseFloat(amount).toFixed(2)}</td>
                    <td>${paymentMethod}</td>
                    <td><i class="fas fa-check-circle"></i></td>
                    <td class="tooltip">Pending<span class="tooltiptext">Pending approval</span></td>
                    <td>$1,000</td> <!-- Example Budget Allocated -->
                    <td>$800</td> <!-- Example Remaining Budget -->
                    <td>Not Approved</td>
                    <td class="action-buttons">
                        <button class="edit-button"><i class="fas fa-edit"></i> Edit</button>
                        <button class="delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
                    </td>
                `;

                // Append the new row to the table body
                document.getElementById('expenses-table').getElementsByTagName('tbody')[0].appendChild(newRow);

                // Clear the input fields
                document.getElementById('expense-date').value = '';
                document.getElementById('expense-category').value = '';
                document.getElementById('expense-description').value = '';
                document.getElementById('expense-amount').value = '';
                document.getElementById('payment-method').value = '';

                                // Close the modal
                document.getElementById('expenseModal').style.display = 'none';
            } else {
                alert('Please fill in all fields.');
            }
        });

        // Event delegation for delete buttons
        document.getElementById('expenses-table').addEventListener('click', (event) => {
            if (event.target.classList.contains('delete-button')) {
                const row = event.target.closest('tr');
                if (confirm('Are you sure you want to delete this expense?')) {
                    row.remove();
                }
            }
        });

        // Event delegation for edit buttons (placeholder functionality)
        document.getElementById('expenses-table').addEventListener('click', (event) => {
            if (event.target.classList.contains('edit-button')) {
                alert('Edit functionality not implemented yet.');
            }
        });
    </script>
</body>
</html>