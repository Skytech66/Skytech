<?php
session_start();
include 'data.php';

// Fetch payments data
$query = "SELECT * FROM payments";
$results = $database->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Student Payments</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mt-4">Student Payments</h2>
    <table class="table table-striped">
        <thead class="bg-dark text-white">
            <tr>
                <th>Student Name</th>
                <th>Class</th>
                <th>Amount Paid</th>
                <th>Date Paid</th>
                <th>Balance</th>
                <th>Last Term Arrears</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $results->fetchArray(SQLITE3_ASSOC)) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['student_name']) ?></td>
                    <td><?= htmlspecialchars($row['class']) ?></td>
                    <td><?= 'Ghc ' . htmlspecialchars($row['amount_paid']) ?></td>
                    <td><?= htmlspecialchars($row['date_paid']) ?></td>
                    <td><?= 'Ghc ' . htmlspecialchars($row['balance']) ?></td>
                    <td><?= 'Ghc ' . htmlspecialchars($row['last_term_arrears']) ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script>
    window.print(); // Automatically trigger print dialog
</script>

</body>
</html>