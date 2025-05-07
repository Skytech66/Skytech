<?php
// Connect to SQLite
$database = new SQLite3("faculty_checkin.db");

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data["id"], $data["name"], $data["className"], $data["time"], $data["date"])) {
    $stmt = $database->prepare("INSERT INTO faculty_checkin (teacher_id, name, class, checkin_time, checkin_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bindValue(1, $data["id"], SQLITE3_TEXT);
    $stmt->bindValue(2, $data["name"], SQLITE3_TEXT);
    $stmt->bindValue(3, $data["className"], SQLITE3_TEXT);
    $stmt->bindValue(4, $data["time"], SQLITE3_TEXT);
    $stmt->bindValue(5, $data["date"], SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save check-in"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
}

// Close database
$database->close();
?>