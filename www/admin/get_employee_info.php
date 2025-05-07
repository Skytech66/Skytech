<?php
require_once "../include/functions.php"; // Include the functions.php for db_conn()

if (isset($_POST['idno'])) {
    $idno = $_POST['idno'];

    // Get the database connection
    $conn = db_conn();

    // Query to fetch employee details based on the idno
    $sql = "SELECT * FROM employees WHERE idno = :idno";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':idno', $idno, SQLITE3_TEXT);
    $result = $stmt->execute();

    // Check if employee exists
    if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        // Return the employee's details as HTML
        echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
        echo "<p><strong>ID Number:</strong> " . htmlspecialchars($row['idno']) . "</p>";
        echo "<p><strong>Contact:</strong> " . htmlspecialchars($row['contact']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
        echo "<p><strong>Position:</strong> " . htmlspecialchars($row['position']) . "</p>";
        echo "<p><strong>Status:</strong> " . htmlspecialchars($row['status']) . "</p>";
    } else {
        echo "<p>Employee not found.</p>";
    }
}
?>
