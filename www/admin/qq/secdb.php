<?php
// Database file path
$db_file = 'pickup_requests.db';

// Create (or open) SQLite database
$db = new SQLite3($db_file);

// Create the table if it doesn't exist
$query = "CREATE TABLE IF NOT EXISTS pickup_requests (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    child_name TEXT NOT NULL,
    pickup_person TEXT NOT NULL,
    phone_number TEXT NOT NULL,
    relation TEXT NOT NULL,
    pickup_date TEXT NOT NULL,
    pickup_time TEXT NOT NULL,
    otp TEXT NOT NULL
)";

if ($db->exec($query)) {
    echo "Database and table created successfully!";
} else {
    echo "Error creating table.";
}

// Close the database connection
$db->close();
?>