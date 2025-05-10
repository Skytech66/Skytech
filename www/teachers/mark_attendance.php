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

// Fetch students from the assigned class
$query = $db->prepare("SELECT id, name FROM students WHERE class = ?");
$query->execute([$assigned_class]);
$students = $query->fetchAll(PDO::FETCH_ASSOC);

// Handle attendance submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mark_attendance'])) {
    $attendance_week = $_POST['attendance_week'] ?? ''; // Get selected week
    $attendance_date = $_POST['attendance_date'] ?? date('Y-m-d'); 
    $attendance_day = $_POST['attendance_day'] ?? ''; // Get selected day
    $attendance_data = $_POST['attendance'] ?? [];

    if (empty($attendance_data)) {
        $_SESSION['error'] = "No attendance data received.";
        header("Location: mark_attendance.php");
        exit();
    }

    $query = $db->prepare("INSERT INTO mark_attendance (student_id, class, date, week, day, status, teacher_id) VALUES (?, ?, ?, ?, ?, ?, ?)");

    foreach ($attendance_data as $student_id => $status) {
        $query->execute([$student_id, $assigned_class, $attendance_date, $attendance_week, $attendance_day, $status, $teacher_id]);
    }
    
    $_SESSION['success'] = "Attendance recorded successfully!";
    
    // Check for absentee patterns
    checkAbsenteePatterns($assigned_class, $teacher_id);
    
    header("Location: mark_attendance.php");
    exit();
}

// Handle adding a new student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_student'])) {
    $student_name = trim($_POST['student_name']);

    if (!empty($student_name)) {
        $query = $db->prepare("INSERT INTO students (name, class) VALUES (?, ?)");
        if ($query->execute([$student_name, $assigned_class])) {
            $_SESSION['success'] = "Student added successfully!";
        } else {
            $_SESSION['error'] = "Error adding student.";
        }
    } else {
        $_SESSION['error'] = "Student name cannot be empty.";
    }
    header("Location: mark_attendance.php");
    exit();
}

