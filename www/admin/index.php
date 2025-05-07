<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduTrack | Attendance Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #3B82F6;
            --surface: #F8FAFC;
            --border: #E2E8F0;
        }

        body {
            font-family: 'Inter', system-ui;
            background-color: var(--surface);
        }

        .attendance-card {
            background: white;
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        .nav-header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 1rem 2rem;
        }

        .student-avatar {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            background: #EFF6FF;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .status-pill {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            border: 1px solid transparent;
        }

        .status-present { background: #F0FDF4; color: #16A34A; border-color: #BBF7D0; }
        .status-absent { background: #FEF2F2; color: #DC2626; border-color: #FECACA; }
        .status-late { background: #FFFBEB; color: #D97706; border-color: #FDE68A; }
        .status-excused { background: #F5F3FF; color: #7C3AED; border-color: #DDD6FE; }

        .day-column {
            min-width: 120px;
            transition: background 0.2s ease;
        }

        .day-column:hover {
            background: #F8FAFC;
        }
    </style>
</head>
<body>
    <nav class="nav-header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="text-primary fs-4">
                <i class="bi bi-journal-check"></i>
            </div>
            <h5 class="mb-0 fw-semibold text-gray-800">Attendance Manager</h5>
        </div>
        <div class="d-flex gap-3 align-items-center">
            <button class="btn btn-sm btn-light">
                <i class="bi bi-calendar-week"></i> Week 5
            </button>
            <div class="dropdown">
                <button class="btn btn-sm btn-primary px-3">
                    <i class="bi bi-people"></i> Grade 10A
                </button>
            </div>
        </div>
    </nav>

    <main class="container py-4 px-4">
        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col">
                <div class="attendance-card p-3 d-flex align-items-center gap-3">
                    <div class="student-avatar">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div>
                        <div class="text-sm text-muted">Attendance Rate</div>
                        <div class="h4 mb-0 text-success">94%</div>
                    </div>
                </div>
            </div>
            <!-- Repeat for other stats -->
        </div>

        <!-- Attendance Table -->
        <div class="attendance-card p-2">
            <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                <div class="d-flex gap-2 align-items-center">
                    <input type="search" class="form-control form-control-sm" placeholder="Search students...">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-light">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <button class="btn btn-sm btn-primary">
                        <i class="bi bi-cloud-check"></i> Save
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="ps-4">Student</th>
                            <th class="day-column text-center">Mon 3</th>
                            <th class="day-column text-center">Tue 4</th>
                            <th class="day-column text-center">Wed 5</th>
                            <th class="day-column text-center">Thu 6</th>
                            <th class="day-column text-center">Fri 7</th>
                            <th class="pe-4">Summary</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="student-avatar">
                                        AJ
                                    </div>
                                    <div>
                                        <div class="fw-medium">Alice Johnson</div>
                                        <div class="text-sm text-muted">ID: 10245</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <select class="form-select form-select-sm border-0 bg-transparent">
                                    <option value="present" class="status-present" selected>Present</option>
                                    <option value="absent" class="status-absent">Absent</option>
                                    <option value="late" class="status-late">Late</option>
                                    <option value="excused" class="status-excused">Excused</option>
                                </select>
                            </td>
                            <!-- Repeat for other days -->
                            <td class="pe-4">
                                <div class="d-flex gap-2">
                                    <div class="status-pill status-present">5 Days</div>
                                </div>
                            </td>
                        </tr>
                        <!-- More student rows -->
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>