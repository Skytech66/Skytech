<?php
try {
    // Connect to SQLite database
    $database = new SQLite3('school_fees_management.db', SQLITE3_OPEN_READWRITE);
    
    // Enable foreign key constraints
    $database->exec('PRAGMA foreign_keys = ON;');

    // Check if connection is successful
    if (!$database) {
        throw new Exception("Failed to connect to the database.");
    }
} catch (Exception $e) {
    die("Database Connection Error: " . $e->getMessage());
}
?>