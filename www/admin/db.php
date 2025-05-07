<?php
// db.php
$databasePath = 'C:/xampp/htdocs/EduPro/www/db/school.db'; // Path to your SQLite database
try {
    $pdo = new PDO("sqlite:$databasePath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>