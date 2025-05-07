<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the content type to JSON
header('Content-Type: application/json');

// Get the JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Extract parent message ID, admin ID, and message from the input
$parent_message_id = $input['parent_message_id'] ?? ''; // ID of the parent message being replied to
$admin_id = $input['admin_id'] ?? ''; // ID of the admin replying
$message = $input['message'] ?? ''; // Reply message

// Validate input
if (empty($parent_message_id) || empty($admin_id) || empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Parent message ID, admin ID, and message cannot be empty.']);
    exit;
}

// Database connection
$dbFile = 'messages.db'; // Path to your SQLite database
try {
    $conn = new PDO("sqlite:$dbFile");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Insert the admin reply
try {
    $stmt = $conn->prepare("INSERT INTO admin_replies (parent_message_id, admin_id, message) VALUES (:parent_message_id, :admin_id, :message)");
    $stmt->bindParam(':parent_message_id', $parent_message_id);
    $stmt->bindParam(':admin_id', $admin_id);
    $stmt->bindParam(':message', $message);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to send reply.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error executing query: ' . $e->getMessage()]);
}

// Close the connection
$conn = null;
?>