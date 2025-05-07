<?php
require_once "db_connection.php"; // Include your database connection

if (isset($_POST['exam_id'])) {
    $exam_id = $_POST['exam_id'];

    // Prepare and execute the delete statement
    $stmt = $conn->prepare("DELETE FROM exam WHERE id = :id");
    $stmt->bindParam(':id', $exam_id);
    
    if ($stmt->execute()) {
        // Redirect back to the main page or show a success message
        header("Location: your_main_page.php?message=Exam deleted successfully");
        exit();
    } else {
        // Handle error
        echo "Error deleting exam.";
    }
} else {
    // Handle case where exam_id is not set
    echo "No exam ID provided.";
}
?>