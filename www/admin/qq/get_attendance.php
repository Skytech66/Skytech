<?php
require "dbm.php"; // Ensure database connection

header("Content-Type: application/json");

try {
    $stmt = $pdo->query("SELECT receiver_id, message, created_at, status FROM messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($messages);
} catch (PDOException $e) {
    echo json_encode(["error" => "Failed to fetch messages."]);
}
?>