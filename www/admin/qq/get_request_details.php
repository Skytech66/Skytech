<?php
// Database connection
$db = new SQLite3('pickup_requests.db');

// Fetch the request ID from the URL
$requestId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the pickup request details
$request = $db->querySingle("SELECT * FROM pickup_requests WHERE id = $requestId", true);

if ($request) {
    echo json_encode($request);
} else {
    echo json_encode([]);
}
?>