<?php 
require_once "header.php"; 
require_once "db_connection.php"; // Include your database connection file
$conn = db_conn(); // Establish the database connection
?>

<div class="container mt-5">
  <!-- Header Section -->
  <div class="ai-header">
    <h2 class="subject-title"><i class="fas fa-book"></i> Subjects Management</h2>
    <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage your subjects efficiently</p>
  </div>

  <!-- Add New Subject Button -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <button class="btn btn-success btn-sm" type="button" data-toggle="modal" data-target="#NewSubject">
      <i class="fa fa-plus-circle"></i> Add New Subject
    </button>
  </div>

  <!-- Search Bar -->
  <div class="form-group mb-4">
    <input type="text" id="myInput" class="form-control form-control-lg" placeholder="Search subject..." onkeyup="TableFilter()">
  </div>

  <!-- Subject Table -->
  <div class="table-responsive">
    <table id="pager" class="table table-striped table-bordered table-sm text-nowrap" cellspacing="0" width="100%">
      <thead class="thead-light">
        <tr>
          <th>Subject</th>
          <th>Teacher</th>
          <th>Class</th>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * from subject";
          $res = $conn->query($sql);
          while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        ?>
        <tr>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['teacherid']); ?></td>
          <td><?php echo htmlspecialchars($row['classid']); ?></td>
          <td><?php echo htmlspecialchars($row['category']); ?></td>
          <td class="text-center">
            <!-- Edit Button -->
            <button class="btn btn-info btn-sm btn-rounded" id="<?php echo $row['subjectid']; ?>" data-toggle="modal" data-target="#UpdateSubject" onclick="edit_product(this.id)">
              <i class="fa fa-pencil"></i> Edit
            </button>
            <!-- Delete Button -->
            <button class="btn btn-danger btn-sm btn-rounded" id="<?php echo $row['subjectid']; ?>" onclick="delete_subject(this.id)">
              <i class="fa fa-trash"></i> Delete
            </button>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <div id="pageNavPosition" class="pager-nav"></div>
</div>

