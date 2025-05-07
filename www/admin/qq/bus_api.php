<?php
// connect to SQLite database
$db = new SQLite3('students_records.db');

// get latest bus location
$query = "SELECT latitude, longitude FROM bus_location ORDER BY id DESC LIMIT 1";
$result = $db->query($query);
$row = $result->fetchArray(SQLITE3_ASSOC);

// return JSON response
header('Content-Type: application/json');
echo json_encode($row ?: ["latitude" => null, "longitude" => null]);