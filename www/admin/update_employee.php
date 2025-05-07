<?php
session_start(); // Start session if needed

// Database connection setup
$database_name = "../db/school.db"; // Adjust the path as needed
$conn = new SQLite3($database_name); // SQLite3 connection

if (!$conn) {
    die("Database connection failed.");
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get the form data
  $idno = $_POST['idno'];
  $name = $_POST['name'];
  $gender = $_POST['gender'];
  $contact = $_POST['contact'];
  $email = $_POST['email'];
  $position = $_POST['position'];

  // Prepare the SQL update query
  $sql = "UPDATE employees SET name = :name, gender = :gender, contact = :contact, email = :email, position = :position WHERE idno = :idno";
  $stmt = $conn->prepare($sql);
  
  // Bind the values to the SQL statement
  $stmt->bindValue(':idno', $idno, SQLITE3_TEXT);
  $stmt->bindValue(':name', $name, SQLITE3_TEXT);
  $stmt->bindValue(':gender', $gender, SQLITE3_TEXT);
  $stmt->bindValue(':contact', $contact, SQLITE3_TEXT);
  $stmt->bindValue(':email', $email, SQLITE3_TEXT);
  $stmt->bindValue(':position', $position, SQLITE3_TEXT);

  // Execute the query and check if it was successful
  if ($stmt->execute()) {
    // Redirect back to employee list with success message
    header("Location: employees.php?message=Employee updated successfully");
    exit();
  } else {
    // Redirect with error message
    header("Location: employees.php?message=Error updating employee");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Employee</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script>
        // JavaScript to hide the message after 3 seconds
        window.onload = function() {
            const messageElement = document.getElementById('success-message');
            if (messageElement) {
                setTimeout(function() {
                    messageElement.style.display = 'none';
                }, 3000); // Hide the message after 3 seconds
            }
        }
    </script>
</head>
<body>
    <div class="container my-4">
        <h2>Update Employee</h2>
        
        <?php if (isset($_GET['message'])): ?>
            <div id="success-message" class="alert alert-info">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <form action="update_employee.php" method="POST">
            <div class="form-group">
                <label for="idno">ID Number</label>
                <input type="text" class="form-control" id="idno" name="idno" required>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <input type="text" class="form-control" id="gender" name="gender" required>
            </div>
            <div class="form-group">
                <label for="contact">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="position">Position</label>
                <input type="text" class="form-control" id="position" name="position" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Employee</button>
        </form>
    </div>
</body>
</html>
