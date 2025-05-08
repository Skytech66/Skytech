<?php
ob_start(); // Start output buffering to prevent any accidental output

include 'data.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Prepare the DELETE SQL statement
    $stmt = $database->prepare("DELETE FROM payments WHERE id = ?");
    if (!$stmt) {
        // Redirect with error message if preparing the statement fails
        header("Location: index.php?error=" . urlencode($database->lastErrorMsg()));
        exit;
    }

    // Bind the ID parameter and execute the statement
    $stmt->bindParam(1, $id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if ($result) {
        // Successful deletion, immediately redirect to index.php
        header("Location: index.php?message=" . urlencode("Record deleted successfully"));
        exit;
    } else {
        // Deletion failed, redirect with error message
        header("Location: index.php?error=" . urlencode($database->lastErrorMsg()));
        exit;
    }
} else {
    // No valid ID provided, redirect with an error message
    header("Location: index.php?error=" . urlencode("Invalid payment ID."));
    exit;
}
?>
