<?php
// Connect to SQLite database
$db = new SQLite3('students.db');

// Create table if not exists
$db->exec("CREATE TABLE IF NOT EXISTS bus_location (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    latitude REAL NOT NULL,
    longitude REAL NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Get POST data
$latitude = $_POST['latitude'] ?? '';
$longitude = $_POST['longitude'] ?? '';

// Validate input
if (!is_numeric($latitude) || !is_numeric($longitude)) {
    echo json_encode(["success" => false, "message" => "Invalid coordinates."]);
    exit;
}

// Insert bus location into database
$stmt = $db->prepare("INSERT INTO bus_location (latitude, longitude) VALUES (:latitude, :longitude)");
$stmt->bindValue(':latitude', $latitude, SQLITE3_FLOAT);
$stmt->bindValue(':longitude', $longitude, SQLITE3_FLOAT);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Bus location updated."]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update location."]);
}
?>