<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Manager | Financial Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.min.css">
    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #1e293b;
            --gray: #64748b;
            --gray-light: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--dark);
            line-height: 1.6;
        }

        .dashboard {
            display: grid;
            grid-template-columns: 240px 1fr;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            background: white;
            box-shadow: var(--card-shadow);
            padding: 1.5rem 0;
            position: sticky;
            top: 0;
            height: 100vh;
            z-index: 10;
        }

        .logo {
            display: flex;
            align-items: center;
            padding: 0 1.5rem 1.5rem;
            border-bottom: 1px solid var(--gray-light);
            margin-bottom: 1.5rem;
        }

        .logo-icon {
            background: var(--primary);
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
        }

        .logo-text {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .nav-menu {
            list-style: none;
            padding: 0 1rem;
        }

        .nav-item {
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
        }

        .nav-item:hover {
            background-color: var(--gray-light);
        }

        .nav-item.active {
            background-color: rgba(79, 70, 229, 0.1);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--dark);
            text-decoration: none;
            font-weight: 500;
        }

        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            color: var(--gray);
        }

        .nav-item.active .nav-link i {
            color: var(--primary);
        }

        /* Main Content Styles */
        .main-content {
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--dark);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .stat-card.primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .stat-card-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(255, 255, 255, 0.2);
        }

        .stat-card.primary .stat-icon {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .stat-title {
            font-size: 0.875rem;
            color: var(--gray);
            font-weight: 500;
        }

        .stat-card.primary .stat-title {
            color: rgba(255, 255, 255, 0.8);
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .stat-change {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .stat-change.positive {
            color: var(--secondary);
        }

        .stat-change.negative {
            color: var(--danger);
        }

        /* Expense Table */
        .expense-section {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-secondary {
            background-color: var(--gray-light);
            color: var(--dark);
        }

        .btn-secondary:hover {
            background-color: #cbd5e1;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background-color: #f8fafc;
            border-bottom: 1px solid var(--gray-light);
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray);
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-light);
            font-size: 0.9375rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background-color: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-primary {
            background-color: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .badge-success {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            border: none;
            cursor: pointer;
            transition: var(--transition);
            color: var(--gray);
        }

        .action-btn:hover {
            background-color: var(--gray-light);
            color: var(--dark);
        }

        .action-btn.edit:hover {
            color: var(--primary);
        }

        .action-btn.delete:hover {
            color: var(--danger);
        }

        /* Modal Styles */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 100%;
            max-width: 480px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(20px);
            transition: all 0.3s ease;
        }

        .modal.active .modal-content {
            transform: translateY(0);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--gray-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--gray);
            transition: var(--transition);
        }

        .modal-close:hover {
            color: var(--danger);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-light);
            border-radius: 8px;
            font-size: 0.9375rem;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--gray-light);
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
        }

        /* Responsive Styles */
        @media (max-width: 992px) {
            .dashboard {
                grid-template-columns: 1fr;
            }
            
            .sidebar {
                position: fixed;
                left: -100%;
                width: 280px;
                transition: all 0.3s ease;
            }
            
            .sidebar.active {
                left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }
        }

        /* Animations */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <span class="logo-text">ExpensePro</span>
            </div>
            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="dashboard.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
    <a href="rec.php" class="nav-link">
        <i class="fas fa-chart-pie"></i>
        <span>Analytics</span>
    </a>
