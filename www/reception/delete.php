<?php
// delete.php
include 'db.php'; // Include your database connection file

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Parse the incoming request to get the student ID
    parse_str(file_get_contents("php://input"), $_DELETE);
    $studentId = $_DELETE['id'];

    // Debugging: Log the student ID
    error_log("Attempting to delete student with ID: " . $studentId);

    // Check if studentId is set and is a valid integer
    if (!isset($studentId) || !is_numeric($studentId)) {
        echo json_encode(['success' => false, 'message' => 'Invalid Student ID provided.']);
        exit;
    }

    // Prepare and execute the delete statement using PDO
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = :id");
    $stmt->bindParam(':id', $studentId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Student deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting student.']);
    }
} else {
    // If the request method is not DELETE, return an error
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>