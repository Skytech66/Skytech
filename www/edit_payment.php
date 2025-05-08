<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'data.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the data from the POST request
    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $student_name = isset($_POST['student_name']) ? $_POST['student_name'] : '';
    $class = isset($_POST['class']) ? $_POST['class'] : '';
    $amount_paid = isset($_POST['amount_paid']) ? $_POST['amount_paid'] : '';
    $date_paid = isset($_POST['date_paid']) ? $_POST['date_paid'] : '';
    $balance = isset($_POST['balance']) ? $_POST['balance'] : '';
    $last_term_arrears = isset($_POST['last_term_arrears']) ? $_POST['last_term_arrears'] : '';

    // Input validation (basic example)
    if (empty($student_name) || empty($class) || !is_numeric($amount_paid) || !is_numeric($balance) || !is_numeric($last_term_arrears)) {
        echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
        exit;
    }

    // Prepare the SQL statement
    $stmt = $database->prepare("UPDATE payments SET student_name = :student_name, class = :class, amount_paid = :amount_paid, date_paid = :date_paid, balance = :balance, last_term_arrears = :last_term_arrears WHERE id = :id");

    // Bind the parameters
    $stmt->bindValue(':student_name', $student_name, SQLITE3_TEXT);
    $stmt->bindValue(':class', $class, SQLITE3_TEXT);
    $stmt->bindValue(':amount_paid', $amount_paid, SQLITE3_FLOAT);
    $stmt->bindValue(':date_paid', $date_paid, SQLITE3_TEXT);
    $stmt->bindValue(':balance', $balance, SQLITE3_FLOAT);
    $stmt->bindValue(':last_term_arrears', $last_term_arrears, SQLITE3_FLOAT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    // Execute the statement
    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Return error response
        echo json_encode(['success' => false, 'message' => 'Failed to update payment.']);
    }
} else {
    // Return error response for invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>