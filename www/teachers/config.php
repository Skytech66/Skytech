<?php
// config.php

// Define the path to the SQLite database using a relative path
define('DB_PATH', __DIR__ . '/../db/school.db');

// Optional: You can also include error handling for database connection
try {
    // Create a new SQLite3 database connection
    $conn = new SQLite3(DB_PATH);
} catch (Exception $e) {
    // Handle the error (e.g., log it or display a message)
    error_log("Database connection error: " . $e->getMessage());
    die("Could not connect to the database.");
}
?>
