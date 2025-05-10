<?php
// config.php - configuration file for database connection

// Define the path to the SQLite database
define('DB_PATH', __DIR__ . '/../db/school.db');

try {
    // Create a new PDO instance for SQLite connection
    $pdo = new PDO('sqlite:' . DB_PATH);

    // Set error reporting to Exception mode
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Handle connection error
    die('Connection failed: ' . $e->getMessage());
}
