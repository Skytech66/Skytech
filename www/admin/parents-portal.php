<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents' Dashboard</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8f9fa;
        }

        .dashboard-container {
            margin: 20px auto;
            max-width: 1200px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }

        .profile-card {
            text-align: center;
            padding: 20px;
        }

        .profile-pic {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .status-badge {
            font-size: 14px;
            padding: 5px 10px;
            border-radius: 5px;
        }

        .paid { background: #28a745; color: white; }
        .pending { background: #dc3545; color: white; }

        .message-list li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .message-list li:last-child {
            border-bottom: none;
        }
    </style>
</head>
<body>

<div class="container dashboard-container">
    <h2 class="text-center mb-4">Parents' Dashboard</h2>

    <div class="row">
        <!-- Student Profile -->
        <div class="col-md-4">
            <div class="card profile-card">
                <img src="https://via.placeholder.com/80" alt="Student Photo" class="profile-pic">
                <h4>John Doe</h4>
                <p>Grade: Basic 6</p>
                <p>Student ID: #12345</p>
            </div>
        </div>

        <!-- Fees Summary -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5>School Fees</h5>
                <p><strong>Paid:</strong> <span class="status-badge paid">✔ 1,200 cedis</span></p>
                <p><strong>Pending:</strong> <span class="status-badge pending">✖ 200 cedis</span></p>
            </div>
        </div>

        <!-- Attendance Overview -->
        <div class="col-md-4">
            <div class="card p-3">
                <h5>Attendance Summary</h5>
                <p><strong>Present:</strong> 90 days</p>
                <p><strong>Absent:</strong> 5 days</p>
                <p><strong>Late:</strong> 3 days</p>
            </div>
        </div>
    </div>

    <!-- Messages & Announcements -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card p-3">
                <h5>Messages & Announcements</h5>
                <ul class="list-unstyled message-list">
                    <li>📢 PTA meeting scheduled for March 15, 2025.</li>
                    <li>📅 Mid-term exams start next Monday.</li>
                    <li>⚠️ School fees deadline is approaching.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>