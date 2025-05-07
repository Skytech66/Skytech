<?php
$dsn = "sqlite:database.sqlite";

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create "students" table if it doesn't exist
    $sql = "CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        class TEXT NOT NULL,
        age INTEGER NOT NULL,
        dob TEXT NOT NULL,
        admission_number TEXT UNIQUE NOT NULL,
        parent_name TEXT NOT NULL,
        contact TEXT NOT NULL,
        email TEXT NOT NULL,
        address TEXT NOT NULL,
        gender TEXT NOT NULL,
        image TEXT
    )";

    $pdo->exec($sql);
    echo "✅ Database and table created successfully!";
} catch (PDOException $e) {
    die("❌ Error: " . $e->getMessage());
}
?>
