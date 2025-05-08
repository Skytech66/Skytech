<?php require_once "header.php"; ?>

<div class="container my-4">
    <div class="ai-header">
        <h2 class="subject-title"><i class="fas fa-book"></i> Subject Management</h2>
        <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage subjects below</p>
    </div>

    <!-- Search Bar -->
    <div class="form-group mb-4">
        <input type="text" id="myInput" class="form-control form-control-lg search-bar" placeholder="Search subject..." onkeyup="TableFilter()" aria-label="Search">
    </div>
    
    <!-- Table -->
    <div class="table-responsive">
        <table id="pager" class="table table-hover table-striped table-bordered table-sm text-nowrap" cellspacing="0" width="100%">
            <thead class="table-dark">
                <tr>
                    <th class="th-sm">Subject</th>
                    <th class="th-sm">Class</th>
                    <th class="th-sm">Teacher</th>
                    <th class="th-sm">Category</th>
                    <th class="th-sm text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql ="SELECT * FROM subject";
                    $res = $conn->query($sql);
                    while($row = $res->fetchArray(SQLITE3_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['classid']); ?></td>
                        <td><?php echo htmlspecialchars($row['teacherid']); ?></td>
                        <td><?php echo htmlspecialchars($row['category']); ?></td>
                        <td class="text-center">
                            <button class="btn btn-info btn-sm" id="<?php echo $row['subjectid']; ?>" data-toggle="modal" data-target="#UpdateItem" onclick="edit_product(this.id)">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <button class="btn btn-danger btn-sm" id="<?php echo $row['subjectid']; ?>" onclick="NotAllowed()">
                                <i class="fa fa-times"></i> Delete
                            </button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination (if necessary) -->
    <div id="pageNavPosition" class="pager-nav"></div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- JavaScript Enhancements -->
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
</script>

<!-- Custom CSS for Enhanced Visuals -->
<style>
    :root {
        --primary: #4F46E5;
        --ai-accent: #10B981;
        --surface: #F8FAFC;
        --border: #E2E8F0;
    }
    body {
        font-family: 'Inter', system-ui;
        background-color: var(--surface);
        margin: 0;
        padding: 10px;
    }
    .ai-header {
        background: linear-gradient(135deg, var(--primary) 0%, var(--ai-accent) 100%);
        color: black; /* Set text color to black */
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .subject-title {
        font-size: 2.5em; /* Increase font size */
        font-weight: bold; /* Make the text bold */
        color: black; /* Set text color to black */
        margin: 0; /* Remove default margin */
    }
    .ai-header i {
        margin-right: 8px;
        color: white;
        font-size: 1.5em; /* Increase icon size */
    }
    .search-bar {
        border-radius: 25px; /* Rounded corners */
        padding: 10px 20px; /* Padding for a sleeker look */
        border: 1px solid #ced4da; /* Border color */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
        transition: border-color 0.3s ease, box-shadow 0.3s ease; /* Transition effects */
    }
    .search-bar:focus {
        border-color: var(--primary); /* Change border color on focus */
        box-shadow: 0 0 5px rgba(79, 70, 229, 0.5); /* Add shadow on focus */
        outline: none; /* Remove default outline */
    }
    .table th {
        transition: background-color 0.3s ease;
    }
    .table th:hover {
        background-color: #28a745;
        color: white;
    }
    .table td:hover {
        background-color: #eaf4f4;
    }
    .btn-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.2rem;
    }
    .btn-sm i {
        font-size: 0.875rem;
    }
    .table td .btn {
        margin: 0 3px;
    }
    .form-control-lg {
        border-radius: 0.375rem;
        padding: 0.75rem 1.25rem;
        font-size: 1.125rem;
        border: 1px solid #ced4da;
        transition: border-color 0.3s ease;
    }
    .form-control-lg:focus {
        border-color: #4F46E5;
        box-shadow: 0 0 5px rgba(79, 70, 229, 0.5);
    }
</style>