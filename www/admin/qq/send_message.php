<?php
header("Content-Type: application/json");

try {
    // Connect to SQLite
    $db = new SQLite3('school_fees_management.db');

    // Get input data
    $sender_id = $_POST['sender_id'] ?? '';
    $receiver_id = $_POST['receiver_id'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validate
    if (empty($sender_id) || empty($receiver_id) || empty($message)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit;
    }

    // Insert into DB
    $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message, status, created_at) VALUES (:sender, :receiver, :message, 'unread', datetime('now'))");
    $stmt->bindValue(':sender', $sender_id, SQLITE3_INTEGER);
    $stmt->bindValue(':receiver', $receiver_id, SQLITE3_INTEGER);
    $stmt->bindValue(':message', $message, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to insert into DB"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>