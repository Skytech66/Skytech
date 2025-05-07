<?php
session_start();

// SQLite database file
$db_file = 'school_fees_management.db';

try {
    // Connect to the SQLite database
    $conn = new PDO('sqlite:' . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if form is submitted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the form data
        $id = $_POST['student_id']; // Using 'id' instead of 'student_id'
        $password = $_POST['password'];

        // Prepare and execute SQL query to fetch parent account by id
        $stmt = $conn->prepare("SELECT * FROM parent_accounts WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $parent = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the parent account exists and verify password
        if ($parent && password_verify($password, $parent['password'])) {
            // Store id instead of student_id
            $_SESSION['id'] = $parent['id'];
            $_SESSION['parent_name'] = $parent['parent_name'];
            
            header("Location: parents_dashboard.php");
            exit;
        } else {
            $_SESSION['error'] = "Invalid ID or Password.";
            header("Location: login.php");
            exit;
        }
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>