<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'data.php'; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the POST request
    $id = $_POST['id'];
    $parent_phone = $_POST['parent_phone'];
    $parent_email = $_POST['parent_email'];

    // Prepare the SQL statement
    $stmt = $database->prepare("INSERT INTO parent_contacts (id, parent_phone, parent_email) VALUES (:id, :parent_phone, :parent_email)");
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
    $stmt->bindValue(':parent_phone', $parent_phone, SQLITE3_TEXT);
    $stmt->bindValue(':parent_email', $parent_email, SQLITE3_TEXT);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Parent contact added successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to add parent contact.']);
    }
}
?>