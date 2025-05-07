<?php
// get_total_teachers.php

header('Content-Type: application/json');

// Database connection for SQLite
try {
    $pdo = new PDO("sqlite:C:\\xampp\\htdocs\\EduPro\\www\\db\\school.db"); // Adjust the path as necessary
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to get total teachers
    $sql = "SELECT COUNT(*) as total_teachers FROM employees WHERE position = 'Teacher'";
    $stmt = $pdo->query($sql);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(['total_teachers' => $row['total_teachers']]);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}

// Close the connection
$pdo = null;
?>