<?php
// Database connection for SQLite
$db_file = 'C:/xampp/htdocs/MainProject/www/db/school.db'; // Adjusted to your database path
$conn = new SQLite3($db_file);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ensure the security check is in place
    if (isset($_POST['security_code']) && $_POST['security_code'] === '1234') {
        // SQL to delete all marks
        $sql = "DELETE FROM marks";
        
        if ($conn->exec($sql)) {
            // Redirect to the previous page with success
            header('Location: exams.php?success=2');
        } else {
            // Redirect to the previous page with error
            header('Location: exams.php?error=2');
        }
    } else {
        // Redirect to the previous page with error due to invalid security code
        header('Location: exams.php?error=3');
    }
} else {
    // Redirect if accessed directly
    header('Location: exams.php');
}
?>
