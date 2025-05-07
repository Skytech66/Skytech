<?php
// Connect to the SQLite database
$db = new SQLite3('messages.db');

header('Content-Type: application/json');

if (isset($_GET['message_id'])) {
    $message_id = intval($_GET['message_id']);

    // Fetch replies where the admin (sender_id = 2) responded to the given message
    $stmt = $db->prepare("SELECT id, message, timestamp FROM messages WHERE reply_to = ? AND sender_id = 2 ORDER BY timestamp ASC");
    $stmt->bindValue(1, $message_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $replies = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $replies[] = [
            'id' => $row['id'],
            'message' => $row['message'],
            'timestamp' => $row['timestamp']
        ];
    }

    if (empty($replies)) {
        echo json_encode(['error' => 'No replies found for this message']);
    } else {
        echo json_encode($replies);
    }
} else {
    echo json_encode(['error' => 'Missing message_id parameter']);
}
?>
