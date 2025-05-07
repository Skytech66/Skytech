<?php
header('Content-Type: application/json');

// Connect to the SQLite database
try {
    $db = new PDO('sqlite:messages.db'); // Ensure the path to your SQLite database is correct
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"));

// Check if the required fields are present
if (isset($data->message_id)) {
    $messageId = $data->message_id; // ID of the message to delete

    // Prepare and execute the SQL statement to delete the message
    $stmt = $db->prepare("DELETE FROM messages WHERE id = :message_id");
    $stmt->bindParam(':message_id', $messageId, PDO::PARAM_INT); // Bind as integer

    if ($stmt->execute()) {
        // Check if any row was affected
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Message not found.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete message.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>