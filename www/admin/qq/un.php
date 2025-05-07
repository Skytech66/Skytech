<?php
// SQLite database file
$db_file = 'school_fees_management.db';  // Path to your SQLite database file

// Create a new SQLite connection
try {
    $conn = new PDO('sqlite:' . $db_file);
    // Set error mode to exception for better error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // SQL query to create the parent_accounts table
    $sql = "CREATE TABLE IF NOT EXISTS parent_accounts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        parent_name TEXT NOT NULL,
        student_id INTEGER NOT NULL,
        password TEXT NOT NULL,
        email TEXT,
        phone TEXT
    )";

    // Execute the query
    $conn->exec($sql);
    echo "Table 'parent_accounts' created successfully.";

} catch (PDOException $e) {
    // Handle error if connection fails or query fails
    echo "Error: " . $e->getMessage();
}

// Close the SQLite connection
$conn = null;
?>