</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span>Calendar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-question-circle"></i>
                        <span>Help</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <div class="header">
                <h1 class="page-title">Expense Management</h1>
                <div class="user-profile">
                    <div class="user-avatar">JD</div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card animate-slide-up" style="animation-delay: 0.1s;">
                    <div class="stat-card-header">
                        <div>
                            <p class="stat-title">Total Expenses</p>
                            <p class="stat-value" id="total-expense">₵0.00</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>12% from last month</span>
                    </div>
                </div>
                <div class="stat-card animate-slide-up" style="animation-delay: 0.2s;">
                    <div class="stat-card-header">
                        <div>
                            <p class="stat-title">Monthly Budget</p>
                            <p class="stat-value">₵5,000.00</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-piggy-bank"></i>
                        </div>
                    </div>
                    <div class="stat-change negative">
                        <i class="fas fa-arrow-down"></i>
                        <span>8% over budget</span>
                    </div>
                </div>
                <div class="stat-card animate-slide-up" style="animation-delay: 0.3s;">
                    <div class="stat-card-header">
                        <div>
                            <p class="stat-title">Categories</p>
                            <p class="stat-value">4</p>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                    </div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>2 new this month</span>
                    </div>
                </div>
               
            </div>

            <!-- Expense Table Section -->
            <div class="expense-section animate-fade-in">
                <div class="section-header">
                    <h2 class="section-title">Recent Expenses</h2>
                    <button class="btn btn-primary" id="open-modal-button">
                        <i class="fas fa-plus"></i>
                        <span>Add Expense</span>
                    </button>
                </div>
                <div class="table-container">
                    <table id="expenses-table">
                        <thead>
                            <tr>
                                <th>Expense</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Existing expenses will be populated here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Expense Modal -->
    <div class="modal" id="expenseModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Add New Expense</h3>
                <button class="modal-close" id="close-modal-button">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="expense-name" class="form-label">Expense Name</label>
                    <input type="text" id="expense-name" class="form-control" placeholder="Office supplies">
                </div>
                <div class="form-group">
                    <label for="expense-amount" class="form-label">Amount (₵)</label>
                    <input type="number" id="expense-amount" class="form-control" placeholder="250.00">
                </div>
                <div class="form-group">
                    <label for="expense-date" class="form-label">Date</label>
                    <input type="date" id="expense-date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="expense-category" class="form-label">Category</label>
                    <select id="expense-category" class="form-control">
                        <option value="">Select Category</option>
                        <option value="supplies">Supplies</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="salaries">Salaries</option>
                        <option value="events">Events</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancel-expense-button">Cancel</button>
                <button class="btn btn-primary" id="add-expense-button">Add Expense</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.7.12/sweetalert2.all.min.js"></script>
    <script>
        // DOM Elements
        const modal = document.getElementById('expenseModal');
        const openModalBtn = document.getElementById('open-modal-button');
        const closeModalBtn = document.getElementById('close-modal-button');
        const cancelModalBtn = document.getElementById('cancel-expense-button');
        const addExpenseBtn = document.getElementById('add-expense-button');
        const expensesTable = document.getElementById('expenses-table').getElementsByTagName('tbody')[0];
        const totalExpenseElement = document.getElementById('total-expense');

        // Initialize total expense
        let totalExpense = 0;

        // Modal Functions
        function openModal() {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
            clearForm();
        }

        function clearForm() {
            document.getElementById('expense-name').value = '';
            document.getElementById('expense-amount').value = '';
            document.getElementById('expense-date').value = '';
            document.getElementById('expense-category').value = '';
        }

        // Event Listeners
        openModalBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelModalBtn.addEventListener('click', closeModal);

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Fetch existing expenses when the page loads
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                // Simulate API call (replace with actual fetch)
                const response = await fetch('get_expenses.php');
                const expenses = await response.json();

                // Sample data for demonstration
                const sampleExpenses = [
                    { id: 1, expense_name: "Office Supplies", amount: "350.00", expense_date: "2023-06-15", category: "supplies" },
                    { id: 2, expense_name: "Team Lunch", amount: "1200.00", expense_date: "2023-06-10", category: "events" },
                    { id: 3, expense_name: "Software Subscription", amount: "500.00", expense_date: "2023-06-05", category: "maintenance" },
                    { id: 4, expense_name: "June Salaries", amount: "15000.00", expense_date: "2023-06-01", category: "salaries" }
                ];

                // Use sample data if API fails
                const data = expenses.length > 0 ? expenses : sampleExpenses;

                // Populate table
                data.forEach(expense => {
                    addExpenseToTable(expense);
                    totalExpense += parseFloat(expense.amount);
                });

                updateTotalExpense();
            } catch (error) {
                console.error('Error fetching expenses:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to load expenses. Please try again later.'
                });
            }
        });

        // Add expense to table
        function addExpenseToTable(expense) {
            const newRow = expensesTable.insertRow();
            newRow.innerHTML = `
                <td>${expense.expense_name}</td>
                <td>₵${parseFloat(expense.amount).toFixed(2)}</td>
                <td>${formatDate(expense.expense_date)}</td>
                <td><span class="badge ${getCategoryBadgeClass(expense.category)}">${capitalizeFirstLetter(expense.category)}</span></td>
                <td>
                    <div class="actions">
                        <button class="action-btn edit" data-id="${expense.id}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="action-btn delete" data-id="${expense.id}">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            `;
        }

        // Format date
        function formatDate(dateString) {
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            return new Date(dateString).toLocaleDateString('en-US', options);
        }

        // Get category badge class
        function getCategoryBadgeClass(category) {
            switch(category) {
                case 'supplies': return 'badge-primary';
                case 'maintenance': return 'badge-warning';
                case 'salaries': return 'badge-success';
                case 'events': return 'badge-danger';
                default: return 'badge-primary';
            }
        }

        // Capitalize first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Update total expense display
        function updateTotalExpense() {
            totalExpenseElement.textContent = `₵${totalExpense.toFixed(2)}`;
        }

        // Add new expense
        addExpenseBtn.addEventListener('click', async () => {
            const name = document.getElementById('expense-name').value;
            const amount = document.getElementById('expense-amount').value;
            const date = document.getElementById('expense-date').value;
            const category = document.getElementById('expense-category').value;

            if (!name || !amount || !date || !category) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Incomplete Form',
                    text: 'Please fill in all fields before submitting.'
                });
                return;
            }

            try {
                // Simulate API call (replace with actual fetch)
                const response = await fetch('add_expense.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ name, amount, date, category }),
                });

                const result = await response.json();

                // For demo purposes, generate a random ID
                const newExpense = {
                    id: Math.floor(Math.random() * 1000) + 5,
                    expense_name: name,
                    amount: amount,
                    expense_date: date,
                    category: category
                };

                // Add to table
                addExpenseToTable(newExpense);
                
                // Update total
                totalExpense += parseFloat(amount);
                updateTotalExpense();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Expense added successfully.',
                    timer: 2000,
                    showConfirmButton: false
                });

                // Close modal
                closeModal();
            } catch (error) {
                console.error('Error adding expense:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to add expense. Please try again.'
                });
            }
        });

        // Handle delete and edit actions with event delegation
        expensesTable.addEventListener('click', (e) => {
            const target = e.target.closest('.action-btn');
            if (!target) return;

            const row = target.closest('tr');
            const expenseId = target.getAttribute('data-id');
            const isDelete = target.classList.contains('delete');
            const isEdit = target.classList.contains('edit');

            if (isDelete) {
                handleDeleteExpense(row, expenseId);
            } else if (isEdit) {
                handleEditExpense(row, expenseId);
            }
        });

        // Delete expense
        function handleDeleteExpense(row, expenseId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        // Simulate API call (replace with actual fetch)
                        const response = await fetch('delete_expense.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ id: expenseId }),
                        });

                        const result = await response.json();

                        // Get amount from the row being deleted
                        const amountText = row.cells[1].textContent.replace('₵', '');
                        const amount = parseFloat(amountText);

                        // Remove from table
                        row.remove();

                        // Update total
                        totalExpense -= amount;
                        updateTotalExpense();

                        // Show success message
                        Swal.fire(
                            'Deleted!',
                            'The expense has been deleted.',
                            'success'
                        );
                    } catch (error) {
                        console.error('Error deleting expense:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete expense. Please try again.'
                        });
                    }
                }
            });
        }

        // Edit expense (placeholder functionality)
        function handleEditExpense(row, expenseId) {
            Swal.fire({
                title: 'Edit Expense',
                html: 'This would open an edit form in a real application.',
                icon: 'info',
                confirmButtonText: 'OK'
            });
        }
    </script>
</body>
</html>