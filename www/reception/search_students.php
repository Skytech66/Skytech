<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db.php'; // Ensure the path is correct

$search = isset($_GET['search']) ? $_GET['search'] : '';
$class = isset($_GET['class']) ? $_GET['class'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM students WHERE name LIKE ?";

if ($class) {
    $sql .= " AND class = ?";
}

$stmt = $pdo->prepare($sql); // Use PDO for prepared statements
if (!$stmt) {
    echo json_encode(['success' => false, 'message' => 'SQL prepare error: ' . $pdo->errorInfo()[2]]);
    exit;
}

$searchTerm = "%" . $search . "%";
if ($class) {
    $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(2, $class, PDO::PARAM_STR);
} else {
    $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
}

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'SQL execute error: ' . $stmt->errorInfo()[2]]);
    exit;
}

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['success' => true, 'students' => $students]);

$stmt = null; // Close the statement
$pdo = null; // Close the database connection
?>