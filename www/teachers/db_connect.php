<?php
$dbFile = 'C:\xampp\htdocs\EduPro\www\students_records.db';

if (!file_exists($dbFile)) {
    die("Error: Database file not found at $dbFile");
}

try {
    $db = new PDO("sqlite:$dbFile");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
