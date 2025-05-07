<?php
include 'db_connected.php'; // Include database connection

// Fetch students (wards) from the database
$result = $db->query("SELECT * FROM students ORDER BY id ASC");

$wards = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $wards[] = $row;
}

// Return as JSON
echo json_encode($wards);
?>