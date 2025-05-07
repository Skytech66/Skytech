<?php
require_once "header.php";

// Direct SQLite connection
$database_name = "../db/school.db"; 
$conn = new SQLite3($database_name);

// Fetch class data based on the classid
$classid = $_GET['classid'];
$sql = "SELECT * FROM class WHERE classid = :classid";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':classid', $classid, SQLITE3_INTEGER);
$res = $stmt->execute();
$class = $res->fetchArray(SQLITE3_ASSOC);

if ($class) {
  echo json_encode($class); // Return the class data as a JSON response
} else {
  echo json_encode(null); // If no class is found, return null
}
?>
