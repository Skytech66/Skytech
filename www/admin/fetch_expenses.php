<?php
// fetch_expenses.php
include 'db.php'; // Include the database connection

// Prepare the SQL statement
$stmt = $pdo->prepare("SELECT * FROM expenses");
$stmt->execute();

// Fetch all expenses
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the expenses as JSON
echo json_encode($expenses);
?>