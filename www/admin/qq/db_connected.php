<?php
// Connect to SQLite database
$db_file = 'bus_track.db'; // Path to your database
$db = new SQLite3($db_file);

// Check if connection is successful
if (!$db) {
    die("Database connection failed.");
}
?>