<?php
try {
    // Connect to SQLite database
    $db = new PDO('sqlite:school_fees_management.db');

    // SQL to create the table
    $sql = "CREATE TABLE IF NOT EXISTS parents (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        parent_id TEXT UNIQUE NOT NULL,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        phone TEXT NOT NULL,
        password TEXT NOT NULL
    )";

    // Execute the query
    $db->exec($sql);

    echo "Parents table created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>