<?php
session_start();
require 'db_connect.php';  

// Redirect if not logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php');
    exit();
}

$teacher_id = $_SESSION['teacher_id'];

// Fetch teacher's assigned class
$query = $db->prepare("SELECT assigned_class FROM teacher WHERE id = ?");
$query->execute([$teacher_id]);
$teacher = $query->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    echo "Error: Teacher not found.";
    exit();
}

$assigned_class = $teacher['assigned_class'];

// Fetch unique weeks from the database
$weeksQuery = $db->query("SELECT DISTINCT week FROM mark_attendance ORDER BY week");
$weeks = $weeksQuery->fetchAll(PDO::FETCH_ASSOC);

// Initialize selected_week and selected_day
$selected_week = $_GET['week'] ?? null;
$selected_day = $_GET['day'] ?? null; // Initialize selected_day

// Pagination setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch attendance records for the selected week and optional day
$query = $db->prepare("SELECT a.date, s.name, a.status, s.gender 
                        FROM mark_attendance a 
                        JOIN students s ON a.student_id = s.id 
                        WHERE a.class = ? AND a.week = ? 
                        AND (a.day = ? OR ? IS NULL) 
                        ORDER BY a.date DESC, s.name ASC 
                        LIMIT ? OFFSET ?");
$query->execute([$assigned_class, $selected_week, $selected_day, $selected_day, $limit, $offset]);
$records = $query->fetchAll(PDO::FETCH_ASSOC);

// Get total records count
$countQuery = $db->prepare("SELECT COUNT(*) FROM mark_attendance a 
                            JOIN students s ON a.student_id = s.id 
                            WHERE a.class = ? AND a.week = ? 
                            AND (a.day = ? OR ? IS NULL)");
$countQuery->execute([$assigned_class, $selected_week, $selected_day, $selected_day]);
$total_records = $countQuery->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Count males and females who were present
$presentCountQuery = $db->prepare("SELECT s.gender, COUNT(*) as count 
                                    FROM mark_attendance a 
                                    JOIN students s ON a.student_id = s.id 
                                    WHERE a.class = ? AND a.week = ? 
                                    AND (a.day = ? OR ? IS NULL) 
                                    AND a.status = 'Present' 
                                    GROUP BY s.gender");
$presentCountQuery->execute([$assigned_class, $selected_week, $selected_day, $selected_day]);
$genderCounts = $presentCountQuery->fetchAll(PDO::FETCH_ASSOC);

// Initialize counts
$totalMales = 0;
$totalFemales = 0;

// Process gender counts
foreach ($genderCounts as $genderCount) {
    if ($genderCount['gender'] === 'Male') {
        $totalMales = $genderCount['count'];
    } elseif ($genderCount['gender'] === 'Female') {
        $totalFemales = $genderCount['count'];
    }
}

// Calculate total present
$totalPresent = $totalMales + $totalFemales;

// Count total absent students
$absentCountQuery = $db->prepare("SELECT COUNT(*) as count 
                                    FROM mark_attendance a 
                                    WHERE a.class = ? AND a.week = ? 
                                    AND (a.day = ? OR ? IS NULL) 
                                    AND a.status = 'Absent'");
$absentCountQuery->execute([$assigned_class, $selected_week, $selected_day, $selected_day]);
$totalAbsent = $absentCountQuery->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack | Attendance History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs    .cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4F46E5;
            --secondary-color: #10B981;
            --dark-color: #1F2937;
            --light-color: #F9FAFB;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: #F8FAFC;
            color: #1F2937;
            line-height: 1.6;
        }
        
        .history-header {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border: none;
            position: relative;
            overflow: hidden;
        }
        
        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border: 1px solid #E5E7EB;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            border: 1px solid #E5E7EB;
        }
        
        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .badge-present {
            background-color: #D1FAE5;
            color: var(--secondary-color);
        }
        
        .badge-absent {
            background-color: #FEE2E2;
            color: var(--danger-color);
        }
        
        .badge-late {
            background-color: #FEF3C7;
            color: var(--warning-color);
        }
        
        .class-week-header {
            background-color: #E5E7EB; /* Light gray background */
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .class-week-header h5 {
            margin: 0;
            font-weight: 500;
        }
    </style>
</head>
<body>
  <div class="container py-4">
    <div class="history-header">
        <h3><i class="fas fa-calendar-check"></i> Attendance History</h3>
        <p class="mb-0">Review and analyze student attendance records</p>
    </div>

    <div class="class-week-header mb-4 p-3 bg-white rounded shadow-sm border-0">
        <div class="d-flex flex-wrap align-items-center gap-4">
            <div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Class</span>
                <span class="fs-6 fw-bold text-dark"><?php echo htmlspecialchars($assigned_class); ?></span>
            </div>
            <div class="vr"></div>
            <div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Week</span>
                <span class="fs-6 fw-bold text-dark"><?php echo htmlspecialchars($selected_week ? $selected_week : '—'); ?></span>
            </div>
            <div class="vr"></div>
            <div>
                <span class="text-muted small text-uppercase fw-semibold d-block mb-1">Day</span>
                               <span class="fs-6 fw-bold text-dark"><?php echo htmlspecialchars($selected_day ? $selected_day : '—'); ?></span>
            </div>
        </div>
    </div>

    <div class="filter-card">
        <form method="GET" class="mb-0">
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label for="week" class="form-label small text-muted">Select Week</label>
                    <select name="week" id="week" class="form-control" required>
                        <option value="">-- Select Week --</option>
                        <?php foreach ($weeks as $week): ?>
                            <option value="<?php echo htmlspecialchars($week['week']); ?>" <?php echo ($week['week'] == $selected_week) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($week['week']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-5">
                    <label for="day" class="form-label small text-muted">Select Day (Optional)</label>
                    <select name="day" id="day" class="form-control">
                        <option value="">-- Select Day --</option>
                        <?php 
                        // Days of the week
                        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                        foreach ($daysOfWeek as $day): ?>
                            <option value="<?php echo htmlspecialchars($day); ?>" <?php echo ($day == $selected_day) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($day); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-2"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="table-container">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th><i class="far fa-calendar me-2"></i>Date</th>
                        <th><i class="fas fa-user-graduate me-2"></i>Student Name</th>
                        <th><i class="fas fa-info-circle me-2"></i>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($records) > 0): ?>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                <td>
                                    <?php if ($record['status'] == 'Present'): ?>
                                        <span class="status-badge badge-present">
                                            <i class="fas fa-check-circle me-1"></i> Present
                                        </span>
                                    <?php elseif ($record['status'] == 'Absent'): ?>
                                        <span class="status-badge badge-absent">
                                            <i class="fas fa-times-circle me-1"></i> Absent
                                        </span>
                                    <?php else: ?>
                                        <span class="status-badge badge-late">
                                            <i class="fas fa-clock me-1"></i> <?php echo htmlspecialchars($record['status']); ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center">No records found for the selected week.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Display total present and absent counts -->
    <div class="alert alert-info">
        <strong>Total Present:</strong> 
        <?php echo $totalPresent; ?> (<?php echo $totalMales; ?> Male(s), <?php echo $totalFemales; ?> Female(s))<br>
        <strong>Total Absent:</strong> <?php echo $totalAbsent; ?> 
    </div>

    <!-- Pagination -->
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>&week=<?php echo htmlspecialchars($selected_week); ?>&day=<?php echo htmlspecialchars($selected_day); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&week=<?php echo htmlspecialchars($selected_week); ?>&day=<?php echo htmlspecialchars($selected_day); ?>"><?php echo $i; ?></a>
                </li>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>&week=<?php echo htmlspecialchars($selected_week); ?>&day=<?php echo htmlspecialchars($selected_day); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Add subtle animation to table rows
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = `all 0.3s ease ${index * 0.05}s`;
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 100);
        });
    });
</script>
</body>
</html>