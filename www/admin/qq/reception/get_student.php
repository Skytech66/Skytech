<?php
session_start();
include 'data.php'; // Include your database connection

if (isset($_GET['class'])) {
    $class = $_GET['class'];
    $students_query = "SELECT student_name, parent_email, parent_phone FROM payments WHERE class = :class";
    $stmt = $database->prepare($students_query);
    $stmt->bindValue(':class', $class, SQLITE3_TEXT);
    $results = $stmt->execute();

    $students = [];
    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $students[] = $row;
    }

    // Return the students as a JSON response
    echo json_encode($students);
} else {
    echo json_encode([]); // Return an empty array if no class is specified
}