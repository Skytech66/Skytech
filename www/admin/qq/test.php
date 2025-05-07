<?php
// Connect to SQLite database
$db = new SQLite3('school_fees_management.db');

// Query to count total messages
$query = "SELECT COUNT(*) as total FROM messages";
$result = $db->querySingle($query, true);

// Return total message count as JSON
echo json_encode(["total_messages" => $result['total']]);
?>