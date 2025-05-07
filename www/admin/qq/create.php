<?php
// Connect to SQLite database
$db = new SQLite3('school_fees_management.db');

// Begin transaction
$db->exec('BEGIN TRANSACTION;');

try {
    // Rename the existing messages table
    $db->exec("ALTER TABLE messages RENAME TO old_messages");

    // Create the new messages table with the correct structure
    $db->exec("CREATE TABLE messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        sender_id INTEGER NOT NULL,
        receiver_id INTEGER NOT NULL,
        message TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        status TEXT DEFAULT 'unread'
    )");

    // Copy data from old table to new table, handling missing timestamp column
    $db->exec("INSERT INTO messages (id, sender_id, receiver_id, message, created_at)
               SELECT id, sender_id, receiver_id, message, COALESCE(timestamp, CURRENT_TIMESTAMP) FROM old_messages");

    // Drop the old table
    $db->exec("DROP TABLE old_messages");

    // Commit transaction
    $db->exec('COMMIT;');

    echo "Messages table altered successfully.";
} catch (Exception $e) {
    // Rollback in case of error
    $db->exec('ROLLBACK;');
    echo "Failed to alter messages table: " . $e->getMessage();
}

// Close database connection
$db->close();
?>