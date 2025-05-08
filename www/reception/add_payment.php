<?php
include 'data.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $class = trim($_POST['class']);
    $amount_paid = trim($_POST['amount_paid']);
    $date_paid = trim($_POST['date_paid']);
    $balance = isset($_POST['balance']) ? trim($_POST['balance']) : 0;
    $last_term_arrears = isset($_POST['last_term_arrears']) ? trim($_POST['last_term_arrears']) : 0;

    // Ensure required fields are not empty
    if (empty($name) || empty($class) || empty($amount_paid) || empty($date_paid)) {
        die("Error: Student name, class, amount, and date are required!");
    }

    // Ensure numeric values are valid
    if (!is_numeric($amount_paid) || !is_numeric($balance) || !is_numeric($last_term_arrears)) {
        die("Error: Amounts must be valid numbers!");
    }

    // Check if the `payments` table exists
    $table_check = $database->querySingle("SELECT COUNT(*) FROM sqlite_master WHERE type='table' AND name='payments'");
    if ($table_check == 0) {
        die("Database error: 'payments' table does not exist!");
    }

    // Prepare the SQL statement
    $stmt = $database->prepare("INSERT INTO payments (student_name, class, amount_paid, date_paid, balance, last_term_arrears) 
                                VALUES (:name, :class, :amount_paid, :date_paid, :balance, :last_term_arrears)");

    if (!$stmt) {
        die("SQL Error: " . $database->lastErrorMsg());
    }

    // Bind parameters securely
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':class', $class, SQLITE3_TEXT);
    $stmt->bindValue(':amount_paid', $amount_paid, SQLITE3_FLOAT);
    $stmt->bindValue(':date_paid', $date_paid, SQLITE3_TEXT);
    $stmt->bindValue(':balance', $balance, SQLITE3_FLOAT);
    $stmt->bindValue(':last_term_arrears', $last_term_arrears, SQLITE3_FLOAT);

    // Execute statement
    if ($stmt->execute()) {
        // Get last inserted ID for confirmation
        $receipt_id = $database->lastInsertRowID();

        if ($receipt_id) {
            echo "Payment successfully added! Payment ID: " . $receipt_id;
        } else {
            die("Error: Failed to retrieve last inserted ID.");
        }
    } else {
        die("Execution failed: " . $database->lastErrorMsg());
    }
}
?>
