<?php 
require_once "header.php"; 

// Direct SQLite connection
$database_name = "../db/school.db"; 
$conn = new SQLite3($database_name); 
?>

<div class="container my-4">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-dark">Class Management</h2>
    <!-- Add New Class Button -->
    <button class="btn btn-info btn-sm" type="button" data-toggle="modal" data-target="#NewClass">
      <i class="fa fa-plus-circle"></i> Add New Class
    </button>
  </div>

  <!-- Search Bar -->
  <div class="input-group mb-3 shadow-sm">
    <input type="text" id="myInput" class="form-control" placeholder="Search class..." onkeyup="TableFilter()">
    <div class="input-group-append">
      <button class="btn btn-primary" type="button">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div>

  <!-- Class Table -->
  <div class="table-responsive">
    <table id="pager" class="table table-striped table-bordered text-center table-sm">
      <thead class="thead-dark">
        <tr>
          <th>Class Name</th>
          <th>Room No</th>
          <th>Class Teacher</th>
          <th>Fees</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * from class";
          $res = $conn->query($sql);
          while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        ?>
        <tr>
          <td><?php echo $row['classname']; ?></td>
          <td><?php echo $row['room_no']; ?></td>
          <td><?php echo $row['classteacher']; ?></td>
          <td><?php echo $row['fees']; ?></td>
          <td>
            <span class="badge <?php echo ($row['status'] == 'Active') ? 'badge-success' : 'badge-secondary'; ?>">
              <?php echo $row['status']; ?>
            </span>
          </td>
          <td class="text-center">
          
            <button class="btn btn-danger btn-sm" onclick="deleteClass(<?php echo $row['classid']; ?>)">
              <i class="fa fa-trash"></i>
            </button>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div id="pageNavPosition" class="pager-nav"></div>
</div>

<!-- Add New Class Modal -->
<div class="modal fade" id="NewClass" tabindex="-1" role="dialog" aria-labelledby="NewClassLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-light">
        <h5 class="modal-title" id="NewClassLabel">Add New Class</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="action.php" method="POST">
          <input type="hidden" name="user" value="<?php echo $session_id; ?>" />
          <div class="form-row">
            <div class="col-md-6">
              <label for="classname" class="col-form-label">Class Name</label>
              <input type="text" class="form-control" id="classname" name="classname" required>
            </div>
            <div class="col-md-6">
              <label for="classteacher" class="col-form-label">Class Teacher</label>
              <select class="form-control" id="classteacher" name="classteacher" required>
                <option selected value="">Select Teacher</option>
                <?php
                  $sql = "SELECT * from employees WHERE position = 'Teacher'";
                  $res = $conn->query($sql);
                  while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
                ?>
                  <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="col-md-6">
              <label for="room" class="col-form-label">Room Number</label>
              <input type="text" class="form-control" id="room" name="room">
            </div>
            <div class="col-md-6">
              <label for="date_added" class="col-form-label">Date Added</label>
              <input type="date" class="form-control" id="date_added" name="date_added">
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="col-md-6">
              <label for="fees" class="col-form-label">Fees</label>
              <input type="number" class="form-control" id="fees" name="fees" required>
            </div>
            <div class="col-md-6">
              <label for="status" class="col-form-label">Status</label>
              <select class="form-control" id="status" name="status" required>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" name="submit" value="addclass" class="btn btn-info btn-sm">Add Class</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Update Class Modal -->
<div class="modal fade" id="UpdateClass" tabindex="-1" role="dialog" aria-labelledby="UpdateClassLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-light">
        <h5 class="modal-title" id="UpdateClassLabel">Update Class</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="action.php" method="POST">
          <input type="hidden" name="user" value="<?php echo $session_id; ?>" />
          <div class="form-row">
            <div class="col-md-6">
              <label for="Uclassname" class="col-form-label">Class Name</label>
              <input type="text" class="form-control" id="Uclassname" name="Uclassname" required>
            </div>
            <div class="col-md-6">
              <label for="Uclassteacher" class="col-form-label">Class Teacher</label>
              <select class="form-control" id="Uclassteacher" name="Uclassteacher" required>
                <option value="">Select Teacher</option>
                <?php
                  $sql = "SELECT * from employees WHERE position = 'Teacher'";
                  $res = $conn->query($sql);
                  while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
                ?>
                  <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="col-md-6">
              <label for="Uroom" class="col-form-label">Room Number</label>
              <input type="text" class="form-control" id="Uroom" name="Uroom">
            </div>
            <div class="col-md-6">
              <label for="Udate_added" class="col-form-label">Date Added</label>
              <input type="date" class="form-control" id="Udate_added" name="Udate_added">
            </div>
          </div>

          <div class="form-row mt-3">
            <div class="col-md-6">
              <label for="Ufees" class="col-form-label">Fees</label>
              <input type="number" class="form-control" id="Ufees" name="Ufees">
            </div>
            <div class="col-md-6">
              <label for="Ustatus" class="col-form-label">Status</label>
              <select class="form-control" id="Ustatus" name="Ustatus">
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
          </div>

          <div class="modal-footer">
            <button type="submit" name="submit" value="updateclass" class="btn btn-info btn-sm">Update Class</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- Styles -->
<style>
  .btn-info {
    background-color: #17a2b8;
    border-color: #17a2b8;
    font-size: 0.9rem;
  }
  .btn-info:hover {
    background-color: #117a8b;
    border-color: #10707f;
  }
  .thead-dark {
    background-color: #343a40;
    color: white;
  }
  .table-striped tbody tr:nth-of-type(odd) {
    background-color: #f9f9f9;
  }
  .badge-success {
    background-color: #28a745;
  }
  .badge-secondary {
    background-color: #6c757d;
  }
  .modal-content {
    border-radius: 10px;
  }
</style>

<script>
  // JavaScript for editing class
  function edit_class(classid) {
    // Fetch the data from the database and fill the modal fields using AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_class.php?classid=" + classid, true);
    xhr.onreadystatechange = function() {
      if (xhr.readyState == 4 && xhr.status == 200) {
        var data = JSON.parse(xhr.responseText);
        document.getElementById('Uclassname').value = data.classname;
        document.getElementById('Uclassteacher').value = data.classteacher;
        document.getElementById('Uroom').value = data.room_no;
        document.getElementById('Udate_added').value = data.date_added;
        document.getElementById('Ufees').value = data.fees;
        document.getElementById('Ustatus').value = data.status;
      }
    };
    xhr.send();
  }

  // JavaScript for deleting a class
  function deleteClass(classid) {
    if (confirm("Are you sure you want to delete this class?")) {
      var xhr = new XMLHttpRequest();
      xhr.open("GET", "delete_class.php?classid=" + classid, true);
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          var response = xhr.responseText;
          if (response == "success") {
            alert("Class deleted successfully!");
            location.reload();  // Reload the page to reflect changes
          } else {
            alert("Error deleting class.");
          }
        }
      };
      xhr.send();
    }
  }
</script>

