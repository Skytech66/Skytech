<?php
header("Content-Type: application/json");
require 'db.php'; // Ensure database connection

// Ensure it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Get admission number from request
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['admission_number'])) {
    echo json_encode(["success" => false, "message" => "No student selected for deletion"]);
    exit;
}

$admissionNumber = $data['admission_number'];

try {
    $stmt = $pdo->prepare("DELETE FROM students WHERE admission_number = ?");
    $stmt->execute([$admissionNumber]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Student deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Student not found"]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
