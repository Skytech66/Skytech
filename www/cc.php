<?php
// Create a new SQLite database file
$databasePath = 'school.db'; // Path to your database file
$pdo = new PDO("sqlite:$databasePath");

// Create tables if they do not exist
$pdo->exec("CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT, password TEXT, role TEXT)");
$pdo->exec("CREATE TABLE IF NOT EXISTS system_settings (id INTEGER PRIMARY KEY, system_locked INTEGER)");
?>