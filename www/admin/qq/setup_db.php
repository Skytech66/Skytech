<?php
try {
    // Connect to SQLite database (it will create the file if it doesn't exist)
    $pdo = new PDO("sqlite:" . __DIR__ . "/attendance.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create students table
    $pdo->exec("CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL
    )");

    // Create attendance table
    $pdo->exec("CREATE TABLE IF NOT EXISTS attendance (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        student_id INTEGER NOT NULL,
        date TEXT NOT NULL,
        status TEXT CHECK(status IN ('present', 'late', 'absent', 'excused')),
        FOREIGN KEY (student_id) REFERENCES students(id)
    )");

    echo "Database setup complete!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>