<?php
// Database connection
$db = new SQLite3('C:/xampp/htdocs/MainProject/www/db/school.db');

// Set response header
header('Content-Type: application/json');

try {
    // Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!isset($data['id'], $data['student_name'], $data['class'], $data['fees_paid'], $data['date_paid'], $data['remaining_balance'], $data['last_term_arrears'])) {
        throw new Exception("Invalid input. Please provide all required fields.");
    }

    // Prepare and execute the SQL query
    $stmt = $db->prepare("UPDATE payments SET student_name = :student_name, class = :class, fees_paid = :fees_paid, date_paid = :date_paid, remaining_balance = :remaining_balance, last_term_arrears = :last_term_arrears WHERE id = :id");
    $stmt->bindValue(':id', $data['id'], SQLITE3_INTEGER);
    $stmt->bindValue(':student_name', $data['student_name'], SQLITE3_TEXT);
    $stmt->bindValue(':class', $data['class'], SQLITE3_TEXT);
    $stmt->bindValue(':fees_paid', $data['fees_paid'], SQLITE3_FLOAT);
    $stmt->bindValue(':date_paid', $data['date_paid'], SQLITE3_TEXT);
    $stmt->bindValue(':remaining_balance', $data['remaining_balance'], SQLITE3_FLOAT);
    $stmt->bindValue(':last_term_arrears', $data['last_term_arrears'], SQLITE3_FLOAT);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Payment updated successfully!"]);
    } else {
        throw new Exception("Failed to update payment.");
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

$db->close();
?>
