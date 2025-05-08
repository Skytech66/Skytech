<?php
// Direct database connection setup
$dbFile = 'C:\xampp\htdocs\MainProject\www\db\school.db'; // Adjusted path
$conn = new SQLite3($dbFile);

try {
    // SQL query to delete all exam scores (delete all records in the marks table)
    $sql = "DELETE FROM marks";
    $conn->exec($sql);

    header("Location: index.php?success=1");
    exit();
} catch (Exception $e) {
    // Redirect to the main page if an error occurs
    header("Location: index.php?error=1");
    exit();
}
?>
