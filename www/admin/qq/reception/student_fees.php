<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'data.php';

// Fetch distinct classes for filtering
$classes = $database->query("SELECT DISTINCT class FROM payments ORDER BY class ASC");

// Fetch selected class
$selected_class = isset($_GET['class']) ? $_GET['class'] : "";

// Pagination setup
$limit = 40; // Maximum records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Query payments based on class with pagination
$query = "SELECT * FROM payments";
if (!empty($selected_class)) {
    $query .= " WHERE class = :class";
}
$query .= " LIMIT :limit OFFSET :offset";

$stmt = $database->prepare($query);
if (!empty($selected_class)) {
    $stmt->bindValue(':class', $selected_class, SQLITE3_TEXT);
}
$stmt->bindValue(':limit', $limit, SQLITE3_INTEGER);
$stmt->bindValue(':offset', $offset, SQLITE3_INTEGER);
$results = $stmt->execute();

// Calculate total amounts
$total_amount_query = "SELECT SUM(amount_paid) as total_amount FROM payments";
$total_amount_result = $database->query($total_amount_query);
$total_amount_row = $total_amount_result->fetchArray(SQLITE3_ASSOC);
$total_amount = $total_amount_row['total_amount'] ? $total_amount_row['total_amount'] : 0;

$total_balance_query = "SELECT SUM(balance) as total_balance FROM payments";
$total_balance_result = $database->query($total_balance_query);
$total_balance_row = $total_balance_result->fetchArray(SQLITE3_ASSOC);
$total_balance = $total_balance_row['total_balance'] ? $total_balance_row['total_balance'] : 0;

$total_arrears_query = "SELECT SUM(last_term_arrears) as total_arrears FROM payments";
$total_arrears_result = $database->query($total_arrears_query);
$total_arrears_row = $total_arrears_result->fetchArray(SQLITE3_ASSOC);
$total_arrears = $total_arrears_row['total_arrears'] ? $total_arrears_row['total_arrears'] : 0;

// Query for students with balances to settle
$balances_query = "SELECT student_name, balance FROM payments WHERE balance > 0";
$balances_result = $database->query($balances_query);

// Query for students with last term arrears
$arrears_query = "SELECT student_name, last_term_arrears FROM payments WHERE last_term_arrears > 0";
$arrears_result = $database->query($arrears_query);

