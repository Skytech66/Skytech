<?php
// Connect to SQLite database (it will be created if it doesn't exist)
$database = new SQLite3("faculty_checkin.db");

// Create faculty check-in table
$query = "CREATE TABLE IF NOT EXISTS faculty_checkin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    teacher_id TEXT NOT NULL,
    name TEXT NOT NULL,
    class TEXT NOT NULL,
    checkin_time TEXT NOT NULL,
    checkin_date TEXT NOT NULL
)";

if ($database->exec($query)) {
    echo "Faculty Check-In Table Created Successfully";
} else {
    echo "Error Creating Table: " . $database->lastErrorMsg();
}

// Close the database connection
$database->close();
?>