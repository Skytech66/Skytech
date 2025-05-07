<?php
// delete_expense.php
include 'db.php'; // Include the database connection

// Get the JSON input
$data = json_decode(file_get_contents("php://input"));

// Prepare the SQL statement
$stmt = $pdo->prepare("DELETE FROM expenses WHERE id = :id");

// Bind parameters
$stmt->bindParam(':id', $data->id);

// Execute the statement
if ($stmt->execute()) {
    echo json_encode(['message' => 'Expense deleted successfully.']);
} else {
    echo json_encode(['message' => 'Failed to delete expense.']);
}
?>