<?php
header("Content-Type: application/json");

try {
    $db = new SQLite3('school_fees_management.db');

    $parent_id = $_GET['parent_id'] ?? '';
    if (empty($parent_id)) {
        echo json_encode(["error" => "Missing parent_id"]);
        exit;
    }

    $stmt = $db->prepare("SELECT sender_id, message FROM messages WHERE sender_id = :parent_id OR receiver_id = :parent_id ORDER BY created_at ASC");
    $stmt->bindValue(':parent_id', $parent_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $messages = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $messages[] = $row;
    }

    echo json_encode($messages);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
?>