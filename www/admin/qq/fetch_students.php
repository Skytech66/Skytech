<?php
// Connect to SQLite database
$db = new SQLite3('students.db');

// Fetch all students
$result = $db->query("SELECT * FROM students");

// Store results in an array
$students = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $students[] = $row;
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($students);
?>