<?php
$db = new SQLite3('pickup_requests.db');
$pendingCount = $db->querySingle("SELECT COUNT(*) FROM pickup_requests WHERE status = 'Pending'");
echo json_encode(['count' => $pendingCount]);
?>