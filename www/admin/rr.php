<?php
// SQLite database file
$db_file = 'school_fees_management.db';

try {
    $conn = new PDO('sqlite:' . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Start transaction
    $conn->beginTransaction();

    // Step 1: Create a new table with correct column names
    $conn->exec("CREATE TABLE IF NOT EXISTS new_parent_accounts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        parent_name TEXT NOT NULL,
        password TEXT NOT NULL,
        email TEXT NOT NULL,
        phone TEXT NOT NULL
    )");

    // Step 2: Copy data from the old table
    $conn->exec("INSERT INTO new_parent_accounts (id, parent_name, password, email, phone)
                 SELECT id, parent_name, password, email, phone FROM parent_accounts");

    // Step 3: Drop the old table
    $conn->exec("DROP TABLE parent_accounts");

    // Step 4: Rename the new table to the old name
    $conn->exec("ALTER TABLE new_parent_accounts RENAME TO parent_accounts");

    // Commit transaction
    $conn->commit();

    echo "Database migration completed successfully!";
} catch (PDOException $e) {
    // Rollback transaction if an error occurs
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>