// Fetch total records for pagination
$total_records_query = "SELECT COUNT(*) as total FROM payments";
$total_records_result = $database->query($total_records_query);
$total_records_row = $total_records_result->fetchArray(SQLITE3_ASSOC);
$total_records = $total_records_row['total'];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>School Fees Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f9f9f9;
        }
        .ai-header {
            background: linear-gradient(135deg, #0056b3 0%, #4CA1AF 100%);
            color: white;
            border-radius: 12px;
            padding: 10px; /* Reduced height */
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .subject-title {
            font-size: 2.5em;
            font-weight: bold;
            margin: 0;
        }
        .ai-header i {
            margin-right: 8px;
            font-size: 1.5em;
        }
        .table thead th {
            background-color: #007bff; /* Bootstrap primary color */
            color: white;
            font-weight: bold;
        }
        footer {
            background-color: #fffff;
        }
        .main-content {
            padding: 20px;
        }
        .money-card {
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px; /* Increased padding */
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }
        .money-card:hover {
            transform: scale(1.02);
        }
        .money-card h4 {
            margin: 0;
            color: #333;
        }
        .money-card h2 {
            color: #28a745;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .btn-custom {
            font-weight: 600;
            border-radius: 5px;
            transition: background-color 0.3s, transform 0.3s;
            width: 150px; /* Set a fixed width for buttons */
            height: 50px; /* Set a fixed height for buttons */
            margin-right: 10px; /* Added margin for spacing */
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2); /* Added shadow for polish */
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
        .btn-receipt {
            color: #FFFFFF; /* White text for better readability */
        }
        .btn-set-deadline {
            background-color: #0056b3; /* Change to dark blue */
        }
        .pagination {
            justify-content: center;
        }
        .table tbody tr:nth-child(even) {
            background-color: #f2f2f2; /* Alternating row colors */
        }
        .table tbody tr {
            height: 50px; /* Increased row height for better spacing */
        }
        .table td {
            padding: 10px; /* Increased padding for table cells */
        }
        @media (max-width: 768px) {
            .money-card {
                margin-bottom: 15px;
            }
            .btn-custom {
                width: 100%; /* Make buttons full width on small screens */
                margin-bottom: 10px; /* Add margin for spacing */
            }
        }
    </style>
</head>
<body>

<!-- Header -->
<div class="ai-header">
    <h2 class="subject-title"><i class="fas fa-book"></i> School Fees Management</h2>
    <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage student payments efficiently!</p>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12 main-content">
            <!-- Display Total Amounts in a Horizontal Layout -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="money-card" data-bs-toggle="modal" data-bs-target="#totalAmountModal">
                        <h4>Total Amount Paid:</h4>
                        <h2>Ghc <?= number_format($total_amount, 2) ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="money-card" data-bs-toggle="modal" data-bs-target="#totalBalanceModal">
                        <h4>Total Balance:</h4>
                        <h2>Ghc <?= number_format($total_balance, 2) ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="money-card" data-bs-toggle="modal" data-bs-target="#totalArrearsModal">
                        <h4>Total Last Term Arrears:</h4>
                        <h2>Ghc <?= number_format($total_arrears, 2) ?></h2>
                    </div>
                </div>
            </div>

            <!-- Filter by Class -->
            <form method="GET" class="mb-3">
                <label for="class">Filter by Class:</label>
                <select name="class" class="form-control" onchange="this.form.submit()">
                    <option value="">All Classes</option>
                    <?php while ($row = $classes->fetchArray(SQLITE3_ASSOC)) { ?>
                        <option value="<?= htmlspecialchars($row['class']) ?>" <?= ($selected_class == $row['class']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($row['class']) ?>
                        </option>
                    <?php } ?>
                </select>
            </form>

            <!-- Search Bar -->
            <input type="text" id="search" class="form-control mb-3" placeholder="Search students...">

            <!-- Action Buttons -->
            <div class="mb-3">
                <button class="btn btn-success mb-3 btn-custom" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
                    <i class="fas fa-plus"></i> Add Payment
                </button>
                <button class="btn btn-success mb-3 btn-custom btn-receipt">
                    <i class="fas fa-receipt"></i> Issue Bulk Receipt
                </button>
                <button class="btn btn-success mb-3 btn-custom btn-set-deadline" title="Add Parent Contact" onclick="window.location.href='deadline.php'">
                    <i class="fas fa-user-plus"></i> Set Deadline Reminder
                </button>
            </div>

            <!-- Payments Table -->
            <table class="table table-striped table-responsive" id="paymentsTable">
                <thead class="bg-dark text-white">
                    <tr>
                        <th>Student Name</th>
                        <th>Class</th>
                        <th>Amount Paid</th>
                        <th>Date Paid</th>
                        <th>Balance</th>
                        <th>Last Term Arrears</th>
                        <th>Actions</th> <!-- New column for actions -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)) { ?>
                        <tr data-id="<?= $row['id'] ?>" style="cursor: pointer;">
                            <td class="editable" data-field="student_name"><?= htmlspecialchars($row['student_name']) ?></td>
                            <td class="editable" data-field="class"><?= htmlspecialchars($row['class']) ?></td>
                            <td class="editable" data-field="amount_paid"><?= 'Ghc ' . htmlspecialchars($row['amount_paid']) ?></td>
                            <td class="editable" data-field="date_paid"><?= htmlspecialchars($row['date_paid']) ?></td>
                            <td class="editable" data-field="balance"><?= 'Ghc ' . htmlspecialchars($row['balance']) ?></td>
                            <td class="editable" data-field="last_term_arrears"><?= 'Ghc ' . htmlspecialchars($row['last_term_arrears']) ?></td>
                            <td>
                                <button class="btn btn-warning btn-sm edit-row-btn" data-id="<?= $row['id'] ?>">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-info btn-sm receipt-btn" data-id="<?= $row['id'] ?>">
                                    <i class="fas fa-receipt" style="color: white;"></i> Receipt
                                </button>
                                <button class="btn btn-danger btn-sm delete-btn" data-id="<?= $row['id'] ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&class=<?= urlencode($selected_class) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPaymentForm">
                    <input type="text" name="name" class="form-control mb-2" placeholder="Student Name" required>
                    <input type="text" name="class" class="form-control mb-2" placeholder="Class" required>
                    <div class="input-group mb-2">
                        <input type="text" id="add-amount" name="amount_paid" class="form-control" placeholder="Amount Paid" required>
                        <span class="input-group-text">Ghc</span>
                    </div>
                    <input type="date" name="date_paid" class="form-control mb-2" required>
                    <div class="input-group mb-2">
                        <input type="text" id="add-balance" name="balance" class="form-control" placeholder="Balance" required>
                        <span class="input-group-text">Ghc</span>
                    </div>
                    <div class="input-group mb-2">
                        <input type="text" id="add-arrears" name="last_term_arrears" class="form-control" placeholder="Last Term Arrears" required>
                        <span class="input-group-text">Ghc</span>
                    </div>
                    <button type="submit" class="btn btn-success">Add Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editPaymentForm">
                    <input type="hidden" name="id" id="edit-id" autocomplete="off">
                    <div class="mb-3">
                        <label for="edit-student-name" class="form-label">Student Name</label>
                        <input type="text" name="student_name" id="edit-student-name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-class" class="form-label">Class</label>
                        <input type="text" name="class" id="edit-class" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-amount" class="form-label">Amount Paid</label>
                        <div class="input-group">
                            <input type="text" id="edit-amount" name="amount_paid" class="form-control" required>
                            <span class="input-group-text">Ghc</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-date" class="form-label">Date Paid</label>
                        <input type="date" name="date_paid" id="edit-date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit-balance" class="form-label">Balance</label>
                        <div class="input-group">
                            <input type="text" id="edit-balance" name="balance" class="form-control" required>
                            <span class="input-group-text">Ghc</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit-arrears" class="form-label">Last Term Arrears</label>
                        <div class="input-group">
                            <input type="text" id="edit-arrears" name="last_term_arrears" class="form-control" required>
                            <span class="input-group-text">Ghc</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-warning">Update Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Total Balance Modal -->
<div class="modal fade" id="totalBalanceModal" tabindex="-1" aria-labelledby="totalBalanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Students with Balances</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $balances_result->fetchArray(SQLITE3_ASSOC)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= 'Ghc ' . htmlspecialchars($row['balance']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="mb-3">
                    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    <a href="export_to_csv.php" class="btn btn-secondary"><i class="fas fa-file-csv"></i> Export to CSV</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Total Last Term Arrears Modal -->
<div class="modal fade" id="totalArrearsModal" tabindex="-1" aria-labelledby="totalArrearsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Students with Last Term Arrears</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Last Term Arrears</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $arrears_result->fetchArray(SQLITE3_ASSOC)) { ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= 'Ghc ' . htmlspecialchars($row['last_term_arrears']) ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="mb-3">
                    <button class="btn btn-primary" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                    <a href="export_to_csv.php" class="btn btn-secondary"><i class="fas fa-file-csv"></i> Export to CSV</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Format amount input to prepend "Ghc"
    $('#add-amount, #add-balance, #add-arrears, #edit-amount, #edit-balance, #edit-arrears').on('input', function() {
        let value = $(this).val().replace(/Ghc\s*/, ''); // Remove existing "Ghc" if present
        if (!isNaN(value) && value !== '') {
            $(this).val('Ghc ' + value); // Prepend "Ghc"
        } else {
            $(this).val(''); // Clear if not a number
        }
    });

    // Handle form submission for adding payment
    $('#addPaymentForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Show loading indicator
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we add the payment.',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Extract the numeric values from the inputs
        let amount = $('#add-amount').val().replace(/Ghc\s*/, '').trim();
        let balance = $('#add-balance').val().replace(/Ghc\s*/, '').trim();
        let arrears = $('#add-arrears').val().replace(/Ghc\s*/, '').trim();

        $.ajax({
            type: 'POST',
            url: 'add_payment.php', // URL to your PHP script that handles the form submission
            data: $(this).serialize() + '&amount_paid=' + amount + '&balance=' + balance + '&last_term_arrears=' + arrears, // Serialize form data and add the numeric amounts
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Payment added successfully!',
                        showCloseButton: true,
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    $('#addPaymentModal').modal('hide');
                    $('#addPaymentForm')[0].reset();

                    // Refresh the payments table or the entire page
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: res.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed!',
                    text: 'Failed to add payment.',
                });
            }
        });
    });

    // Handle delete button click
    $(document).on('click', '.delete-btn', function(e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent the row click event from firing
        let id = $(this).data('id');

        // Show SweetAlert2 confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `delete_payment.php?id=${id}`,
                    type: 'GET',
                    success: function(response) {
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'Record deleted successfully!',
                            icon: 'success',
                            showCloseButton: true,
                            confirmButtonText: 'OK',
                            timer: 3000,
                            timerProgressBar: true
                        });
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Error deleting record. Please try again.',
                            'error'
                        );
                    }
                });
            }
        });
    });

    // Handle edit button click
    $(document).on('click', '.edit-row-btn', function(e) {
        e.preventDefault();
        const row = $(this).closest('tr');
        const id = $(this).data('id');

        // Prepare data for AJAX
        const data = {
            id: id,
            student_name: row.find('[data-field="student_name"]').text(),
            class: row.find('[data-field="class"]').text(),
            amount_paid: row.find('[data-field="amount_paid"]').text().replace('Ghc ', ''),
            date_paid: row.find('[data-field="date_paid"]').text(),
            balance: row.find('[data-field="balance"]').text().replace('Ghc ', ''),
            last_term_arrears: row.find('[data-field="last_term_arrears"]').text().replace('Ghc ', '')
        };

        // Populate the edit modal fields
        $('#edit-id').val(data.id);
        $('#edit-student-name').val(data.student_name);
        $('#edit-class').val(data.class);
        $('#edit-amount').val('Ghc ' + data.amount_paid);
        $('#edit-date').val(data.date_paid);
        $('#edit-balance').val('Ghc ' + data.balance);
        $('#edit-arrears').val('Ghc ' + data.last_term_arrears);

        // Show the edit modal
        $('#editPaymentModal').modal('show');
    });

    // Handle form submission for editing payment
    $('#editPaymentForm').on('submit', function(e) {
        e.preventDefault();

        // Show loading indicator
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait while we update the payment.',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Extract the numeric values from the inputs
        let amount = $('#edit-amount').val().replace(/Ghc\s*/, '').trim();
        let balance = $('#edit-balance').val().replace(/Ghc\s*/, '').trim();
        let arrears = $('#edit-arrears').val().replace(/Ghc\s*/, '').trim();

        // Prepare data for AJAX
        const formData = $(this).serialize() + '&amount_paid=' + amount + '&balance=' + balance + '&last_term_arrears=' + arrears;

        $.ajax({
            type: 'POST',
            url: 'edit_payment.php', // URL to your PHP script that handles the edit
            data: formData,
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Payment updated successfully!',
                        showCloseButton: true,
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    $('#editPaymentModal').modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: res.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed!',
                    text: 'Failed to update payment.',
                });
            }
        });
    });

    // Handle receipt button click
    $(document).on('click', '.receipt-btn', function(e) {
        e.preventDefault();
        const paymentId = $(this).data('id');
        window.location.href = 'view_payment.php?id=' + paymentId; // Redirect to the receipt page
    });

    // Search functionality
    $('#search').on('input', function() {
        var value = $(this).val().toLowerCase();
        $('#paymentsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
});
</script>

<!-- Footer -->
<footer class="bg-light text-center text-lg-start mt-5">
    <div class="text-center p-3">
        &copy; EduPro GT7
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>