<!-- New Subject Modal -->
<div class="modal fade" id="NewSubject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-light">
        <h5 class="modal-title" id="exampleModalLabel">Add New Subject</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="action.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="user" value="<?php echo $session_id; ?>" />
          <div class="row">
            <div class="col-md-6">
              <label for="subjectname" class="col-form-label">Subject Name:</label>
              <input type="text" class="form-control" id="subjectname" name="subjectname" required>
            </div>
            <div class="col-md-6">
              <label for="teacher" class="col-form-label">Select Teacher:</label>
              <select class="form-control form-select-lg" id="teacher" name="teacher" required>
                <option value="" selected>Select Teacher</option>
                <?php
                  $sql = "SELECT * from employees where position = 'Teacher' order by id ASC";
                  $ret = $conn->query($sql);
                                    while ($rows = $ret->fetchArray(SQLITE3_ASSOC)) {
                ?>
                  <option value="<?php echo htmlspecialchars($rows["name"]); ?>"><?php echo htmlspecialchars($rows["name"]); ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-md-6">
              <label for="class" class="col-form-label">Select Class:</label>
              <select class="form-control form-select-lg" id="class" name="class" required>
                <option value="" selected>Select Class</option>
                <?php
                  $sql = "SELECT * from class order by classid ASC";
                  $ret = $conn->query($sql);
                  while ($rows = $ret->fetchArray(SQLITE3_ASSOC)) {
                ?>
                  <option value="<?php echo htmlspecialchars($rows["classname"]); ?>"><?php echo htmlspecialchars($rows["classname"]); ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="col-md-6">
              <label for="category" class="col-form-label">Category:</label>
              <select class="form-control form-select-lg" id="category" name="category" required>
                <option value="" selected>Select Category</option>
                <option value="Languages">Languages</option>
                <option value="Science">Science</option>
                <option value="Business">Business</option>
                <option value="Humanities">Humanities</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="submit" value="addsubject" class="btn btn-primary btn-lg">Add Subject</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Update Subject Modal -->
<div class="modal fade" id="UpdateSubject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-warning text-light">
        <h5 class="modal-title" id="exampleModalLabel">Update Subject</h5>
        <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="action.php" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="user" value="<?php echo $session_id; ?>" />
          <div class="row">
            <div class="col-md-6">
              <label for="Usubjectname" class="col-form-label">Subject Name:</label>
              <input type="text" class="form-control" id="Usubjectname" name="Usubjectname" required>
            </div>
            <div class="col-md-6">
              <label for="Uteacher" class="col-form-label">Select Teacher:</label>
              <select class="form-control form-select-lg" id="Uteacher" name="Uteacher" required>
                <option selected value="">Select Teacher</option>
                <!-- Teachers will be populated here -->
              </select>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-md-6">
              <label for="Uclass" class="col-form-label">Select Class:</label>
              <select class="form-control form-select-lg" id="Uclass" name="Uclass" required>
                <option value="" selected>Select Class</option>
                <!-- Classes will be populated here -->
              </select>
            </div>
            <div class="col-md-6">
              <label for="Ucategory" class="col-form-label">Category:</label>
              <select class="form-control form-select-lg" id="Ucategory" name="Ucategory" required>
                <option selected value="">Select Category</option>
                <option value="Languages">Languages</option>
                <option value="Science">Science</option>
                <option value="Business">Business</option>
                <option value="Humanities">Humanities</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="submit" value="updateproduct" class="btn btn-warning btn-lg">Update Subject</button>
          </div>
        </form>
		      </div>
    </div>
  </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- JavaScript for Deleting a Subject -->
<script>
    // Table Filter functionality
    function TableFilter() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('myInput');
        filter = input.value.toUpperCase();
        table = document.getElementById("pager");
        tr = table.getElementsByTagName("tr");

        for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header
            td = tr[i].getElementsByTagName("td");
            if (td) {
                txtValue = td[0].textContent || td[0].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Delete Subject functionality
    function delete_subject(subjectid) {
        if (confirm("Are you sure you want to delete this subject?")) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Send to the same file
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.status === 'success') {
                        // Remove the row from the table
                        var row = document.getElementById(subjectid).closest('tr');
                        row.parentNode.removeChild(row);
                        alert("Subject deleted successfully.");
                    } else {
                        alert("Error: " + response.message);
                    }
                }
            };
            xhr.send("action=delete&subjectid=" + subjectid);
        }
    }

    // Handle form submission for adding and updating subjects
    document.querySelector('form').onsubmit = function(event) {
        event.preventDefault(); // Prevent default form submission
        var formData = new FormData(this);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", this.action, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.status === 'success') {
                    // Reload the page or update the table dynamically
                    location.reload(); // Reload the page to see changes
                } else {
                    alert("Error: " + response.message);
                }
            }
        };
        xhr.send(formData);
    };
</script>

<!-- Custom Styles -->
<style>
  /* Header Styles */
  .ai-header {
      background: linear-gradient(135deg, #4F46E5 0%, #10B981 100%);
      color: white; /* Set text color to white for better contrast */
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
  }

  .subject-title {
      font-size: 2.5em; /* Increase font size */
      font-weight: bold; /* Make the text bold */
      margin: 0; /* Remove default margin */
  }

  .ai-header i {
      margin-right: 8px;
      font-size: 1.5em; /* Increase icon size */
  }

  /* Reduce the size of the 'Add New Subject' button */
  .btn-sm {
    padding: 0.4rem 1rem;
    font-size: 0.875rem;
  }

  /* Table and Column Styles */
  .table-striped tbody tr:hover {
    background-color: #f7f7f7;
  }
  .table thead th {
    background-color: #f1f1f1;
    color: #333;
    font-weight: 600;
  }
  .table td, .table th {
    padding: 0.75rem;
    vertical-align: middle;
  }
  .thead-light {
    background-color: #e9ecef;
  }
  .table-bordered {
    border: 1px solid #ddd;
  }
  .modal-content {
    border-radius: 10px;
  }
  .btn-rounded {
        border-radius: 50px;
  }
  .modal-header {
    border-bottom: 2px solid #ddd;
  }
  .modal-footer button {
    padding: 0.8rem 2rem;
  }
</style>

</body>
</html>