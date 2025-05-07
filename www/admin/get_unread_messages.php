<?php
// get_unread_messages.php
header('Content-Type: application/json');

// Simulate fetching unread messages count from a database
$unreadCount = 3; // Replace this with actual logic to get the count

echo json_encode(['unreadCount' => $unreadCount]);
?>