// Function to check absentee patterns
function checkAbsenteePatterns($class, $teacher_id) {
    global $db;

    // Get the current date and the date 3 days ago
    $current_date = date('Y-m-d');
    $three_days_ago = date('Y-m-d', strtotime('-3 days'));

    // Query to find students who have been absent for the last 3 days
    $query = $db->prepare("
        SELECT s.name, COUNT(a.status) as absent_count 
        FROM students s 
        LEFT JOIN mark_attendance a ON s.id = a.student_id 
        WHERE s.class = ? AND a.status = 'Absent' AND a.date >= ? 
        GROUP BY s.id 
        HAVING absent_count >= 3
    ");
    $query->execute([$class, $three_days_ago]);
    $absent_students = $query->fetchAll(PDO::FETCH_ASSOC);

    // If there are students with absentee patterns, set a session variable to show the AI message
    if (!empty($absent_students)) {
        $_SESSION['ai_message'] = "👋 Hi, I've noticed that the following students have been absent consistently for three days: " . implode(", ", array_column($absent_students, 'name')) . ".";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack | AI Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #4F46E5;
            --ai-accent: #10B981;
			  --surface: #F8FAFC;
            --border: #E2E8F0;
            --ai-bg: #1E293B;
            --ai-text: #E0E7FF;
        }
        body {
            font-family: 'Inter', system-ui;
            background-color: var(--surface);
        }
        .ai-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--ai-accent) 100%);
            color: white;
            border-radius: 12px;
            padding: 15px;
            text-align: center;
        }
        .student-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
            cursor: pointer; /* Change cursor to pointer */
        }
        .success-message {
            background: #D1FAE5;
            color: #065F46;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
        }
        .error-message {
            background: #FECACA;
            color: #B91C1C;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 15px;
        }
        .ai-popup {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #4CAF50; /* Green background */
            color: white; /* Text color */
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 15px;
            z-index: 1000;
            display: none; /* Initially hidden */
            opacity: 0; /* Start with opacity 0 for fade-in effect */
            transition: opacity 0.5s ease, transform 0.5s ease;
        }
        .ai-popup.show {
            display: block; /* Show the popup */
            opacity: 1; /* Fade in */
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="ai-header mb-4">
            <h3><i class="fas fa-robot"></i> AI-Powered Attendance</h3>
            <p class="mb-0">Smart tracking with pattern recognition</p>
        </div>

        <!-- Display Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <!-- Display Error Message -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <i class="fas fa-exclamation-triangle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Display AI Message -->
        <?php if (isset($_SESSION['ai_message'])): ?>
            <div class="ai-popup" id="aiPopup">
                <?php echo $_SESSION['ai_message']; unset($_SESSION['ai_message']); ?>
            </div>
        <?php endif; ?>

        <h4 class="text-primary"><i class="fas fa-school"></i> Class: <?php echo htmlspecialchars($assigned_class); ?></h4>

        <!-- Attendance Form -->
        <form method="POST">
            <div class="mb-3">
                <label for="weekSelect" class="form-label"><i class="fas fa-calendar-week"></i> Select Week</label>
                <select name="attendance_week" id="weekSelect" class="form-select" required>
                    <option value="">Select Week</option>
                    <option value="Week 1">Week 1</option>
                    <option value="Week 2">Week 2</option>
                    <option value="Week 3">Week 3</option>
                    <option value="Week 4">Week 4</option>
                    <option value="Week 5">Week 5</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="attendanceDate" class="form-label"><i class="fas fa-calendar-alt"></i> Select Date</label>
                <input type="date" name="attendance_date" id="attendanceDate" class=" form-control" required>
            </div>

            <div class="mb-3">
                <label for="daySelect" class="form-label"><i class="fas fa-calendar-day"></i> Select Day</label>
                <select name="attendance_day" id="daySelect" class="form-select" required>
                    <option value="">Select Day</option>
                    <option value="Monday">Monday</option>
                    <option value="Tuesday">Tuesday</option>
                    <option value="Wednesday">Wednesday</option>
                    <option value="Thursday">Thursday</option>
                    <option value="Friday">Friday</option>
                    <option value="Saturday">Saturday</option>
                    <option value="Sunday">Sunday</option>
                </select>
            </div>

            <div id="studentList">
                <?php if (count($students) > 0): ?>
                    <?php foreach ($students as $student): ?>
                        <div class="student-card">
                            <div class="fw-medium"><i class="fas fa-user"></i> <?php echo htmlspecialchars($student['name']); ?></div>
                            <select name="attendance[<?php echo $student['id']; ?>]" class="form-select form-select-sm">
                                <option value=""> </option>
                                <option value="Present">Present</option>
                                <option value="Late">Late</option>
                                <option value="Absent">Absent</option>
                            </select>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted">No students found in this class.</p>
                <?php endif; ?>
            </div>

            <input type="hidden" name="mark_attendance" value="1">
            <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-cloud-upload-alt"></i> Submit Attendance</button>
        </form>
        <a href="view_attendance.php" class="btn btn-info mt-3">
            <i class="fas fa-history"></i> View Attendance History
        </a>

        <!-- Add Student Button -->
        <button class="btn btn-success mt-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fas fa-user-plus"></i> Add Student
        </button>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user"></i> Add Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="text" name="student_name" class="form-control" placeholder="Enter Student Name" required>
                        <button type="submit" name="add_student" class="btn btn-primary mt-3 w-100">Add Student</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Add click event to student cards
            document.querySelectorAll('.student-card').forEach(card => {
                card.addEventListener('click', function() {
                    const selectElement = this.querySelector("select");
                    if (selectElement) {
                        // Cycle through attendance options
                        const options = ["Present", "Late", "Absent"];
                        const currentIndex = options.indexOf(selectElement.value);
                        const nextIndex = (currentIndex + 1) % options.length;
                        selectElement.value = options[nextIndex];

                        // Add visual feedback (highlight)
                        this.style.backgroundColor = nextIndex === 0 ? "#D1FAE5" : // Green for Present
                                                     nextIndex === 1 ? "#FEF3C7" : // Yellow for Late
                                                                       "#FECACA";  // Red for Absent
                    }
                });
            });

            // Auto-hide AI message after 5 seconds
            const aiPopup = document.getElementById("aiPopup");
            if (aiPopup) {
                setTimeout(() => {
                    aiPopup.style.opacity = "0";
                    setTimeout(() => aiPopup.style.display = "none", 500);
                }, 5000);
            }
        });
    </script>
</body>
</html>