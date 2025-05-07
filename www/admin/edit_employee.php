<?php
require_once "../include/header.php"; 

// Get the employee ID from URL
if (isset($_GET['idno'])) {
  $idno = $_GET['idno'];
} else {
  // Redirect to employees page if no ID is provided
  header("Location: employees.php");
  exit();
}

// Fetch employee data
$sql = "SELECT * FROM employees WHERE idno = :idno";
$stmt = $conn->prepare($sql);
$stmt->bindValue(':idno', $idno, SQLITE3_TEXT);
$res = $stmt->execute();
$employee = $res->fetchArray(SQLITE3_ASSOC);

if (!$employee) {
  // Redirect to employees page if employee not found
  header("Location: employees.php");
  exit();
}
?>

<div class="container my-4">
  <h2 class="text-dark">Edit Employee</h2>
  <form action="update_employee.php" method="POST">
    <input type="hidden" name="idno" value="<?php echo $employee['idno']; ?>">

    <!-- Name -->
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" class="form-control" id="name" name="name" value="<?php echo $employee['name']; ?>" required>
    </div>

    <!-- ID Number -->
    <div class="form-group">
      <label for="idno">ID Number</label>
      <input type="text" class="form-control" id="idno" name="idno" value="<?php echo $employee['idno']; ?>" readonly>
    </div>

    <!-- Gender -->
    <div class="form-group">
      <label for="gender">Gender</label>
      <select class="form-control" id="gender" name="gender" required>
        <option value="Male" <?php echo ($employee['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
        <option value="Female" <?php echo ($employee['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
      </select>
    </div>

    <!-- Contact -->
    <div class="form-group">
      <label for="contact">Contact</label>
      <input type="text" class="form-control" id="contact" name="contact" value="<?php echo $employee['contact']; ?>" required>
    </div>

    <!-- Email -->
    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" class="form-control" id="email" name="email" value="<?php echo $employee['email']; ?>" required>
    </div>

    <!-- Position -->
    <div class="form-group">
      <label for="position">Position</label>
      <input type="text" class="form-control" id="position" name="position" value="<?php echo $employee['position']; ?>" required>
    </div>

    <!-- Submit Button -->
    <button type="submit" class="btn btn-teal">Update Employee</button>
  </form>
</div>

<?php require_once "../include/footer.php"; ?>
