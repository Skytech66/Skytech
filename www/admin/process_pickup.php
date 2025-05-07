<?php
// Database connection
$db = new SQLite3('pickup_requests.db');

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $child_name = $_POST['child_name'];
    $pickup_person = $_POST['pickup_person'];
    $phone_number = $_POST['phone_number'];
    $relation = $_POST['relation'];
    $pickup_date = $_POST['pickup_date'];
    $pickup_time = $_POST['pickup_time'];
    $otp = $_POST['otp'];

    // Insert data into database with a default 'pending' status
    $stmt = $db->prepare("INSERT INTO pickup_requests (child_name, pickup_person, phone_number, relation, pickup_date, pickup_time, otp, status) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->bindValue(1, $child_name, SQLITE3_TEXT);
    $stmt->bindValue(2, $pickup_person, SQLITE3_TEXT);
    $stmt->bindValue(3, $phone_number, SQLITE3_TEXT);
    $stmt->bindValue(4, $relation, SQLITE3_TEXT);
    $stmt->bindValue(5, $pickup_date, SQLITE3_TEXT);
    $stmt->bindValue(6, $pickup_time, SQLITE3_TEXT);
    $stmt->bindValue(7, $otp, SQLITE3_TEXT);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Pickup request saved successfully."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save request."]);
    }
}

// Close database connection
$db->close();
?>