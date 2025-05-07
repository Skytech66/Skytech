<?php
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['name']) && isset($data['class']) && isset($data['location'])) {
    $db = new SQLite3('students.db');
    $stmt = $db->prepare("INSERT INTO students (name, class, location) VALUES (:name, :class, :location)");
    $stmt->bindValue(':name', $data['name'], SQLITE3_TEXT);
    $stmt->bindValue(':class', $data['class'], SQLITE3_TEXT);
    $stmt->bindValue(':location', $data['location'], SQLITE3_TEXT);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Student added successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add student"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid input"]);
}
?>