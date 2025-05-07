<?php
// Connect to SQLite database
$db = new SQLite3('school_fees_management.db');

// Get Student ID from URL
$student_id = $_GET['student_id'] ?? '';

if (!$student_id) {
    die("Student ID is required.");
}

// Check if the query can be prepared
$query = $db->prepare("SELECT * FROM payments WHERE student_id = :student_id");

if (!$query) {
    die("Database query error.");
}

$query->bindValue(':student_id', $student_id, SQLITE3_TEXT);
$result = $query->execute();

// Fetch student details
$student = $result->fetchArray(SQLITE3_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <style>
        body {
            font-family: 'Segoe UI', system-ui;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        h2 {
            color: #2A5C82;
        }
        .details {
            background: #F4F6F8;
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1rem;
            text-align: left;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Student Details</h2>

        <?php if ($student): ?>
            <div class="details">
                <p><strong>Name:</strong> <?= htmlspecialchars($student['student_name']) ?></p>
                <p><strong>Class:</strong> <?= htmlspecialchars($student['class']) ?></p>
                <p><strong>Student ID:</strong> <?= htmlspecialchars($student_id) ?></p>
                <p><strong>Fees Paid:</strong> GHS <?= number_format($student['amount_paid'], 2) ?></p>
                <p><strong>Last Term Arrears:</strong> GHS <?= number_format($student['last_term_arrears'], 2) ?></p>
                <p><strong>Balance:</strong> GHS <?= number_format($student['balance'], 2) ?></p>
            </div>
        <?php else: ?>
            <p class="error">Student not found!</p>
        <?php endif; ?>

    </div>

</body>
</html>