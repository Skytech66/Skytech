<?php
include "../include/functions.php";
$conn = db_conn();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $exam = $_POST['exam'];
    $class = $_POST['class'];
    $subject = $_POST['subject']; // Subject remains plain
    $admno = $_POST['regno'];
    $count = count($admno);

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO marks (examname, class, midterm, endterm, subject, student, admno, average, remarks, position) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    for ($i = 0; $i < $count; $i++) {
        // Get individual values
        $mid = $_POST['midterm'][$i];
        $end = $_POST['endterm'][$i];
        $jina = $_POST['jina'][$i]; // Student name remains plain
        $regno = $admno[$i];
        $position = $_POST['position'][$i];

        // Set midterm and endterm to 0 if they are empty
        $midterm = !empty($mid) ? floatval($mid) : 0; // Store as float, default to 0
        $endterm = !empty($end) ? floatval($end) : 0; // Store as float, default to 0
        $remarks = null; // Default value for remarks

        // Calculate average only if both midterm and endterm are present
        $average = null;
        if ($midterm !== 0 || $endterm !== 0) { // Check if either is not zero
            $average = round(($midterm + $endterm)); // Calculate average and round to nearest integer

            // Assign remarks based on the average
            if ($average < 30) {
                $remarks = "E"; // Remarks remain plain
            } else if ($average < 40) {
                $remarks = "D"; // Remarks remain plain
            } else if ($average < 60) {
                $remarks = "C"; // Remarks remain plain
            } else if ($average <= 80) {
                $remarks = "B"; // Remarks remain plain
            } else if ($average <= 100) {
                $remarks = "A"; // Remarks remain plain
            } else {
                $remarks = "Invalid"; // Remarks remain plain
            }
        }

        // Execute the prepared statement only if there are values for midterm or endterm
        if ($midterm !== 0 || $endterm !== 0) {
            // Bind parameters using bindValue
            $stmt->bindValue(1, $exam);
            $stmt->bindValue(2, $class);
            $stmt->bindValue(3, $midterm); // Midterm is now plain
            $stmt->bindValue(4, $endterm); // Endterm is now plain
            $stmt->bindValue(5, $subject); // Subject is now plain
            $stmt->bindValue(6, $jina); // Student name is now plain
            $stmt->bindValue(7, $regno);
            $stmt->bindValue(8, $average); // Average is now plain and rounded
            $stmt->bindValue(9, $remarks); // Remarks are now plain
            $stmt->bindValue(10, $position);

            $result = $stmt->execute();

            // Check for errors
            if (!$result) {
                echo "Error: " . $conn->lastErrorMsg();
            }
        }
    }

    // Close the statement
    $stmt->close();

    // Redirect or show success message
    echo "<script>alert('Marks added successfully!'); window.location = 'index.php?exams';</script>";
} else {
    // If the request method is not POST, redirect or show an error
    echo "<script>alert('Marks added successfully!'); window.location = 'index.php?exams';</script>";
}
?>