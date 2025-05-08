<?php
// Database connection
$db = new PDO('sqlite:C:\xampp\htdocs\MainProject\www\db\school.db');

// Fetch search and filter parameters
$search = $_GET['search'] ?? '';
$classFilter = $_GET['classFilter'] ?? '';

// Fetch classes
$classes = [
    'Basic One', 'Basic Two', 'Basic Three', 'Basic Four', 
    'Basic Five', 'Basic Six', 'Form One', 'Form Two', 'Form Three'
];

// Prepare the query for filtering
$query = "SELECT * FROM student_fees WHERE 1";
$params = [];

if ($search) {
    $query .= " AND (student_name LIKE ?)";
    $params[] = "%$search%";
}

if ($classFilter) {
    $query .= " AND class_name = ?";
    $params[] = $classFilter;
}

$stmt = $db->prepare($query);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group records by class
$groupedData = [];
foreach ($rows as $row) {
    $groupedData[$row['class_name']][] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Fees Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Student Fees Management</h1>

        <!-- Filters -->
        <div class="row my-4">
            <div class="col-md-6">
                <input type="text" id="search" class="form-control" placeholder="Search by student name" value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-4">
                <select id="classFilter" class="form-select">
                    <option value="">Filter by Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= $class ?>" <?= $class === $classFilter ? 'selected' : '' ?>><?= $class ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary w-100" onclick="addPayment()">Add Payment</button>
            </div>
        </div>

        <!-- Tables grouped by class -->
        <?php foreach ($classes as $class): ?>
            <h3 class="mt-4"><?= $class ?></h3>
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Student Name</th>
                        <th>Fees Paid</th>
                        <th>Date Paid</th>
                        <th>Remaining Balance</th>
                        <th>Last Term Arrears</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($groupedData[$class])): ?>
                        <?php foreach ($groupedData[$class] as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['student_name']) ?></td>
                                <td><?= htmlspecialchars($row['fees_paid']) ?></td>
                                <td><?= htmlspecialchars($row['payment_date']) ?></td>
                                <td><?= htmlspecialchars($row['remaining_balance']) ?></td>
                                <td><?= htmlspecialchars($row['last_term_arrears']) ?></td>
                                <td>
                                    <button class="btn btn-warning btn-sm">Edit</button>
                                    <button class="btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">No records found for <?= $class ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal" tabindex="-1" id="addPaymentModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_student.php" method="POST">
                        <!-- New Student Fields -->
                        <div class="mb-3">
                            <label for="student_name" class="form-label">Student Name</label>
                            <input type="text" name="student_name" id="student_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="class_name" class="form-label">Class</label>
                            <select name="class_name" id="class_name" class="form-select" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class ?>"><?= $class ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Payment Information -->
                        <div class="mb-3">
                            <label for="fees_paid" class="form-label">Fees Paid</label>
                            <input type="number" name="fees_paid" id="fees_paid" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="payment_date" class="form-label">Date Paid</label>
                            <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="remaining_balance" class="form-label">Remaining Balance</label>
                            <input type="number" name="remaining_balance" id="remaining_balance" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="last_term_arrears" class="form-label">Last Term Arrears</label>
                            <input type="number" name="last_term_arrears" id="last_term_arrears" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addPayment() {
            const modal = new bootstrap.Modal(document.getElementById('addPaymentModal'));
            modal.show();
        }

        document.getElementById('search').addEventListener('input', function () {
            const searchValue = this.value;
            const classFilter = document.getElementById('classFilter').value;
            window.location.href = `?search=${searchValue}&classFilter=${classFilter}`;
        });

        document.getElementById('classFilter').addEventListener('change', function () {
            const classFilter = this.value;
            const searchValue = document.getElementById('search').value;
            window.location.href = `?search=${searchValue}&classFilter=${classFilter}`;
        });
    </script>
</body>
</html>
