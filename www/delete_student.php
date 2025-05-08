<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
    $result = $stmt->execute([$id]);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Student deleted successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete student"]);
    }
}
?>
