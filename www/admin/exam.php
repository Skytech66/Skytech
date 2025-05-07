<?php require_once "header.php"; ?>

<div class="container mt-5">
  <!-- Header Section -->
  <div class="ai-header">
    <h2 class="subject-title"><i class="fas fa-book"></i> Exam Control Unit</h2>
    <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage your exams efficiently</p>
  </div>

  <h2 class="text-center text-dark">
    <button class="btn btn-success shadow-sm" type="button" data-toggle="modal" data-target="#AddExam">
      <i class="fa fa-plus-circle"></i> Add New Exam
    </button>
  </h2>

  <div class="my-4">
    <div class="input-group">
      <input type="text" id="myInput" class="form-control shadow-sm" placeholder="Search exams by name..." onkeyup="TableFilter()">
      <div class="input-group-append">
        <span class="input-group-text bg-light text-dark">
          <i class="fa fa-search"></i>
        </span>
      </div>
    </div>
  </div>

  <div class="table-responsive rounded shadow-sm">
    <table id="pager" class="table table-striped table-bordered text-center">
      <thead class="bg-dark text-light">
        <tr>
          <th>Exam Name</th>
          <th>Year</th>
          <th>Date Added</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $sql = "SELECT * from exam";
          $res = $conn->query($sql);
          while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        ?>
          <tr>
            <td><?php echo $row['examname']; ?></td>
            <td><?php echo $row['year']; ?></td>
            <td><?php echo $row['date_added']; ?></td>
            <td>
              <span class="badge <?php echo $row['status'] == 'Active' ? 'badge-success' : 'badge-secondary'; ?>">
                <?php echo $row['status']; ?>
              </span>
            </td>
            <td>
              <button class="btn btn-warning btn-sm" title="Edit Exam" data-toggle="tooltip">
                <i class="fa fa-edit"></i>
              </button>
              <button class="btn btn-danger btn-sm" title="Delete Exam" onclick="NotAllowed()">
                <i class="fa fa-trash"></i>
              </button>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- Add Exam Modal -->
  <div class="modal fade" id="AddExam" tabindex="-1" role="dialog" aria-labelledby="AddExamLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-success text-light">
          <h5 class="modal-title">Add New Exam</h5>
          <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="action.php" method="POST">
            <input type="hidden" name="user" value="<?php echo $session_id; ?>" />
            <div class="form-group">
              <label for="examname">Exam Name:</label>
              <input type="text" class="form-control" id="examname" name="examname" required>
            </div>
            <div class="form-group">
              <label for="year">Year:</label>
              <input type="number" class="form-control" id="year" name="year" min="2022" required>
            </div>
            <div class="form-group">
              <label for="DateAdded">Date Added:</label>
              <input type="date" class="form-control" id="DateAdded" name="DateAdded" required>
            </div>
            <div class="form-group">
              <label for="status">Status:</label>
              <select class="form-control" id="status" name="status" required>
                <option value="">Select Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
              </select>
            </div>
                       <div class="text-center">
              <button type="submit" name="submit" value="addexam" class="btn btn-success btn-block">
                Add Exam
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- Optional Custom CSS -->
<style>
  body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
  }

  .table thead th {
    font-weight: bold;
    text-transform: uppercase;
  }

  .modal-header {
    border-bottom: none;
  }

  .modal-footer {
    border-top: none;
  }

  .form-control, .btn {
    border-radius: 0.25rem;
  }

  .badge {
    font-size: 0.9rem;
  }

  .btn i {
    margin-right: 0.25rem;
  }

  .table {
    margin-top: 1.5rem;
  }

  .input-group-text {
    border-radius: 0.25rem;
  }

  .pager-nav {
    text-align: center;
    margin-top: 20px;
  }

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
</style>