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
        }
        body {
            font-family: 'Inter', system-ui;
            background-color: var(--surface);
        }
        .ai-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--ai-accent) 100%);
            color: white;
            border-radius: 12px;
        }
        .date-picker-container {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .date-picker-btn {
            background: white;
            border: 1px solid var(--border);
            padding: 8px 12px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            cursor: pointer;
            min-width: 160px;
            justify-content: space-between;
            position: relative;
        }
        .hidden-date-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .student-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .student-card i {
            margin-right: 8px;
            color: var(--primary);
        }
        .student-card select {
            min-width: 120px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="ai-header p-4 mb-4">
            <h3><i class="fas fa-robot"></i> AI-Powered Attendance</h3>
            <p class="mb-0">Smart tracking with pattern recognition</p>
        </div>

        <!-- Date Picker -->
        <div class="date-picker-container">
            <label class="date-picker-btn">
                <input type="date" id="attendanceDate" class="hidden-date-input">
                <span id="selectedDateText"></span>
                <i class="fas fa-calendar-alt"></i>
            </label>
        </div>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fas fa-user-plus"></i> Add Student
        </button>

        <div id="studentList"></div>

        <!-- Attendance History Section -->
        <h4 class="fw-bold text-primary mt-4"><i class="fas fa-clipboard-list"></i> Attendance History</h4>
        <a href="view_attendance.html" class="btn btn-outline-primary mb-3">
            <i class="fas fa-file-alt"></i> View Full Attendance Records
        </a>

        <div class="fixed-bottom bg-white p-3 border-top">
            <div class="container">
                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-light" onclick="saveDraft()">Save Draft</button>
                    <button class="btn btn-primary" onclick="submitAttendance()">
                        <i class="fas fa-cloud-upload-alt"></i> Submit Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addStudentForm" onsubmit="addStudent(event)">
                        <div class="mb-3">
                            <label for="studentName" class="form-label">Student Name</label>
                            <input type="text" class="form-control" id="studentName" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Add Student</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let students = JSON.parse(localStorage.getItem("students")) || [];

        function loadStudents() {
            const studentList = document.getElementById("studentList");
            studentList.innerHTML = students.length === 0 
                ? "<p class='text-muted'>No students added yet.</p>" 
                : students.map(student => `
                    <div class="student-card">
                        <div class="fw-medium"><i class="fas fa-user"></i> ${student.name}</div>
                        <select class="form-select form-select-sm attendance-status">
                            <option value="">Select</option>
                            <option value="present">Present</option>
                            <option value="late">Late</option>
                            <option value="absent">Absent</option>
                            <option value="excused">Excused</option>
                        </select>
                    </div>
                `).join("");
        }

        function addStudent(event) {
            event.preventDefault();

            const studentName = document.getElementById("studentName").value.trim();
            if (studentName === "") {
                alert("Please enter a student name.");
                return;
            }

            students.push({ name: studentName });
            localStorage.setItem("students", JSON.stringify(students));

            loadStudents();
            document.getElementById("addStudentForm").reset();

            let modal = bootstrap.Modal.getInstance(document.getElementById("addStudentModal"));
            modal.hide();
        }

        function updateDate() {
            const dateInput = document.getElementById("attendanceDate");
            const dateText = document.getElementById("selectedDateText");

            const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
            dateInput.value = today; // Set input value
            dateText.innerText = today; // Update displayed text
        }

        function submitAttendance() {
            const selectedDate = document.getElementById("attendanceDate").value;
            if (!selectedDate) {
                alert("Please select a date before submitting attendance.");
                return;
            }

            let attendanceData = JSON.parse(localStorage.getItem("attendanceRecords")) || {};
            let studentData = [];

            document.querySelectorAll(".student-card").forEach(card => {
                const name = card.querySelector(".fw-medium").innerText.trim();
                const status = card.querySelector(".attendance-status").value;
                if (!status) {
                    alert("Please mark attendance for all students.");
                    return;
                }
                studentData.push({ name, status });
            });

            attendanceData[selectedDate] = studentData;
            localStorage.setItem("attendanceRecords", JSON.stringify(attendanceData));

            alert("Attendance submitted successfully!");
        }

        document.addEventListener("DOMContentLoaded", () => {
            updateDate();
            loadStudents();
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>