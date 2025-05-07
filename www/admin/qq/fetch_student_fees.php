<?php
header('Content-Type: application/json');

// Connect to SQLite database
$db = new SQLite3('school_fees_management.db');

// Get student name from URL
$student_name = $_GET['student_name'] ?? '';

// Validate input
if (!$student_name) {
    echo json_encode(["error" => "Student name is required."]);
    exit;
}

// Prepare query to fetch student by name
$query = $db->prepare("SELECT * FROM payments WHERE student_name = :student_name");
if (!$query) {
    echo json_encode(["error" => "Database query error."]);
    exit;
}

$query->bindValue(':student_name', $student_name, SQLITE3_TEXT);
$result = $query->execute();

// Fetch student details
$student = $result->fetchArray(SQLITE3_ASSOC);

if ($student) {
    echo json_encode([
        "student_name" => $student['student_name'],
        "class" => $student['class'],
        "amount_paid" => number_format($student['amount_paid'], 2),
        "date_paid" => $student['date_paid'],
        "balance" => number_format($student['balance'], 2)
    ]);
} else {
    echo json_encode(["error" => "Student not found."]);
}
?>