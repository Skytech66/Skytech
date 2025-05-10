<?php require_once "header.php"; ?>

<div class="ai-container">
    <!-- Header Section -->
    <div class="ai-management-header">
        <div class="header-content">
            <h1 class="page-title">
                <span class="icon-wrapper"><i class="fas fa-book-open"></i></span>
                Subject Management
            </h1>
            <p class="page-description">Efficiently manage and organize all academic subjects</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Subject
            </button>
        </div>
    </div>

    <!-- Control Panel -->
    <div class="ai-control-panel">
        <!-- Search Bar -->
        <div class="search-container">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="subjectSearch" class="search-input" placeholder="Search subjects..." onkeyup="TableFilter()" aria-label="Search subjects">
            <div class="search-actions">
                <button class="btn btn-icon" title="Advanced Search">
                    <i class="fas fa-sliders-h"></i>
                </button>
            </div>
        </div>

        <!-- Filter Options -->
        <div class="filter-options">
            <div class="filter-group">
                <label>Filter by:</label>
                <select class="form-select">
                    <option selected>All Classes</option>
                    <option>Class 10</option>
                    <option>Class 11</option>
                    <option>Class 12</option>
                </select>
            </div>
            <div class="filter-group">
                <select class="form-select">
                    <option selected>All Teachers</option>
                    <option>Mathematics</option>
                    <option>Science</option>
                    <option>Languages</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="ai-data-table">
        <div class="table-responsive">
            <table id="subjectTable" class="table">
                <thead>
                    <tr>
                        <th class="sortable" onclick="sortTable(0)">
                            Subject <i class="fas fa-sort"></i>
                        </th>
                        <th class="sortable" onclick="sortTable(1)">
                            Class <i class="fas fa-sort"></i>
                        </th>
                        <th class="sortable" onclick="sortTable(2)">
                            Teacher <i class="fas fa-sort"></i>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "SELECT * FROM subject";
                        $res = $conn->query($sql);
                        while($row = $res->fetchArray(SQLITE3_ASSOC)) {
                    ?>
                        <tr>
                            <td>
                                <div class="subject-info">
                                    <div class="subject-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                    <div>
                                        <div class="subject-name"><?php echo htmlspecialchars($row['name']); ?></div>
                                        <div class="subject-code">SUB-<?php echo strtoupper(substr($row['name'], 0, 3)); ?>-<?php echo $row['id']; ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="class-badge">Class <?php echo htmlspecialchars($row['classid']); ?></span>
                            </td>
                            <td>
                                <div class="teacher-info">
                                    <div class="teacher-avatar">
                                        <i class="fas fa-user-tie"></i>
                                    </div>
                                    <div class="teacher-name">Teacher <?php echo htmlspecialchars($row['teacherid']); ?></div>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn btn-icon btn-edit" title="Edit">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    <button class="btn btn-icon btn-view" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-icon btn-delete" title="Delete" onclick="confirmDelete(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="ai-pagination">
        <div class="pagination-info">
            Showing 1 to 10 of 45 entries
        </div>
        <div class="pagination-controls">
            <button class="btn btn-pagination" disabled>
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="btn btn-pagination active">1</button>
            <button class="btn btn-pagination">2</button>
            <button class="btn btn-pagination">3</button>
            <button class="btn btn-pagination">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- JavaScript -->
<script>
    // Table Filter functionality
    function TableFilter() {
        const input = document.getElementById('subjectSearch');
        const filter = input.value.toUpperCase();
        const table = document.getElementById("subjectTable");
        const tr = table.getElementsByTagName("tr");

        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName("td")[0]; // Search only in first column
            if (td) {
                const txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }
        }
    }

    // Sort table functionality
    function sortTable(n) {
        // Implement sorting logic here
        console.log(`Sorting by column ${n}`);
    }

    // Confirm delete dialog
    function confirmDelete(subjectId) {
        if (confirm('Are you sure you want to delete this subject?')) {
            // AJAX call to delete the subject
            console.log(`Deleting subject with ID: ${subjectId}`);
            // Implement actual deletion logic here
        }
    }
