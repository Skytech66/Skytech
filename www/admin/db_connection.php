<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database file path using a relative path
$db_file = 'school_fees_management.db'; // This assumes the database file is in the same directory

// Check if the database file exists
if (!file_exists($db_file)) {
    die("Database file does not exist: " . $db_file);
}

// Create a new SQLite3 database connection
$database = new SQLite3($db_file);

// Check if the connection was successful
if (!$database) {
    die("Connection to database failed: " . $database->lastErrorMsg());
}
?>