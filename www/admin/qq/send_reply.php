<?php
header('Content-Type: application/json');

// Connect to the SQLite database
try {
    $db = new PDO('sqlite:messages.db');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $e->getMessage()]);
    exit;
}

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"));

// Check if the required fields are present
if (isset($data->recipient) && isset($data->message)) {
    $recipient = $data->recipient; // The ID of the user receiving the reply (parent's ID)
    $message = $data->message; // The content of the reply

    // Set the sender ID to 1 for admin replies
    $senderId = 1; // Always use 1 as the sender ID for admin replies

    // Prepare and execute the SQL statement to insert the reply
    $stmt = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message, timestamp) VALUES (:sender_id, :receiver_id, :message, datetime('now'))");
    $stmt->bindParam(':sender_id', $senderId); // This will always be 1
    $stmt->bindParam(':receiver_id', $recipient); // This will be the ID of the user receiving the reply (parent)
    $stmt->bindParam(':message', $message);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        // Get detailed error information
        $errorInfo = $stmt->errorInfo();
        echo json_encode(['success' => false, 'message' => 'Failed to send reply: ' . $errorInfo[2]]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid input.']);
}
?>