<?php
require_once "../include/functions.php";

$session_id = $_SESSION["id"];

if ($session_id == "") {
    header("Location: ../index.php?error= Invalid username or password");
    exit();
}

$conn = db_conn();

// Fetch average marks for each class
$query = "SELECT class, AVG(Average) AS average_mark FROM marks GROUP BY class ORDER BY class;";
$result = $conn->query($query);

$classes = [];
$average_marks = [];

while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $classes[] = $row['class'];
    $average_marks[] = $row['average_mark'];
}

// Fetch total fees paid from the school_fees table
$query_balance = "SELECT SUM(fees_paid) AS total_balance FROM Student_fees;";
$result_balance = $conn->query($query_balance);
$balance_row = $result_balance->fetchArray(SQLITE3_ASSOC);
$school_account_balance = (float)$balance_row['total_balance'];

// Count the number of students admitted each month
$query_admission = "SELECT strftime('%Y-%m', date_of_admission) AS admission_month, COUNT(*) AS student_count FROM students GROUP BY admission_month ORDER BY admission_month;";
$result_admission = $conn->query($query_admission);

$months = [];
$student_counts = [];

while ($row = $result_admission->fetchArray(SQLITE3_ASSOC)) {
    $months[] = $row['admission_month'];
    $student_counts[] = (int)$row['student_count'];
}

// Conversion rate (example)
$conversion_rate = 0.12; // 1 GHS = 0.12 USD
$converted_amount = $school_account_balance * $conversion_rate;

// Initialize total_students, total_teachers, total_females, total_males, and total_employees variables
$total_students = 0;
$total_teachers = 0;
$total_females = 0;
$total_males = 0;
$total_employees = 0;

