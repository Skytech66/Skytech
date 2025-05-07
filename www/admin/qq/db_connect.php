<?php
// Database connection
$database = new SQLite3('student.db'); 

if (!$database) {
    die("Connection failed: " . $database->lastErrorMsg());
} else {
    echo "Connected to student.db successfully!";
}
?>