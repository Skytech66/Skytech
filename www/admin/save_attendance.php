<?php
header("Content-Type: application/json");

// Connect to SQLite database
$database = new SQLite3("attendance.db");

// Check if the connection was successful
if (!$database) {
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit;
}

// Get JSON input from the request
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || !isset($data["date"]) || !isset($data["attendance"])) {
    echo json_encode(["status" => "error", "message" => "Invalid data format"]);
    exit;
}

$date = $data["date"];
$attendanceRecords = $data["attendance"];

foreach ($attendanceRecords as $record) {
    $student_id = $record["id"];
    $status = $record["status"];

    // Check if attendance already exists for this student on this date
    $checkQuery = $database->prepare("SELECT id FROM attendance WHERE student_id = ? AND date = ?");
    $checkQuery->bindValue(1, $student_id, SQLITE3_INTEGER);
    $checkQuery->bindValue(2, $date, SQLITE3_TEXT);
    $result = $checkQuery->execute();

    if ($result->fetchArray()) {
        // Update existing record
        $updateQuery = $database->prepare("UPDATE attendance SET status = ? WHERE student_id = ? AND date = ?");
        $updateQuery->bindValue(1, $status, SQLITE3_TEXT);
        $updateQuery->bindValue(2, $student_id, SQLITE3_INTEGER);
        $updateQuery->bindValue(3, $date, SQLITE3_TEXT);
        $updateQuery->execute();
    } else {
        // Insert new attendance record
        $insertQuery = $database->prepare("INSERT INTO attendance (student_id, date, status) VALUES (?, ?, ?)");
        $insertQuery->bindValue(1, $student_id, SQLITE3_INTEGER);
        $insertQuery->bindValue(2, $date, SQLITE3_TEXT);
        $insertQuery->bindValue(3, $status, SQLITE3_TEXT);
        $insertQuery->execute();
    }
}

echo json_encode(["status" => "success", "message" => "Attendance saved successfully"]);

$database->close();
?>