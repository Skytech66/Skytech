<?php
header("Content-Type: application/json");

try {
    $pdo = new PDO("sqlite:students_records.db");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $selectedClass = isset($_GET['class']) ? trim($_GET['class']) : '';

    if (!empty($selectedClass)) {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE class = ?");
        $stmt->execute([$selectedClass]);
    } else {
        $stmt = $pdo->query("SELECT * FROM students");
    }

    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(["success" => true, "students" => $students]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