try {
    // Get total students count for the current month
    $students_result = $conn->query("SELECT COUNT(*) as total_students FROM students");
    $students_row = $students_result->fetchArray(SQLITE3_ASSOC);
    $total_students = (int)$students_row['total_students'];

    // Get total students count for the previous month
    $previous_month_result = $conn->query("SELECT COUNT(*) as total_students FROM students WHERE strftime('%Y-%m', date_of_admission) = strftime('%Y-%m', 'now', '-1 month')");
    $previous_month_row = $previous_month_result->fetchArray(SQLITE3_ASSOC);
    $previous_month_students = (int)$previous_month_row['total_students'];

    // Calculate percentage change
    $percentage_change = 0;
    if ($previous_month_students > 0) {
        $percentage_change = (($total_students - $previous_month_students) / $previous_month_students) * 100;
    } elseif ($total_students > 0) {
        $percentage_change = 100;
    }

    // Determine if the change is positive or negative
    $change_class = $percentage_change >= 0 ? 'change-positive' : 'change-negative';
    $arrow_icon = $percentage_change >= 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down';
    $percentage_change_display = number_format(abs($percentage_change), 1);

    // Get total teachers count
    $teachers_result = $conn->query("SELECT COUNT(*) as total_teachers FROM employees WHERE position = 'Teacher'");
    $teachers_row = $teachers_result->fetchArray(SQLITE3_ASSOC);
    $total_teachers = (int)$teachers_row['total_teachers'];

    // Get total females count
    $females_result = $conn->query("SELECT COUNT(*) as total_females FROM students WHERE gender = 'Female'");
    $females_row = $females_result->fetchArray(SQLITE3_ASSOC);
    $total_females = (int)$females_row['total_females'];

    // Get total males count
    $males_result = $conn->query("SELECT COUNT(*) as total_males FROM students WHERE gender = 'Male'");
    $males_row = $males_result->fetchArray(SQLITE3_ASSOC);
    $total_males = (int)$males_row['total_males'];
    
    // Get total employees count
    $employees_result = $conn->query("SELECT COUNT(*) as total_employees FROM employees");
    $employees_row = $employees_result->fetchArray(SQLITE3_ASSOC);
    $total_employees = (int)$employees_row['total_employees'];

    // Fetch the most recent attendance date
    $latest_date_query = "SELECT MAX(date) AS latest_date FROM mark_attendance;";
    $latest_date_result = $conn->query($latest_date_query);
    $latest_date_row = $latest_date_result->fetchArray(SQLITE3_ASSOC);
    $latest_date = $latest_date_row['latest_date'];

    if ($latest_date) {
        $latest_date_formatted = date('Y-m-d', strtotime($latest_date));

        $count_present_query = "SELECT COUNT(*) AS present_count FROM mark_attendance WHERE date = :latest_date AND status = 'Present';";
        $stmt = $conn->prepare($count_present_query);
        $stmt->bindValue(':latest_date', $latest_date_formatted, SQLITE3_TEXT);
        $count_present_result = $stmt->execute();
        $count_present_row = $count_present_result->fetchArray(SQLITE3_ASSOC);
        $present_count = (int)$count_present_row['present_count'];
    }
} catch (Exception $e) {
    error_log("Error fetching counts: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPro Suite 2.0 - Admin Dashboard</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #2c3e50;
            --gray-color: #95a5a6;
            --card-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: var(--dark-color);
            margin: 0;
            padding: 0;
        }
        
        #sidebar {
            min-width: 250px;
            max-width: 250px;
            background: var(--secondary-color);
            color: white;
            transition: var(--transition);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header {
            padding: 20px;
            background: rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        
        .sidebar-header img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
            border: 3px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-header h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 0;
            font-size: 1.1rem;
        }
        
        #sidebar ul.components {
            padding: 0;
        }
        
        #sidebar ul li a {
            padding: 12px 20px;
            font-size: 0.9rem;
            display: block;
            color: rgba(255, 255, 255, 0.8);
            border-left: 3px solid transparent;
            transition: var(--transition);
            text-decoration: none;
        }
        
        #sidebar ul li a:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 3px solid var(--primary-color);
        }
        
        #sidebar ul li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        #sidebar ul li.active > a {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 3px solid var(--primary-color);
        }
        
        #sidebar .footer {
            padding: 15px;
            text-align: center;
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.5);
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        
        #content {
            width: calc(100% - 250px);
            padding: 20px;
            min-height: 100vh;
            transition: var(--transition);
            position: absolute;
            top: 0;
            right: 0;
            background-color: #f8f9fa;
        }
        
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }
        
        .dashboard-title {
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 1.5rem;
            color: var(--dark-color);
        }
        
        .dashboard-title img {
            height: 50px;
            margin-right: 15px;
        }
        
        .ai-badge {
            background-color: var(--primary-color);
            color: white;
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
            margin-left: 10px;
            display: flex;
            align-items: center;
        }
        
        .ai-badge i {
            margin-right: 5px;
        }
        
        .admin-profile {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .admin-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: var(--light-color);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .admin-role {
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .metric-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            border-left: 4px solid var(--primary-color);
        }
        
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.25rem;
            color: white;
            background-color: var(--primary-color);
        }
        
        .metric-info {
            flex: 1;
        }
        
        .metric-title {
            font-size: 0.85rem;
            color: var(--gray-color);
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .metric-value {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
            color: var(--dark-color);
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .metric-change {
            font-size: 0.75rem;
            display: flex;
            align-items: center;
        }
        
        .change-positive {
            color: var(--success-color);
        }
        
        .change-negative {
            color: var(--danger-color);
        }
        
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .chart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: var(--card-shadow);
            position: relative;
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .chart-title {
            font-family: 'Poppins', sans-serif;
            font-weight: 500;
            font-size: 1rem;
            color: var(--dark-color);
        }
        
        .chart-options {
            font-size: 0.8rem;
            color: var(--gray-color);
            cursor: pointer;
        }
        
        .chart-wrapper {
            position: relative;
            height: 250px;
            width: 100%;
        }
        
        .double-width {
            grid-column: span 2;
        }
        
        .ai-suggestion {
            font-size: 0.8rem;
            color: var(--gray-color);
            margin-top: 10px;
            padding: 8px 12px;
            background: rgba(52, 152, 219, 0.1);
            border-radius: 5px;
            display: flex;
            align-items: center;
        }
        
        .ai-suggestion i {
            margin-right: 8px;
            color: var(--primary-color);
        }
        
        @media (max-width: 992px) {
            .double-width {
                grid-column: span 1;
            }
        }
        
        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            
            #sidebar.active {
                margin-left: 0;
            }
            
            #content {
                width: 100%;
            }
            
            #content.active {
                width: calc(100% - 250px);
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <div id="sidebar">
            <div class="sidebar-header">
                <img src="../images/icon.png" alt="EduPro Logo">
                <h4>EduPro Suite 4</h4>
            </div>
            
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="index.php?dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li>
                    <a href="index.php?student_fees"><i class="fas fa-user-graduate"></i> Student Fees</a>
                </li>
                <li>
                    <a href="../reception/students.php"><i class="fas fa-users"></i> Student Management</a>
                </li>
                <li>
                    <a href="index.php?unnamed"><i class="fas fa-check-circle"></i> Attendance Check-ins</a>
                </li>
                <li>
                    <a onclick="window.location.href='qq/admin.php';"><i class="fas fa-clipboard-list"></i> Attendance Records</a>
                </li>
                <li>
                    <a href="index.php?unnamed"><i class="fas fa-check-double"></i> Fees Validator</a>
                </li>
                <li>
                    <a onclick="window.location.href='rec.php';" style="cursor: pointer;"><i class="fas fa-file-invoice-dollar"></i> Expenses</a>
                </li>
                <li>
                    <a href="index.php?sent-messages"><i class="fas fa-comments"></i> Parent Communication</a>
                </li>
                <li>
                    <a href="index.php?admin_pickup"><i class="fas fa-lock"></i> Secure Pickup</a>
                </li>
                <li>
                    <a href="index.php?visitors"><i class="fas fa-user-friends"></i> Visitors Tracking</a>
                </li>
                <li>
                    <a href="index.php?bus_tracking"><i class="fas fa-bus"></i> Bus Tracking</a>
                </li>
                <li>
                    <a href="index.php?emp"><i class="fas fa-chalkboard-teacher"></i> Employees</a>
                </li>
                <li>
                    <a href="index.php?class"><i class="fas fa-chalkboard"></i> Classes</a>
                </li>
                <li>
                    <a href="index.php?subject"><i class="fas fa-book"></i> Subjects</a>
                </li>
                <li>
                    <a href="index.php?exam"><i class="fas fa-pencil-alt"></i> Exams</a>
                </li>
                <li>
                    <a href="#Change_Password" data-toggle="modal" data-target="#Change_Password"><i class="fas fa-user-cog"></i> Profile</a>
                </li>
                <li>
                    <a href="../include/functions.php?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
                <li>
                    <a href="#"><i class="fas fa-question-circle"></i> Help</a>
                </li>
            </ul>
            
            <div class="footer">
                <p>&copy; <?php echo date("Y"); ?> Swipeware Technologies</p>
            </div>
        </div>

        <!-- Page Content -->
        <div id="content">
            <div class="dashboard-container">
                <div class="dashboard-header">
                    <h1 class="dashboard-title">
                        <img src="../images/ad.jpeg" alt="School Logo">
                        ADINKRA INTERNATIONAL SCHOOL
                        <span class="ai-badge">
                            <i class="fas fa-robot"></i> AI Enhanced
                        </span>
                    </h1>
                    
                    <div class="admin-profile">
                        <div class="admin-avatar">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>

                <!-- Metrics Cards Section -->
                <div class="metrics-grid">
                    <div class="metric-card">
                        <div class="metric-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-title">Total Students</div>
                            <div class="metric-value"><?php echo number_format($total_students); ?></div>
                            <div class="metric-change <?php echo $change_class; ?>">
                                <i class="<?php echo $arrow_icon; ?>"></i> <?php echo $percentage_change_display; ?>% from last month
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon" style="background-color: #2ecc71;">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-title">Total Teachers</div>
                            <div class="metric-value"><?php echo number_format($total_teachers); ?></div>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon" style="background-color: #9b59b6;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-title">Total Staff</div>
                            <div class="metric-value"><?php echo $total_employees; ?></div>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon" style="background-color: #e67e22;">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-title">Today's Attendance</div>
                            <div class="metric-value">
                                <?php echo isset($present_count) ? number_format($present_count) : "N/A"; ?>
                            </div>
                            <div class="metric-date">
                                <?php echo date('l, F j, Y'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon" style="background-color: #34495e;">
                            <i class="fas fa-venus-mars"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-title">Gender Distribution</div>
                            <div class="metric-value">
                                <?php echo $total_males; ?>♂ / <?php echo $total_females; ?>♀
                            </div>
                        </div>
                    </div>
                    
                    <div class="metric-card">
                        <div class="metric-icon" style="background-color: #f1c40f; color: #2c3e50;">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <div class="metric-info">
                            <div class="metric-title">School Balance</div>
                            <div class="metric-value">
                                GHS <?php echo number_format($school_account_balance, 2); ?>
                            </div>
                            <div class="metric-change" style="cursor: pointer;" onclick="showConvertedAmount()">
                                <i class="fas fa-exchange-alt"></i> Convert to USD
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="charts-grid">
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Student Admission Trend</div>
                            <div class="chart-options">Monthly <i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                        <div class="ai-suggestion">
                            <i class="fas fa-robot"></i> AI forecasts 210 enrollments for next month
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Attendance Rate</div>
                            <div class="chart-options">Weekly <i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                        <div class="ai-suggestion">
                            <i class="fas fa-robot"></i> AI detected Wednesday as low attendance day
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Gender Distribution</div>
                            <div class="chart-options">Current Term <i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="genderChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Performance Trend</div>
                            <div class="chart-options">Current Term <i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-container double-width">
                        <div class="chart-header">
                            <div class="chart-title">Class Performance Analysis</div>
                            <div class="chart-options">By Examination <i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="classPerformanceChart"></canvas>
                        </div>
                        <div class="ai-suggestion">
                            <i class="fas fa-robot"></i> AI identifies Science as needing curriculum adjustments
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Admission Growth</div>
                            <div class="chart-options">Current Term <i class="fas fa-chevron-down"></i></div>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="admissionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    
    <script>
        // Sidebar toggle functionality
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });
            
            // Adjust content margin when sidebar is toggled
            if ($(window).width() < 768) {
                $('#sidebar').addClass('active');
                $('#content').addClass('active');
            }
        });
        
        // Enrollment Trend Chart
        const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
        const enrollmentChart = new Chart(enrollmentCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Actual Enrollments',
                    data: <?php echo json_encode($student_counts); ?>,
                    borderColor: '#3498db',
                    backgroundColor: 'rgba(52, 152, 219, 0.1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                },
                {
                    data: [null, null, null, null, null, null, 231, 210],
                    borderColor: '#e74c3c',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    pointBackgroundColor: '#e74c3c',
                    pointBorderColor: '#fff',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Attendance Rate Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
                datasets: [{
                    label: 'Attendance Rate',
                    data: [96.2, 95.7, 91.8, 95.1, 96.4],
                    backgroundColor: '#3498db',
                    borderColor: '#2980b9',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 90,
                        max: 100,
                        grid: {
                            display: true,
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        
        // Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const genderChart = new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [<?php echo $total_males; ?>, <?php echo $total_females; ?>],
                    backgroundColor: ['#3498db', '#e74c3c'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });

        // Class Performance Chart
        const classPerformanceCtx = document.getElementById('classPerformanceChart').getContext('2d');
        const classPerformanceChart = new Chart(classPerformanceCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($classes); ?>,
                datasets: [{
                    label: 'Average Marks',
                    data: <?php echo json_encode($average_marks); ?>,
                    backgroundColor: [
                        'rgba(52, 152, 219, 0.8)',
                        'rgba(46, 204, 113, 0.8)',
                        'rgba(155, 89, 182, 0.8)',
                        'rgba(230, 126, 34, 0.8)',
                        'rgba(241, 196, 15, 0.8)',
                        'rgba(231, 76, 60, 0.8)',
                        'rgba(149, 165, 166, 0.8)'
                    ],
                    borderColor: [
                        'rgba(52, 152, 219, 1)',
                        'rgba(46, 204, 113, 1)',
                        'rgba(155, 89, 182, 1)',
                        'rgba(230, 126, 34, 1)',
                        'rgba(241, 196, 15, 1)',
                        'rgba(231, 76, 60, 1)',
                        'rgba(149, 165, 166, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Average Marks'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Classes'
                        }
                    }
                }
            }
        });

        // Admission Growth Chart
        const admissionCtx = document.getElementById('admissionChart').getContext('2d');
        const admissionChart = new Chart(admissionCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Students Admitted',
                    data: <?php echo json_encode($student_counts); ?>,
                    backgroundColor: '#2ecc71'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Performance Trend Chart
        const performanceCtx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(performanceCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5'],
                datasets: [{
                    label: 'Performance Trend',
                    data: [75, 82, 78, 85, 88],
                    backgroundColor: 'rgba(46, 204, 113, 0.2)',
                    borderColor: '#2ecc71',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        function showConvertedAmount() {
            var convertedAmount = <?php echo json_encode(number_format($converted_amount, 2)); ?>;
            var conversionRate = <?php echo json_encode($conversion_rate); ?>;
            swal({
                title: "Converted Amount",
                text: "USD " + convertedAmount + "\nConversion Rate: 1 GHS = " + conversionRate + " USD",
                icon: "info",
                button: "Close",
            });
        }
    </script>
</body>
</html>