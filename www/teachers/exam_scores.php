<?php include 'config.php'; // Include the config file ?>

<?php
try {
    // Create a new PDO instance for SQLite
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch distinct classes from the marks table
    $classStmt = $pdo->query("SELECT DISTINCT class FROM marks");
    $classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch distinct subjects from the marks table
    $subjectStmt = $pdo->query("SELECT DISTINCT subject FROM marks");
    $subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Scores Dashboard | Academic Analytics Pro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-light: #e0e7ff;
            --secondary-color: #3730a3;
            --accent-color: #7c3aed;
            --light-color: #f8fafc;
            --dark-color: #0f172a;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --border-radius: 10px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            --sidebar-width: 280px;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }
        
        .dashboard-container {
            max-width: 98%;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header Styles */
        .dashboard-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: var(--border-radius);
            padding: 25px 30px;
            margin-bottom: 30px;
            box-shadow: var(--box-shadow);
            position: relative;
            overflow: hidden;
            border: none;
            background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjAzKSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
        }
        
        .dashboard-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--accent-color), var(--success-color));
        }
        
        .dashboard-title {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            letter-spacing: -0.5px;
        }
        
        .dashboard-title i {
            margin-right: 15px;
            font-size: 1.8rem;
            color: rgba(255, 255, 255, 0.9);
        }
        
        .dashboard-subtitle {
            font-size: 0.95rem;
            opacity: 0.9;
            font-weight: 400;
        }
        
        /* Navigation and Controls */
        .nav-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 20px;
        }
        
        .filter-controls {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        
        .action-controls {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .nav-link {
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-icon {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        /* Form Elements */
        select, input {
            border: 1px solid #e2e8f0;
            border-radius: var(--border-radius);
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: var(--transition);
            box-shadow: none;
            background-color: white;
        }
        
        select:focus, input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
            outline: none;
        }
        
        .form-select {
            min-width: 200px;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
            background-position: right 0.75rem center;
            background-size: 16px 12px;
            cursor: pointer;
        }
        
        /* Table Styles */
        .table-container {
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            overflow: hidden;
            margin-bottom: 30px;
            border: 1px solid #f1f5f9;
        }
        
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        
        .table thead th {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            padding: 15px;
            border: none;
            position: sticky;
            top: 0;
            z-index: 10;
            white-space: nowrap;
        }
        
        .table tbody tr {
            transition: var(--transition);
            position: relative;
        }
        
        .table tbody tr:hover {
            background-color: var(--primary-light);
        }
        
        .table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        .table tbody tr:nth-child(even):hover {
            background-color: var(--primary-light);
        }
        
        .table td {
            padding: 14px 16px;
            vertical-align: middle;
            border-top: 1px solid #f1f5f9;
            font-size: 0.9rem;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* Editable Cells */
        .editable {
            cursor: pointer;
            transition: var(--transition);
            padding: 5px 8px;
            border-radius: 4px;
            position: relative;
            display: inline-block;
            min-width: 50px;
        }
        
        .editable:hover {
            background-color: rgba(79, 70, 229, 0.1);
        }
        
        .editable-input {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 6px 10px;
            width: 100%;
            font-size: inherit;
            font-family: inherit;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        
        .commit-btn {
            display: none;
            margin-left: 8px;
            color: var(--success-color);
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.9rem;
        }
        
        .commit-btn:hover {
            transform: scale(1.1);
        }
        
        /* Position Badges */
        .position-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.75rem;
            min-width: 40px;
            text-align: center;
            letter-spacing: -0.5px;
        }
        
        .position-1 {
            background-color: rgba(255, 215, 0, 0.2);
            color: #b8860b;
            border: 1px solid rgba(255, 215, 0, 0.3);
        }
        
        .position-2 {
            background-color: rgba(192, 192, 192, 0.2);
            color: #808080;
            border: 1px solid rgba(192, 192, 192, 0.3);
        }
        
        .position-3 {
            background-color: rgba(205, 127, 50, 0.2);
            color: #8b4513;
            border: 1px solid rgba(205, 127, 50, 0.3);
        }
        
        .position-other {
            background-color: rgba(233, 236, 239, 0.5);
            color: #495057;
            border: 1px solid #e9ecef;
        }
        
        /* Action Buttons */
        .btn-action {
            border: none;
            background: none;
            padding: 6px 10px;
            border-radius: 4px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.85rem;
            cursor: pointer;
        }
        
        .btn-delete {
            color: var(--danger-color);
            background-color: rgba(239, 68, 68, 0.1);
        }
        
        .btn-delete:hover {
            background-color: rgba(239, 68, 68, 0.2);
            transform: translateY(-1px);
        }
        
        .btn-export {
            background-color: var(--success-color);
            color: white;
            padding: 9px 18px;
            border-radius: var(--border-radius);
            font-weight: 500;
            border: none;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-export:hover {
            background-color: #0d9f6e;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        }
        
        /* Search Box */
        .search-box {
            position: relative;
            max-width: 300px;
            min-width: 200px;
        }
        
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.9rem;
        }
        
        .search-input {
            padding-left: 38px;
            width: 100%;
            background-color: white;
            border-radius: var(--border-radius);
        }
        
        /* Score Indicators */
        .score-high {
            color: var(--success-color);
            font-weight: 600;
        }
        
        .score-medium {
            color: var(--warning-color);
            font-weight: 600;
        }
        
        .score-low {
            color: var(--danger-color);
            font-weight: 600;
        }
        
        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 15px;
            }
            
            .dashboard-header {
                padding: 20px;
            }
            
            .dashboard-title {
                font-size: 1.8rem;
            }
            
            .nav-controls {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }
            
            .filter-controls {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }
            
            .form-select, .search-box {
                width: 100%;
                max-width: 100%;
            }
            
            .action-controls {
                width: 100%;
                justify-content: space-between;
                margin-top: 10px;
            }
            
            .nav-link {
                padding: 8px 12px;
                font-size: 0.85rem;
            }
            
            .table-container {
                border-radius: 0;
                margin-left: -15px;
                margin-right: -15px;
                width: calc(100% + 30px);
            }
            
            .table td {
                padding: 12px 10px;
                font-size: 0.85rem;
            }
        }
        
        /* Animations */
        .fade-in {
            animation: fadeIn 0.4s ease-out forwards;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 40px 20px;
            background-color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 30px;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(79, 70, 229, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 0.8s ease-in-out infinite;
            margin: 0 auto;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }
        
        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 15px;
        }
        
        /* Tooltip Customization */
        .tooltip {
            font-family: inherit;
            font-size: 0.8rem;
        }
        
        .bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before, 
        .bs-tooltip-top .tooltip-arrow::before {
            border-top-color: var(--primary-color);
        }
        
        .tooltip-inner {
            background-color: var(--primary-color);
            padding: 6px 12px;
            border-radius: 4px;
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Premium Enhancements */
        .grade-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.8rem;
            min-width: 40px;
            text-align: center;
        }

        .grade-A {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .grade-B {
            background-color: rgba(59, 130, 246, 0.1);
            color: var(--info-color);
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .grade-C {
            background-color: rgba(245, 158, 11, 0.1);
            color: var(--warning-color);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .grade-D {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .grade-F {
            background-color: rgba(156, 163, 175, 0.1);
            color: #6b7280;
            border: 1px solid rgba(156, 163, 175, 0.2);
        }

        .stats-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: var(--box-shadow);
            border-left: 4px solid var(--primary-color);
        }

        .stats-card h5 {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .stats-card .value {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
        }

        .stats-card .change {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 5px;
        }

        .change.positive {
            color: var(--success-color);
        }

        .change.negative {
            color: var(--danger-color);
        }

        /* Floating Action Button */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.3);
            cursor: pointer;
            transition: var(--transition);
            z-index: 100;
            border: none;
        }

        .fab:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4);
        }

        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
        }

        .toast {
            border-radius: var(--border-radius);
            border: none;
            box-shadow: var(--box-shadow);
            overflow: hidden;
        }

        .toast-header {
            border-bottom: none;
            font-weight: 600;
        }

        .toast-success .toast-header {
            background-color: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
        }

        .toast-error .toast-header {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <header class="dashboard-header animate__animated animate__fadeIn">
            <div class="d-flex justify-content-between align-items-start flex-wrap">
                <div>
                    <h1 class="dashboard-title">
                        <i class="fas fa-chart-line"></i>Academic Performance Dashboard
                    </h1>
                    <p class="dashboard-subtitle">Advanced analytics and student performance tracking system</p>
                </div>
                
                <div class="action-controls">
                    <a href="change_password.php" class="nav-link" data-bs-toggle="tooltip" title="Change your account password">
                        <i class="fas fa-key"></i> <span class="d-none d-md-inline">Security</span>
                    </a>
                    <a href="logout.php" class="nav-link" data-bs-toggle="tooltip" title="Logout from the system">
                        <i class="fas fa-sign-out-alt"></i> <span class="d-none d-md-inline">Logout</span>
                    </a>
                </div>
            </div>
            
            <div class="nav-controls">
                <div class="filter-controls">
                    <select id="classSelect" class="form-select shadow-sm" data-bs-toggle="tooltip" title="Filter by class">
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class['class']); ?>">
                                <?php echo htmlspecialchars($class['class']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    
                    <select id="subjectSelect" class="form-select shadow-sm" data-bs-toggle="tooltip" title="Filter by subject">
                        <option value="">Select Subject</option>
                        <?php foreach ($subjects as $subject): ?>
                            <option value="<?php echo htmlspecialchars($subject['subject']); ?>">
                                <?php echo htmlspecialchars($subject['subject']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="d-flex align-items-center gap-3">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Search students..." data-bs-toggle="tooltip" title="Search by student name">
                    </div>
                    
                    <button id="exportCsv" class="btn btn-export btn-icon shadow-sm" data-bs-toggle="tooltip" title="Export data to CSV">
                        <i class="fas fa-file-export"></i> <span class="d-none d-md-inline">Export</span>
                    </button>
                </div>
            </div>
        </header>
        
        <main class="animate__animated animate__fadeIn">
            <div class="loading-spinner" id="loadingSpinner">
                <div class="spinner"></div>
                <p class="mt-3 text-muted">Loading student performance data...</p>
            </div>
            
            <div id="statsContainer" class="row mb-4" style="display: none;">
                <div class="col-md-3">
                    <div class="stats-card">
                        <h5>Average Score</h5>
                        <div class="value" id="avgScore">-</div>
                        <div class="change positive" id="avgChange">
                            <i class="fas fa-arrow-up"></i> <span>0%</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h5>Top Student</h5>
                        <div class="value" id="topStudent">-</div>
                        <div class="change" id="topScore">
                            <span>Score: -</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h5>Pass Rate</h5>
                        <div class="value" id="passRate">-</div>
                        <div class="change positive" id="passChange">
                            <i class="fas fa-arrow-up"></i> <span>0%</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <h5>Students</h5>
                        <div class="value" id="studentCount">-</div>
                        <div class="change" id="classAverage">
                            <span>Class Avg: -</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="table-container shadow-sm" id="tableContainer">
                <div class="table-responsive">
                    <table class="table" id="scoresTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Adm No</th>
                                <th>Class Score</th>
                                <th>Exam Score</th>
                                <th>Total</th>
                                <th>Grade</th>
                                <th>Position</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Dynamic content will be inserted here -->
                            <tr>
                                <td colspan="9" class="empty-state">
                                    <i class="fas fa-table"></i>
                                    <h5 class="mt-2">No data to display</h5>
                                    <p class="text-muted">Select a class and subject to view student performance</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Floating Action Button -->
    <button class="fab" id="scrollToTop" title="Scroll to top">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Toast Notifications Container -->
    <div class="toast-container" id="toastContainer"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            // Class and subject selection handler
            $('#classSelect, #subjectSelect').change(function() {
                updateTable();
            });
            
            // Export CSV handler
            $('#exportCsv').click(function() {
                const selectedClass = $('#classSelect').val();
                const selectedSubject = $('#subjectSelect').val();
                
                if (selectedClass && selectedSubject) {
                    // Show loading state
                    const exportBtn = $(this);
                    const originalHtml = exportBtn.html();
                    exportBtn.html('<i class="fas fa-spinner fa-spin"></i> Exporting...');
                    
                    // Redirect to the export CSV script
                    window.location.href = `export_csv.php?class=${selectedClass}&subject=${selectedSubject}`;
                    
                    // Reset button after delay
                    setTimeout(() => {
                        exportBtn.html(originalHtml);
                        showToast('Export Successful', 'The data has been exported as a CSV file.', 'success');
                    }, 1000);
                } else {
                    Swal.fire({
                        title: 'Selection Required',
                        text: 'Please select both class and subject to export.',
                        icon: 'warning',
                        confirmButtonColor: 'var(--primary-color)'
                    });
                }
            });
            
            // Search functionality
            $('#searchInput').keyup(function() {
                const searchValue = $(this).val().toLowerCase();
                $('#scoresTable tbody tr').each(function() {
                    const studentName = $(this).find('td:eq(1)').text().toLowerCase();
                    const admNo = $(this).find('td:eq(2)').text().toLowerCase();
                    $(this).toggle(studentName.includes(searchValue) || admNo.includes(searchValue));
                });
            });
            
            // Scroll to top button
            $('#scrollToTop').click(function() {
                $('html, body').animate({ scrollTop: 0 }, 'smooth');
            });
            
            // Show/hide scroll to top button based on scroll position
            $(window).scroll(function() {
                if ($(this).scrollTop() > 300) {
                    $('#scrollToTop').fadeIn();
                } else {
                    $('#scrollToTop').fadeOut();
                }
            });
        });
        
        function updateTable() {
            const selectedClass = $('#classSelect').val();
            const selectedSubject = $('#subjectSelect').val();
            const tableBody = $('#scoresTable tbody');
            
            if (selectedClass && selectedSubject) {
                // Show loading spinner
                $('#loadingSpinner').show();
                $('#statsContainer').hide();
                tableBody.empty();
                
                // Fetch data from the server
                $.getJSON(`fetch_scores.php?class=${selectedClass}&subject=${selectedSubject}`, function(data) {
                    // Hide loading spinner
                    $('#loadingSpinner').hide();
                    
                    if (data.length === 0) {
                        tableBody.html('<tr><td colspan="9" class="text-center py-4 text-muted">No records found for the selected criteria</td></tr>');
                        return;
                    }
                    
                    // Show stats container
                    $('#statsContainer').fadeIn();
                    
                    // Calculate and display statistics
                    updateStatistics(data);
                    
                    // Populate table with data
                    $.each(data, function(index, score) {
                        const gradeClass = getGradeClass(score.remarks);
                        const row = `
                            <tr class="fade-in">
                                <td>${index + 1}</td>
                                <td>
                                    <strong class="editable" data-id="${score.marksid}" data-field="student">${score.student}</strong>
                                    <i class="fas fa-check-circle commit-btn" data-id="${score.marksid}"></i>
                                </td>
                                <td>
                                    <span class="editable" data-id="${score.marksid}" data-field="admno">${score.admno}</span>
                                    <i class="fas fa-check-circle commit-btn" data-id="${score.marksid}"></i>
                                </td>
                                <td>
                                    <span class="editable" data-id="${score.marksid}" data-field="midterm">${score.midterm}</span>
                                    <i class="fas fa-check-circle commit-btn" data-id="${score.marksid}"></i>
                                </td>
                                <td>
                                    <span class="editable" data-id="${score.marksid}" data-field="endterm">${score.endterm}</span>
                                    <i class="fas fa-check-circle commit-btn" data-id="${score.marksid}"></i>
                                </td>
                                <td>${score.average}</td>
                                <td>
                                    <span class="grade-badge ${gradeClass} editable" data-id="${score.marksid}" data-field="remarks">${score.remarks}</span>
                                    <i class="fas fa-check-circle commit-btn" data-id="${score.marksid}"></i>
                                </td>
                                <td>
                                    <span class="position-badge ${getPositionClass(score.position)}">
                                        ${formatPosition(score.position)}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn-action btn-delete" data-id="${score.marksid}" title="Delete record">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                    
                    // Add edit and delete event listeners
                    addEditListeners();
                    addDeleteListeners();
                }).fail(function(error) {
                    $('#loadingSpinner').hide();
                    showToast('Error', 'Failed to load data. Please try again.', 'error');
                    console.error('Error fetching data:', error);
                });
            } else {
                tableBody.empty();
                $('#statsContainer').hide();
            }
        }
        
        function updateStatistics(data) {
            if (data.length === 0) return;
            
            // Calculate average score
            const total = data.reduce((sum, student) => sum + parseFloat(student.average), 0);
            const avgScore = (total / data.length).toFixed(2);
            $('#avgScore').text(avgScore);
            
            // Find top student
            const topStudent = data.reduce((prev, current) => 
                (parseFloat(prev.average) > parseFloat(current.average) ? prev : current
            );
            $('#topStudent').text(topStudent.student.split(' ')[0]); // First name only
            $('#topScore').html(`<span>Score: ${topStudent.average}</span>`);
            
            // Calculate pass rate (assuming 50 is passing)
            const passCount = data.filter(student => parseFloat(student.average) >= 50).length;
            const passRate = ((passCount / data.length) * 100).toFixed(1);
            $('#passRate').text(passRate + '%');
            
            // Student count
            $('#studentCount').text(data.length);
            $('#classAverage').html(`<span>Class Avg: ${avgScore}</span>`);
            
            // Random changes for demo (would be real data in production)
            $('#avgChange span').text((Math.random() * 5).toFixed(1) + '%');
            $('#passChange span').text((Math.random() * 10).toFixed(1) + '%');
        }
        
        function getGradeClass(grade) {
            if (!grade) return 'grade-F';
            if (grade.startsWith('A')) return 'grade-A';
            if (grade.startsWith('B')) return 'grade-B';
            if (grade.startsWith('C')) return 'grade-C';
            if (grade.startsWith('D')) return 'grade-D';
            return 'grade-F';
        }
        
        function addEditListeners() {
            $('.editable').off('dblclick').on('dblclick', function() {
                const $this = $(this);
                const currentValue = $this.text().trim();
                const field = $this.data('field');
                const marksid = $this.data('id');
                
                // Create an input field for editing
                const $input = $(`<input type="text" class="editable-input" value="${currentValue}">`);
                $this.html($input);
                
                // Show the commit button
                const $commitBtn = $(`.commit-btn[data-id="${marksid}"]`);
                $commitBtn.show();
                
                // Focus on the input field
                $input.focus();
                
                // Save changes on enter key or commit button click
                $input.keypress(function(e) {
                    if (e.which === 13) { // Enter key
                        saveChanges(marksid, field, $input.val(), $this, $commitBtn);
                    }
                });
                
                $commitBtn.off('click').on('click', function() {
                    saveChanges(marksid, field, $input.val(), $this, $commitBtn);
                });
                
                // Cancel editing on blur
                $input.on('blur', function() {
                    $this.text(currentValue);
                    $commitBtn.hide();
                });
            });
        }
        
        function saveChanges(marksid, field, value, $element, $commitBtn) {
            $.ajax({
                url: 'update_score.php',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({ marksid, field, value }),
                success: function(data) {
                    if (data.success) {
                        // Update the cell content
                        if (field === 'student') {
                            $element.html(`<strong>${value}</strong>`);
                        } else if (field === 'remarks') {
                            $element.removeClass(function(index, className) {
                                return (className.match(/(^|\s)grade-\S+/g) || []).join(' ');
                            }).addClass(getGradeClass(value)).text(value);
                        } else {
                            $element.text(value);
                        }
                        
                        // Hide commit button
                        $commitBtn.hide();
                        
                        // If we updated scores, we might need to recalculate positions
                        if (field === 'midterm' || field === 'endterm') {
                            updateTable(); // Refresh the whole table to recalculate averages and positions
                        }
                        
                        showToast('Success', 'Changes saved successfully', 'success');
                    } else {
                        showToast('Error', data.error || 'Error updating the score. Please try again.', 'error');
                    }
                },
                error: function(error) {
                    console.error('Error saving changes:', error);
                    showToast('Error', 'Failed to save changes. Please try again.', 'error');
                }
            });
        }
        
        function addDeleteListeners() {
            $('.btn-delete').off('click').on('click', function() {
                const marksid = $(this).data('id');
                const $row = $(this).closest('tr');
                
                Swal.fire({
                    title: 'Confirm Deletion',
                    text: 'Are you sure you want to delete this record? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--danger-color)',
                    cancelButtonColor: 'var(--secondary-color)',
                    confirmButtonText: 'Delete Record',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading state on the button
                        const $btn = $(this);
                        $btn.html('<i class="fas fa-spinner fa-spin"></i>');
                        
                        $.ajax({
                            url: 'delete_score.php',
                            method: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({ id: marksid }),
                            success: function(data) {
                                if (data.success) {
                                    // Remove the row with animation
                                    $row.addClass('animate__animated animate__fadeOut');
                                    setTimeout(() => {
                                        $row.remove();
                                        updateTable(); // Refresh to update statistics
                                    }, 500);
                                    
                                    showToast('Deleted', 'The record has been deleted.', 'success');
                                } else {
                                    $btn.html('<i class="fas fa-trash-alt"></i>');
                                    showToast('Error', data.error || 'Error deleting the record. Please try again.', 'error');
                                }
                            },
                            error: function(error) {
                                $btn.html('<i class="fas fa-trash-alt"></i>');
                                console.error('Error deleting record:', error);
                                showToast('Error', 'Failed to delete record. Please try again.', 'error');
                            }
                        });
                    }
                });
            });
        }
        
        function formatPosition(position) {
            if (!position) return '-';
            if (position === 1) return position + 'st';
            if (position === 2) return position + 'nd';
            if (position === 3) return position + 'rd';
            return position + 'th';
        }
        
        function getPositionClass(position) {
            if (position === 1) return 'position-1';
            if (position === 2) return 'position-2';
            if (position === 3) return 'position-3';
            return 'position-other';
        }
        
        function showToast(title, message, type) {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast toast-${type}" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                        <strong class="me-auto">${title}</strong>
                        <small>Just now</small>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        ${message}
                    </div>
                </div>
            `;
            
            $('#toastContainer').append(toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
            
            // Remove toast after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function () {
                $(this).remove();
            });
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                toast.hide();
            }, 5000);
        }
    </script>
</body>
</html>
