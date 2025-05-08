<?php
// db.php

// Database connection for SQLite
try {
    $pdo = new PDO("sqlite:students_records.db"); // Change to your actual database file
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die(json_encode(["success" => false, "message" => "Database Connection Failed: " . $e->getMessage()]));
}
?>