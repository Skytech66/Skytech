<?php
$db = new SQLite3('nepto_classware.db');
$data = json_decode(file_get_contents("backup_parents.json"), true);

foreach ($data as $row) {
    $stmt = $db->prepare("INSERT INTO parents (name, contact, student_info, password) VALUES (:name, :contact, :student_info, :password)");
    $stmt->bindValue(':name', $row['name'], SQLITE3_TEXT);
    $stmt->bindValue(':contact', $row['contact'], SQLITE3_TEXT);
    $stmt->bindValue(':student_info', $row['student_info'], SQLITE3_TEXT);
    $stmt->bindValue(':password', $row['password'], SQLITE3_TEXT);
    $stmt->execute();
}

echo "Data restored successfully.";
?>