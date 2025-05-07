<?php
header('Content-Type: application/json'); // Set the content type to JSON

// Connect to the SQLite database
$db = new SQLite3('C:\\xampp\\htdocs\\EduPro\\www\\db\\school.db'); // Update with your database path

// Check if the connection was successful
if (!$db) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Query to sum the total expenses
$sql = "SELECT SUM(amount) AS total_expenses FROM expenses"; // Adjust the table name and column name as needed
$result = $db->query($sql);

$total_expenses = 0;
if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $total_expenses = $row['total_expenses'] ? $row['total_expenses'] : 0; // Handle null values
}

// Return the total expenses as JSON
echo json_encode(['total_expenses' => $total_expenses]);

// Close the database connection
$db->close();
?>