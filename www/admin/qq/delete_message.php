<?php
require_once "../include/functions.php";

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $message_id = $data['message_id'] ?? null;
    
    if (!$message_id) {
        echo json_encode(['success' => false, 'message' => 'Message ID required']);
        exit;
    }
    
    $conn = db_conn();
    $stmt = $conn->prepare("DELETE FROM sent_messages WHERE id = :id");
    $stmt->bindValue(':id', $message_id, SQLITE3_INTEGER);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database error']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>