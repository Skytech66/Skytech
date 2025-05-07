<?php require_once "header.php"; ?>

<div class="container my-4">
  <!-- Header Section -->
  <div class="ai-header">
    <h2 class="subject-title"><i class="fas fa-book"></i> Employees Management</h2>
    <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage your employees efficiently</p>
  </div>

  <!-- Success/Error Message -->
  <?php if (isset($_GET['message'])): ?>
    <div class="alert alert-info" id="message">
      <?php echo htmlspecialchars($_GET['message']); ?>
    </div>
  <?php endif; ?>

  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="index.php?reg_employee" class="btn btn-teal btn-md shadow-sm">
      <i class="fa fa-user-plus"></i> Add Employee
    </a>
  </div>

  <!-- Search Bar -->
  <div class="input-group mb-3 shadow-sm">
    <input type="text" id="myInput" class="form-control" placeholder="Search by ID number..." onkeyup="TableFilter()">
    <div class="input-group-append">
      <button class="btn btn-teal" type="button">
        <i class="fa fa-search"></i>
      </button>
    </div>
  </div>

  <!-- Employee Table -->
  <div class="table-responsive shadow-sm">
    <table id="pager" class="table table-hover table-striped table-bordered text-center">
      <thead class="thead-dark">
        <tr>
          <th>Name</th>
          <th>ID Number</th>
          <th>Gender</th>
          <th>Contact</th>
          <th>Email</th>
          <th>Position</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * FROM employees";
          $res = $conn->query($sql);
          while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            $status = 'Active';  // Force status to "Active"
        ?>
        <tr>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['idno']; ?></td>
          <td><?php echo $row['gender']; ?></td>
          <td><?php echo $row['contact']; ?></td>
          <td><?php echo $row['email']; ?></td>
          <td><?php echo $row['position']; ?></td>
          <td>
            <span class="badge <?php echo ($status === 'Active') ? 'badge-teal' : 'badge-secondary'; ?>">
              <?php echo $status; ?>
            </span>
          </td>
          <td>
            <button class="btn btn-outline-info btn-sm" data-toggle="modal" data-target="#profile" onclick="getEmployeeInfo('<?php echo $row['idno']; ?>')">
              <i class="fa fa-eye"></i>
            </button>
            <a href="edit_employee.php?idno=<?php echo $row['idno']; ?>" class="btn btn-outline-dark btn-sm">
              <i class="fa fa-edit"></i>
            </a>
            <!-- Delete Button -->
            <a href="delete_employee.php?idno=<?php echo $row['idno']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this employee?');">
              <i class="fa fa-trash"></i>
            </a>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profile" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Employee Profile</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <!-- Dynamic employee profile will be loaded here -->
      </div>
    </div>
  </div>
</div>

<!-- Footer -->
<?php require_once "../include/footer.php"; ?>

<!-- Styles -->
<style>
  :root {
      --primary: #4F46E5; /* Primary color */
      --ai-accent: #10B981; /* Accent color */
      --surface: #F8FAFC; /* Background color */
      --border: #E2E8F0; /* Border color */
  }

  body {
    font-family: 'Roboto', sans-serif;
    background-color: var(--surface);
  }

  .ai-header {
      background: linear-gradient(135deg, var(--primary) 0%, var(--ai-accent) 100%);
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

  .btn-teal {
    background-color: var(--ai-accent);
    color: white;
    border: none;
  }

  .btn-teal:hover {
    background-color: #17a589; /* Adjust hover color if needed */
  }

  .table-hover tbody tr:hover {
    background-color: #f1f4f8;
  }

  .badge-teal {
    background-color: var(--ai-accent);
    font-size: 0.85rem;
    padding: 0.4em 0.6em;
    color: white;
  }

  .thead-dark th {
    background-color: #343a40;
    color: white;
  }

  .table {
    font-size: 0.9rem;
    margin-bottom: 0;
  }

  .modal-dialog {
    max-width: 90%;
  }

  .input-group {
    max-width: 500px;
    margin: 0 auto;
  }

  @media (max-width: 768px) {
    h2 {
      font-size: 1.5rem;
    }

    .btn-teal {
      font-size: 0.9rem;
      padding: 0.5rem 1rem;
    }

    .table {
      font-size: 0.85rem;
    }

    .modal-dialog {
      margin: 1rem auto;
    }
  }
</style>

<!-- JavaScript for Dynamic Profile Loading -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function getEmployeeInfo(idno) {
    $.ajax({
      url: 'get_employee_info.php', // This file will fetch the employee details
      method: 'POST',
      data: { idno: idno },
      success: function(response) {
        // Inject the response data into the modal
        $('#profile .modal-body').html(response);
      }
    });
  }

  // Hide the message after 3 seconds
  setTimeout(function() {
    var message = document.getElementById("message");
    if (message) {
      message.style.display = "none";
    }
  }, 3000);
</script>