<?php
// Database file path
$db_file = "nepto_classware.db";

// Create (or open) SQLite database
$conn = new SQLite3($db_file);

// Create parents table
$sql = "CREATE TABLE IF NOT EXISTS parents (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    parent_id TEXT UNIQUE NOT NULL,
    full_name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    phone TEXT NOT NULL,
    password TEXT NOT NULL
)";
$conn->exec($sql);

// Create students table
$sql = "CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    student_id TEXT UNIQUE NOT NULL,
    full_name TEXT NOT NULL,
    class TEXT NOT NULL,
    parent_id TEXT NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES parents(parent_id) ON DELETE CASCADE
)";
$conn->exec($sql);

// Create parent-student relationship table
$sql = "CREATE TABLE IF NOT EXISTS parent_student (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    parent_id TEXT NOT NULL,
    student_id TEXT NOT NULL,
    FOREIGN KEY (parent_id) REFERENCES parents(parent_id) ON DELETE CASCADE,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
)";
$conn->exec($sql);

// Success message
echo "Database and tables created successfully!";

// Close connection
$conn->close();
?>