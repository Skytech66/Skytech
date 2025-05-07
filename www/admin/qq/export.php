<?php
// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=faculty_checkins.csv');

// Open output stream
$output = fopen('php://output', 'w');

// Connect to SQLite
$database = new SQLite3("faculty_checkin.db");

// Write CSV column headers
fputcsv($output, ['Name', 'ID', 'Class', 'Time', 'Date']);

// Fetch records
$query = "SELECT * FROM faculty_checkin ORDER BY checkin_date DESC, checkin_time DESC";
$result = $database->query($query);

// Write rows to CSV
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    fputcsv($output, [$row['name'], $row['teacher_id'], $row['class'], $row['checkin_time'], $row['checkin_date']]);
}

// Close output stream
fclose($output);
?>