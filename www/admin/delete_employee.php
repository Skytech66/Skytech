<?php
session_start(); // Start session if needed

// Database connection setup
$database_name = "../db/school.db"; // Adjust the path if necessary
$conn = new SQLite3($database_name); // SQLite3 connection

// Check if 'idno' is provided in the URL
if (isset($_GET['idno'])) {
    $idno = $_GET['idno'];

    // Prepare the SQL query to delete the employee record
    $sql = "DELETE FROM employees WHERE idno = :idno";
    $stmt = $conn->prepare($sql);

    // Bind the idno parameter to the query
    $stmt->bindValue(':idno', $idno, SQLITE3_TEXT);

    // Execute the query
    if ($stmt->execute()) {
        // Redirect to employees.php with a success message
        header("Location: employees.php?message=Employee deleted successfully");
        exit();
    } else {
        // Redirect to employees.php with an error message
        header("Location: employees.php?message=Error deleting employee");
        exit();
    }
} else {
    // If no idno is provided, redirect to employees.php
    header("Location: employees.php");
    exit();
}
?>
