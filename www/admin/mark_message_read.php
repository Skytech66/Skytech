<?php
header("Content-Type: application/json");

try {
    $db = new SQLite3('school_fees_management.db');

    // Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);
    $message_id = $data['message_id'] ?? '';

    if (empty($message_id)) {
        echo json_encode(["error" => "Missing message_id"]);
        exit;
    }

    // Update message status to 'read'
    $stmt = $db->prepare("UPDATE messages SET status = 'read' WHERE id = :message_id");
    $stmt->bindValue(':message_id', $message_id, SQLITE3_INTEGER);
    $stmt->execute();

    echo json_encode(["status" => "success"]);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>