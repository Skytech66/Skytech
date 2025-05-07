<?php
// Connect to SQLite database
$db = new SQLite3('faculty_checkin.db');

// Create table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS faculty_checkin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    teacher_id TEXT NOT NULL,
    name TEXT NOT NULL,
    class TEXT NOT NULL,
    checkin_time TEXT NOT NULL,
    checkin_date TEXT NOT NULL
)");

// Check if data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $teacher_id = $_POST['teacher_id'] ?? '';
    $name = $_POST['name'] ?? '';
    $class = $_POST['class'] ?? '';
    $checkin_time = $_POST['checkin_time'] ?? '';
    $checkin_date = $_POST['checkin_date'] ?? '';

    if (!empty($teacher_id) && !empty($name) && !empty($class)) {
        // Insert check-in data
        $stmt = $db->prepare("INSERT INTO faculty_checkin (teacher_id, name, class, checkin_time, checkin_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bindValue(1, $teacher_id, SQLITE3_TEXT);
        $stmt->bindValue(2, $name, SQLITE3_TEXT);
        $stmt->bindValue(3, $class, SQLITE3_TEXT);
        $stmt->bindValue(4, $checkin_time, SQLITE3_TEXT);
        $stmt->bindValue(5, $checkin_date, SQLITE3_TEXT);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Check-in recorded successfully"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Database error"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing required fields"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>