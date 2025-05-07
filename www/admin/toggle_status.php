<?php
require_once "db_connection.php"; // Include your database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_id = $_POST['exam_id'];
    $current_status = $_POST['current_status'];

    // Determine the new status
    $new_status = ($current_status == 'Active') ? 'Inactive' : 'Active';

    // Update the status in the database
    $sql = "UPDATE exam SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $exam_id);

    if ($stmt->execute()) {
        // Redirect back to the previous page or return a success message
        header("dashboard.php"); // Change to your actual page
        exit();
    } else {  
	        echo "Error updating record: " . $conn->error;
    }
} else {
    // If the request method is not POST, redirect to the previous page
    header("Location: your_previous_page.php"); // Change to your actual page
    exit();
}
?>