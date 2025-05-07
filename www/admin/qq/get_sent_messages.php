<?php
header("Content-Type: application/json");
$db = new SQLite3('school_fees_management.db');

$query = "SELECT id, receiver_id, message, status, created_at FROM messages ORDER BY created_at DESC";
$result = $db->query($query);

$messages = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $messages[] = [
        "id" => $row["id"],
        "receiver_id" => $row["receiver_id"],
        "message" => $row["message"],
        "status" => $row["status"],
        "created_at" => $row["created_at"]
    ];
}

echo json_encode($messages);
$db->close();
?>