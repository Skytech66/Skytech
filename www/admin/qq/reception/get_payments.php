<?php
// get_payments.php

// Database connection
$db = new SQLite3('C:/xampp/htdocs/MainProject/www/db/school.db');

// Set response header
header('Content-Type: application/json');

try {
    // Fetch all payment records
    $result = $db->query("SELECT * FROM payments");

    // Prepare an array to store the payments
    $payments = [];
    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $payments[] = $row;
    }

    // Return the payments as JSON
    echo json_encode(["success" => true, "data" => $payments]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$db->close();
?>
