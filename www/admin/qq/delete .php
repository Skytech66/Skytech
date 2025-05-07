<?php
// Connect to SQLite database
$db = new SQLite3('students.db');

// Get student ID from GET request
$studentId = $_GET['id'] ?? '';

// Validate input
if (empty($studentId)) {
    echo json_encode(["success" => false, "message" => "Invalid student ID."]);
    exit;
}

// Delete student
$stmt = $db->prepare("DELETE FROM students WHERE id = :id");
$stmt->bindValue(':id', $studentId, SQLITE3_INTEGER);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Student removed successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to remove student."]);
}
?>