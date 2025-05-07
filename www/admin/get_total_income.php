<?php
header('Content-Type: application/json'); // Set the content type to JSON

// Connect to the SQLite database
$db = new SQLite3('C:\\xampp\\htdocs\\EduPro\\www\\db\\school.db'); // Update with your database path

// Check if the connection was successful
if (!$db) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Query to get the total income by summing the fees_paid
$query = 'SELECT SUM(fees_paid) AS total_income FROM student_fees';
$result = $db->query($query);

// Check if the query was successful
if (!$result) {
    echo json_encode(['error' => 'Query failed: ' . $db->lastErrorMsg()]);
    exit();
}

// Fetch the result
$row = $result->fetchArray(SQLITE3_ASSOC);
$total_income = $row['total_income'] !== null ? $row['total_income'] : 0; // Handle null case

// Return the total income as JSON
echo json_encode(['total_income' => $total_income]);

// Close the database connection
$db->close();
?>