</script>

<!-- Modern CSS Styles -->
<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --accent-color: #4cc9f0;
        --success-color: #4ad66d;
        --warning-color: #f8961e;
        --danger-color: #f94144;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --text-color: #2b2d42;
        --muted-color: #8d99ae;
        --border-color: #e9ecef;
        --surface-color: #ffffff;
        --hover-color: #f1f3f5;
    }

    body {
        font-family: 'Segoe UI', 'Roboto', sans-serif;
        background-color: #f5f7fa;
        color: var(--text-color);
        line-height: 1.6;
    }

    .ai-container {
        max-width: 1200px;
        margin: 20px auto;
        padding: 0 20px;
    }

    /* Header Styles */
    .ai-management-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        background: var(--surface-color);
        border-radius: 12px;
        padding: 25px 30px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .header-content {
        flex: 1;
    }

    .page-title {
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 8px 0;
        color: var(--dark-color);
        display: flex;
        align-items: center;
    }

    .icon-wrapper {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        margin-right: 16px;
        color: white;
    }

    .page-description {
        color: var(--muted-color);
        margin: 0;
        font-size: 15px;
    }

    /* Control Panel Styles */
    .ai-control-panel {
        background: var(--surface-color);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .search-container {
        position: relative;
        margin-bottom: 20px;
    }

    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted-color);
    }

    .search-input {
        width: 100%;
        padding: 12px 20px 12px 45px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
        outline: none;
    }

    .search-actions {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .filter-options {
        display: flex;
        gap: 15px;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-group label {
        font-size: 14px;
        color: var(--muted-color);
    }

    .form-select {
        padding: 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 6px;
        font-size: 14px;
        background-color: var(--surface-color);
    }

    /* Table Styles */
    .ai-data-table {
        background: var(--surface-color);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        color: var(--muted-color);
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
    }

    .table tbody td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background-color: var(--hover-color);
    }

    .sortable {
        cursor: pointer;
        transition: color 0.2s;
    }

    .sortable:hover {
        color: var(--primary-color);
    }

    /* Subject Info Styles */
    .subject-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .subject-icon {
        width: 40px;
        height: 40px;
        background-color: rgba(67, 97, 238, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
    }

    .subject-name {
        font-weight: 500;
    }

    .subject-code {
        font-size: 12px;
        color: var(--muted-color);
    }

    /* Badge Styles */
    .class-badge {
        display: inline-block;
        padding: 4px 10px;
        background-color: rgba(76, 201, 240, 0.1);
        color: var(--primary-color);
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    /* Teacher Info Styles */
    .teacher-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .teacher-avatar {
        width: 32px;
        height: 32px;
        background-color: rgba(248, 150, 30, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--warning-color);
    }

    .teacher-name {
        font-weight: 500;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        background: transparent;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-edit {
        color: var(--warning-color);
    }

    .btn-edit:hover {
        background-color: rgba(248, 150, 30, 0.1);
    }

    .btn-view {
        color: var(--primary-color);
    }

    .btn-view:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }

    .btn-delete {
        color: var(--danger-color);
    }

    .btn-delete:hover {
        background-color: rgba(249, 65, 68, 0.1);
    }

    /* Pagination Styles */
    .ai-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
        padding: 15px 20px;
        background: var(--surface-color);
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .pagination-info {
        color: var(--muted-color);
        font-size: 14px;
    }

    .pagination-controls {
        display: flex;
        gap: 8px;
    }

    .btn-pagination {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        background: var(--surface-color);
        color: var(--text-color);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-pagination:hover {
        background-color: var(--hover-color);
    }

    .btn-pagination.active {
        background-color: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    /* Button Styles */
    .btn {
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .btn-primary {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: var(--secondary-color);
        transform: translateY(-1px);
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {
        .ai-management-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }

        .filter-options {
            flex-direction: column;
            gap: 10px;
        }

        .ai-pagination {
            flex-direction: column;
            gap: 15px;
            align-items: center;
        }
    }
</style>