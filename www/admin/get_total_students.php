<?php
header('Content-Type: application/json');

// Path to the SQLite database
$dbPath = 'C:\xampp\htdocs\EduPro\www\db\school.db'; // Adjust the path as necessary

try {
    // Create a new SQLite database connection
    $conn = new PDO("sqlite:$dbPath");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get total students
    $sql = "SELECT COUNT(*) as total_students FROM students"; // Counting all rows in the students table
    $stmt = $conn->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['total_students' => $row['total_students']]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

// Close the connection
$conn = null;
?>