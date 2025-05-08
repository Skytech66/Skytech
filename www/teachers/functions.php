<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include other necessary files
require_once "header.php"; 
require_once "db_connection.php";

// Your existing code...
?>