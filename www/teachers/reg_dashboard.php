<?php
session_start();
require 'db_connect.php'; // Ensure this file correctly connects to SQLite

// Check if the teacher is logged in
if (!isset($_SESSION['teacher_id'])) {
    header('Location: login.php'); // Redirect to login if not authenticated
    exit();
}

$teacher_name = $_SESSION['teacher_name'];
$assigned_class = $_SESSION['assigned_class'];

// Fetch total students in assigned class
$query = $db->prepare("SELECT COUNT(*) AS total FROM students WHERE class = ?");
$query->execute([$assigned_class]);
$total_students = $query->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

// Fetch attendance stats
$query = $db->prepare("SELECT COUNT(*) AS present FROM attendance WHERE class = ? AND date = DATE('now') AND status = 'Present'");
$query->execute([$assigned_class]);
$present_students = $query->fetch(PDO::FETCH_ASSOC)['present'] ?? 0;

$attendance_rate = $total_students > 0 ? round(($present_students / $total_students) * 100, 2) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPro | Class Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #4A90E2; /* Deep professional blue */
            --secondary: #50E3C2; /* Teal */
            --danger: #E94E77; /* Deep pinkish red */
            --background: #F8FAFC; /* Light background */
            --border: #E2E8F0; /* Light border */
        }
        body {
            font-family: 'Inter', system-ui;
            background-color: var(--background);
        }
        .attendance-card {
            background: white; /* Restore original background */
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        canvas {
            max-width: 100%; /* Responsive */
            height: 200px; /* Consistent height for both charts */
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 2.5rem;
            color: var(--primary);
        }
        .header h5 {
            color: var(--secondary);
        }
        .nav-container {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
        }
        .nav-link {
            font-size: 1.2rem;
        }
        /* Blue Card Styles */
        .blue-card {
            background: linear-gradient(135deg, #003366, #006699); /* Deeper gradient from dark blue to lighter blue */
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        .blue-card h2 {
            font-size: 2rem;
            margin: 0;
        }
        .blue-card p {
            margin: 10px 0;
        }
        .btn-action {
            background-color: var(--secondary);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn-action:hover {
            background-color: #3B82F6; /* Lighter blue on hover */
        }
        /* Attendance Rate Card Styles */
        .attendance-rate-card {
            background: linear-gradient(135deg, #4A90E2, #50E3C2); /* Gradient from deep blue to teal */
            color: white; /* Text color for better contrast */
            border-radius: 12px;
            padding: 20px; /* Padding for the card */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">EduTrack <i class="bi bi-check2-circle" style="color: green;"></i></a> <!-- Checkmark icon beside EduTrack -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto"> <!-- Align to the right -->
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Classes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Analytics</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid p-4">
        <div class="header">
            <h5>👨‍🏫 Welcome, <?php echo htmlspecialchars($teacher_name); ?>!</h5>
            <h6 class="text-muted">Assigned Class: <?php echo htmlspecialchars($assigned_class); ?></h6>
        </div>

        <div class="blue-card">
            <h2>Class Overview - <?php echo htmlspecialchars($assigned_class); ?></h2>
            <p>Total Students: <?php echo $total_students; ?></p>
            <button class="btn-action" onclick="location.href='mark_attendance.php'">📋 Mark Attendance</button>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="attendance-rate-card p-3">
                    <h4 class="mb-0">
                        Attendance Rate 
                        <i class="bi bi-check2-circle" style="color: white;"></i> <!-- Checkmark icon -->
                    </h4>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="text-muted small">Attendance Rate</div>
                            <div class="h4 mb-0"> <?php echo $attendance_rate; ?>% </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="attendance-card p-3">
                    <h4 class="mb-3">
                        Attendance Overview 
                        <i class="bi bi-check2-circle" style="color: black;"></i> <!-- Checkmark icon -->
                    </h4>
                    <canvas id="attendanceBarChart"></canvas>
                </div>
            </div>
            <div class="col-md-6">
                <div class="attendance-card p-3">
                    <h4 class="mb-3">
                        Attendance Distribution 
                        <i class="bi bi-check2-circle" style="color: black;"></i> <!-- Checkmark icon -->
                    </h4>
                    <canvas id="attendanceDonutChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Bar Chart for Total Students and Present Students
        const ctxBar = document.getElementById('attendanceBarChart').getContext('2d');
        const attendanceBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: ['Total Students', 'Present Students'],
                datasets: [{
                    label: 'Number of Students',
                    data: [<?php echo $total_students; ?>, <?php echo $present_students; ?>],
                    backgroundColor: [
                        'rgba(74, 144, 226, 0.9)', // Deep professional blue
                        'rgba(80, 227, 194, 0.9)'  // Deep teal
                    ],
                    borderColor: [
                        'rgba(74, 144, 226, 1)',
                        'rgba(80, 227, 194, 1)'
                    ],
                    borderWidth: 3 // Thicker border
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Students'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Categories'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false // Hide legend for clarity
                    },
                    title: {
                        display: true,
                        text: 'Attendance Overview'
                    }
                }
            }
        });

        // Donut Chart for Attendance Distribution
        const ctxDonut = document.getElementById('attendanceDonutChart').getContext('2d');
        const attendanceDonutChart = new Chart(ctxDonut, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    label: 'Attendance Distribution',
                    data: [<?php echo $present_students; ?>, <?php echo $total_students - $present_students; ?>],
                    backgroundColor: [
                        'rgba(74, 144, 226, 0.7)', // Soft professional blue
                        'rgba(233, 78, 119, 0.7)'  // Soft pinkish red
                    ],
                    borderColor: [
                        'rgba(255, 255, 255, 1)', // White border for distinction
                        'rgba(255, 255, 255, 1)'  // White border for distinction
                    ],
                    borderWidth: 2 // Thinner border
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 10,
                            font: {
                                family: 'Arial', // Professional font
                                size: 14
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const total = <?php echo $total_students; ?>;
                                const value = tooltipItem.raw;
                                const percentage = ((value / total) * 100).toFixed(2) + '%';
                                return tooltipItem.label + ': ' + value + ' (' + percentage + ')';
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Attendance Distribution'
                    }
                }
            }
        });
    </script>
</body>
</html>