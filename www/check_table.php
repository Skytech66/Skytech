<?php
require 'db_connection.php';

$sql = "SELECT name FROM sqlite_master WHERE type='table' AND name='students'";
$stmt = $pdo->query($sql);
$tableExists = $stmt->fetch();

if ($tableExists) {
    echo "✅ Table 'students' exists!";
} else {
    echo "❌ Table 'students' does not exist!";
}
?>
