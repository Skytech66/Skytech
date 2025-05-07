<?php
// add_expense.php

// Database path
$dbPath = 'C:/xampp/htdocs/EduPro/www/db/school.db';

// Create a new SQLite3 database connection
try {
    $db = new SQLite3($dbPath);
} catch (Exception $e) {
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Prepare and execute the insert statement
$stmt = $db->prepare('INSERT INTO expenses (expense_name, amount, expense_date, category) VALUES (:name, :amount, :date, :category)');
$stmt->bindValue(':name', $data['name'], SQLITE3_TEXT);
$stmt->bindValue(':amount', $data['amount'], SQLITE3_FLOAT);
$stmt->bindValue(':date', $data['date'], SQLITE3_TEXT);
$stmt->bindValue(':category', $data['category'], SQLITE3_TEXT);

if ($stmt->execute()) {
    echo json_encode(['message' => 'Expense added successfully.']);
} else {
    echo json_encode(['message' => 'Failed to add expense.']);
}
?>