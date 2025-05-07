<?php
// Direct SQLite connection
$database_name = "../db/school.db"; 
$conn = new SQLite3($database_name);

if (isset($_GET['classid'])) {
  $classid = $_GET['classid'];

  // Delete query
  $sql = "DELETE FROM class WHERE classid = :classid";
  $stmt = $conn->prepare($sql);
  $stmt->bindValue(':classid', $classid, SQLITE3_INTEGER);

  if ($stmt->execute()) {
    echo "success";  // Return success message if deleted
  } else {
    echo "error";  // Return error message if deletion failed
  }
}
?>
