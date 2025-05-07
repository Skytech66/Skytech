<?php
// Connect to SQLite database
$db = new SQLite3('students.db');

// Create table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS students (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    class TEXT NOT NULL,
    location TEXT NOT NULL
)");

// Get POST data
$name = $_POST['name'] ?? '';
$class = $_POST['class'] ?? '';
$location = $_POST['location'] ?? '';

// Validate input
if (empty($name) || empty($class) || empty($location)) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

// Insert student into database
$stmt = $db->prepare("INSERT INTO students (name, class, location) VALUES (:name, :class, :location)");
$stmt->bindValue(':name', $name, SQLITE3_TEXT);
$stmt->bindValue(':class', $class, SQLITE3_TEXT);
$stmt->bindValue(':location', $location, SQLITE3_TEXT);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Student added successfully!"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to add student."]);
}
?>