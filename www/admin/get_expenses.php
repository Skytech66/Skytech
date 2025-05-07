<?php
// get_expenses.php

// Database path
$dbPath = 'C:/xampp/htdocs/EduPro/www/db/school.db';

// Create a new SQLite3 database connection
try {
    $db = new SQLite3($dbPath);
} catch (Exception $e) {
    echo json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Query to get all expenses
$result = $db->query('SELECT * FROM expenses');

$expenses = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $expenses[] = $row;
}

// Return the expenses as JSON
echo json_encode($expenses);
?>