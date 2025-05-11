<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
    <title>Educator Dashboard</title>

    <!-- Preload fonts and CSS for performance (FOUC prevention) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.rel='stylesheet'"/>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" as="style" onload="this.rel='stylesheet'"/>
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" as="style" onload="this.rel='stylesheet'"/>
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet"/>
    </noscript>

    <style>
        /* Critical CSS Inlined for FOUC Prevention and base styling */
        :root {
            --primary: #6366F1;
            --primary-light: #C7D2FE;
            --primary-dark: #4F46E5;
            --secondary: #6B7280;
            --success: #10B981;
            --success-light: #D1FAE5;
            --info: #3B82F6;
            --info-light: #DBEAFE;
            --warning: #F59E0B;
            --warning-light: #FEF3C7;
            --danger: #EF4444;
            --danger-light: #FEE2E2;
            --light: #F9FAFB;
            --dark: #1F2937;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-700: #374151;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --rounded-sm: 0.125rem;
            --rounded: 0.25rem;
            --rounded-md: 0.375rem;
            --rounded-lg: 0.5rem;
            --rounded-xl: 0.75rem;
            --rounded-full: 9999px;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light);
            color: var(--dark);
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Container max-width reduced for mobile and desktop */
        .dashboard-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 16px 12px 24px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            gap: 16px;
            overflow-x: hidden;
        }

        /* Dashboard Header */
        .dashboard-header {
            background: white;
            border-radius: var(--rounded-xl);
            padding: 16px 20px;
            box-shadow: var(--shadow-sm);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 8px;
        }

        .header-left {
            display: flex;
            flex-direction: column;
            gap: 6px;
            min-width: 170px;
        }

        .dashboard-title {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 700;
            color: var(--dark);
            font-size: 1.35rem;
            display: flex;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            color: var(--secondary);
            flex-wrap: wrap;
            gap: 6px;
            user-select: none;
        }

        .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 500;
        }

        .breadcrumb-divider {
            color: var(--gray-300);
            user-select: none;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: nowrap;
        }

        .notifications {
            position: relative;
            font-size: 1.2rem;
            color: var(--secondary);
            cursor: pointer;
            transition: color 0.2s;
        }
        .notifications:hover {
            color: var(--dark);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: var(--danger);
            color: white;
            border-radius: var(--rounded-full);
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 600;
        }

        .profile-dropdown {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 10px;
            border-radius: var(--rounded-lg);
            cursor: pointer;
            transition: background-color 0.2s;
            white-space: nowrap;
        }
        .profile-dropdown:hover {
            background-color: var(--gray-100);
        }

        .profile-icon {
            font-size: 1.8rem;
            color: var(--primary);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--dark);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--secondary);
        }

        .dropdown-arrow {
            font-size: 0.7rem;
            color: var(--secondary);
            transition: transform 0.2s;
            margin-left: 2px;
        }
        .profile-dropdown:hover .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Metrics Section - stacked vertically for mobiles */
        .metrics-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }

        @media(min-width: 480px) {
            .metrics-section {
                grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            }
        }

        .metric-card {
            background: white;
            border-radius: var(--rounded-xl);
            padding: 14px 18px;
            box-shadow: var(--shadow-sm);
            display: flex;
            gap: 14px;
            align-items: center;
            cursor: default;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }

        /* border-left color accent */
        .student-card {
            border-left: 4px solid var(--primary);
        }
        .classes-card {
            border-left: 4px solid var(--info);
        }
        .attendance-card {
            border-left: 4px solid var(--success);
        }
        .tasks-card {
            border-left: 4px solid var(--warning);
        }

        .metric-icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-bg {
            width: 42px;
            height: 42px;
            border-radius: var(--rounded-lg);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .student-card .icon-bg {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }
        .classes-card .icon-bg {
            background-color: var(--info-light);
            color: var(--info);
        }
        .attendance-card .icon-bg {
            background-color: var(--success-light);
            color: var(--success);
        }
        .tasks-card .icon-bg {
            background-color: var(--warning-light);
            color: var(--warning);
        }

        .metric-icon i {
            font-size: 1.2rem;
        }

        .metric-content {
            flex: 1;
        }

        .metric-label {
            font-size: 0.8rem;
            color: var(--secondary);
            margin-bottom: 2px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .metric-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--dark);
        }

        .metric-trend {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--success);
            margin-top: 4px;
        }
        .metric-trend.negative {
            color: var(--danger);
        }
        .metric-trend i {
            font-size: 0.75rem;
        }

        /* Quick Actions Section */
        .actions-section {
            background: white;
            border-radius: var(--rounded-xl);
            padding: 16px 20px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 20px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .section-header h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .section-header h2 i {
            font-size: 1.1em;
            color: var(--primary);
        }

        .action-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 14px;
        }
        @media(min-width: 480px) {
            .action-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        @media(min-width: 720px) {
            .action-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media(min-width: 1024px) {
            .action-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .action-card {
            background: white;
            border-radius: var(--rounded-lg);
            padding: 18px 16px;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid var(--gray-200);
            color: inherit;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
            cursor: pointer;
        }
        .action-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-light);
            color: var(--primary-dark);
        }
        .action-card:focus-visible {
            outline: 2px solid var(--primary);
            outline-offset: 3px;
        }

        .action-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--rounded-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .action-icon.notes    { background-color: var(--primary); }
        .action-icon.attendance { background-color: var(--success); }
        .action-icon.assignments { background-color: var(--warning); }
        .action-icon.reports { background-color: var(--info); }
        .action-icon.messages { background-color: #8B5CF6; }
        .action-icon.resources { background-color: #EC4899; }

        .action-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--dark);
        }
        .action-card p {
            font-size: 0.85rem;
            color: var(--secondary);
            margin: 0;
            line-height: 1.3;
        }

        /* Bottom Section: Calendar and Activity list stack on mobile */
        .bottom-section {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media(min-width: 768px) {
            .bottom-section {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Calendar Section */
        .calendar-section {
            background: white;
            border-radius: var(--rounded-xl);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
        }
        #calendar {
            margin-top: 12px;
            max-height: 320px;
            overflow-y: auto;
        }

        /* FullCalendar styles override */
        .fc {
            font-family: 'Inter', sans-serif;
        }
        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--dark);
        }
        .fc .fc-button {
            background-color: white;
            border: 1px solid var(--gray-200);
            color: var(--dark);
            font-size: 0.8rem;
            font-weight: 500;
            padding: 6px 10px;
            border-radius: var(--rounded-md);
            transition: all 0.2s;
        }
        .fc .fc-button:hover {
            background-color: var(--gray-100);
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        .fc .fc-daygrid-day-number {
            color: var(--dark);
            font-weight: 600;
            padding: 4px 6px;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background-color: var(--primary-light);
        }
        .fc .fc-daygrid-event {
            border-radius: var(--rounded-sm);
            padding: 2px 4px;
            font-size: 0.75rem;
            cursor: pointer;
        }
        .fc .fc-daygrid-event-dot {
            display: none;
        }

        /* Recent Activity Section */
        .activity-section {
            background: white;
            border-radius: var(--rounded-xl);
            padding: 16px 18px;
            box-shadow: var(--shadow-sm);
        }
        .view-all {
            font-size: 0.85rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .view-all:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-height: 320px;
            overflow-y: auto;
        }
        .activity-item {
            display: flex;
            gap: 12px;
            padding: 12px 14px;
            border-radius: var(--rounded-lg);
            background: var(--light);
            transition: all 0.3s ease;
            border: 1px solid var(--gray-200);
            cursor: default;
        }
        .activity-item:hover {
            background: white;
            transform: translateX(4px);
            box-shadow: var(--shadow-sm);
            border-color: var(--gray-300);
        }
        .activity-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--rounded-full);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: white;
            font-size: 1.1rem;
        }
        .activity-icon.success { background-color: var(--success); }
        .activity-icon.primary { background-color: var(--primary); }
        .activity-icon.info { background-color: var(--info); }
        .activity-icon.warning { background-color: var(--warning); }

        .activity-content {
            flex: 1;
        }
        .activity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }
        .activity-header h3 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--dark);
        }
        .activity-time {
            font-size: 0.75rem;
            color: var(--secondary);
            white-space: nowrap;
        }
        .activity-content p {
            font-size: 0.82rem;
            color: var(--secondary);
            margin: 0;
            line-height: 1.2;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 14px;
            border-radius: var(--rounded-md);
            font-size: 0.8rem;
            font-weight: 500;
            line-height: 1.4;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            gap: 6px;
            user-select: none;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.75rem;
        }
        .btn-primary {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        .btn-secondary {
            background-color: white;
            color: var(--dark);
            border-color: var(--gray-300);
        }
        .btn-secondary:hover {
            background-color: var(--gray-100);
        }

        /* Event Modal */
        .event-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: var(--rounded-xl);
            padding: 20px;
            width: 90%;
            max-width: 400px;
            z-index: 1001;
            box-shadow: var(--shadow-xl);
            font-size: 0.875rem;
            user-select: none;
        }
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
        }
        .modal-header h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark);
        }
        .close-modal {
            background: none;
            border: none;
            font-size: 1.4rem;
            color: var(--secondary);
            cursor: pointer;
            transition: color 0.2s;
            padding: 0;
        }
        .close-modal:hover {
            color: var(--dark);
        }
        .modal-body p {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            color: var(--dark);
        }
        .modal-body i {
            width: 20px;
            text-align: center;
        }
        .modal-actions {
            display: flex;
            gap: 12px;
            margin-top: 14px;
        }

        /* Scrollbar styles for calendar and activity */
        #calendar::-webkit-scrollbar,
        .activity-list::-webkit-scrollbar {
            width: 6px;
        }
        #calendar::-webkit-scrollbar-thumb,
        .activity-list::-webkit-scrollbar-thumb {
            background-color: var(--gray-300);
            border-radius: 3px;
        }

        /* Responsive adjustments */
        @media (max-width: 576px) {
            .dashboard-title {
                font-size: 1.2rem;
                gap: 6px;
            }
            .header-left {
                min-width: auto;
            }
            .profile-info {
                display: none;
            }
            .metric-label {
                font-size: 0.75rem;
            }
            .metric-value {
                font-size: 1.2rem;
            }
            .metric-trend {
                font-size: 0.65rem;
            }
            .action-grid {
                grid-template-columns: 1fr;
            }
            .btn {
                font-size: 0.75rem;
                padding: 4px 10px;
            }
            .section-header h2 {
                font-size: 1rem;
            }
            #calendar,
            .activity-list {
                max-height: 260px;
            }
            .bottom-section {
                gap: 16px;
            }
        }
    </style>
</head>
<body>

<?php require_once "header.php"; ?>

<div class="dashboard-container" role="main" aria-label="Educator Dashboard">

    <!-- Dashboard Header -->
    <header class="dashboard-header" role="banner">
        <div class="header-content">
            <div class="header-left">
                <h1 class="dashboard-title">
                    <i class="fas fa-chalkboard-teacher" aria-hidden="true"></i> Educator Dashboard
                </h1>
                <nav class="breadcrumb" aria-label="Breadcrumb">
                    <span class="breadcrumb-item active" aria-current="page">Dashboard</span>
                    <span class="breadcrumb-divider" aria-hidden="true">/</span>
                    <span class="breadcrumb-item">Overview</span>
                </nav>
            </div>
            <div class="user-profile" aria-label="User Profile">
                <button class="notifications" aria-label="You have 3 new notifications">
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    <span class="notification-badge" aria-hidden="true">3</span>
                </button>
                <div class="profile-dropdown" tabindex="0" aria-haspopup="true" aria-expanded="false" aria-label="User menu">
                    <div class="profile-icon" aria-hidden="true">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <div class="profile-info">
                        <span class="user-name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?></span>
                        <span class="user-role">Facilitator</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow" aria-hidden="true"></i>
                </div>
            </div>
        </div>
    </header>

    <!-- Key Metrics Section -->
    <section class="metrics-section" aria-label="Key Metrics">
        <!-- Total Students Card -->
        <article class="metric-card student-card" tabindex="0" aria-labelledby="totalStudentsLabel totalStudentsValue">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fas fa-user-graduate" aria-hidden="true"></i>
                </div>
            </div>
            <div class="metric-content">
                <span class="metric-label" id="totalStudentsLabel">Total Students</span>
                <?php
                    $stmt = $conn->query("SELECT COUNT(name) as 'tstudents' FROM student");
                    $row = $stmt->fetchArray(SQLITE3_ASSOC);
                    $totalStudents = $row['tstudents'] ?? 0;
                ?>
                <span class="metric-value" id="totalStudentsValue"><?php echo intval($totalStudents); ?></span>
                <div class="metric-trend" aria-label="5% increase from last term">
                    <i class="fas fa-arrow-up" aria-hidden="true"></i>
                    <span>5% from last term</span>
                </div>
            </div>
        </article>

        <!-- Active Classes Card -->
        <article class="metric-card classes-card" tabindex="0" aria-labelledby="activeClassesLabel activeClassesValue">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fas fa-school" aria-hidden="true"></i>
                </div>
            </div>
            <div class="metric-content">
                <span class="metric-label" id="activeClassesLabel">Active Classes</span>
                <?php
                    $stmt = $conn->query("SELECT COUNT(DISTINCT class) as 'tclasses' FROM student");
                    $row = $stmt->fetchArray(SQLITE3_ASSOC);
                    $totalClasses = $row['tclasses'] ?? 0;
                ?>
                <span class="metric-value" id="activeClassesValue"><?php echo intval($totalClasses); ?></span>
                <div class="metric-trend" aria-label="2 new classes this term">
                    <i class="fas fa-arrow-up" aria-hidden="true"></i>
                    <span>2 new this term</span>
                </div>
            </div>
        </article>

        <!-- Attendance Rate Card -->
        <article class="metric-card attendance-card" tabindex="0" aria-labelledby="attendanceRateLabel attendanceRateValue">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fas fa-calendar-check" aria-hidden="true"></i>
                </div>
            </div>
            <div class="metric-content">
                <span class="metric-label" id="attendanceRateLabel">Attendance Rate</span>
                <span class="metric-value" id="attendanceRateValue">94%</span>
                <div class="metric-trend" aria-label="3% improvement">
                    <i class="fas fa-arrow-up" aria-hidden="true"></i>
                    <span>3% improvement</span>
                </div>
            </div>
        </article>
    </section>

    <!-- Quick Actions Section -->
    <section class="actions-section" aria-label="Quick Actions">
        <div class="section-header">
            <h2><i class="fas fa-bolt" aria-hidden="true"></i> Quick Actions</h2>
        </div>
        <div class="action-grid">
            <a href="lesson_notes.php" class="action-card" role="button" tabindex="0">
                <div class="action-icon notes" aria-hidden="true">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>Lesson Notes</h3>
                <p>Create and manage your teaching materials</p>
            </a>
            <a href="login.php" class="action-card" role="button" tabindex="0">
                <div class="action-icon attendance" aria-hidden="true">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Attendance</h3>
                <p>Mark and track student attendance</p>
            </a>
            <a href="#" class="action-card" role="button" tabindex="0">
                <div class="action-icon assignments" aria-hidden="true">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Assignments</h3>
                <p>Create and grade student work</p>
            </a>
            <a href="#" class="action-card" role="button" tabindex="0">
                <div class="action-icon reports" aria-hidden="true">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Reports</h3>
                <p>Generate performance reports</p>
            </a>
            <a href="#" class="action-card" role="button" tabindex="0">
                <div class="action-icon messages" aria-hidden="true">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>Messages</h3>
                <p>Communicate with students/parents</p>
            </a>
            <a href="#" class="action-card" role="button" tabindex="0">
                <div class="action-icon resources" aria-hidden="true">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>Resources</h3>
                <p>Access teaching resources</p>
            </a>
        </div>
    </section>

    <!-- Calendar and Activity Section -->
    <section class="bottom-section" aria-label="Calendar and Recent Activity">

        <!-- Calendar Section -->
        <div class="calendar-section" aria-label="Academic Calendar">
            <div class="section-header">
                <h2><i class="far fa-calendar-alt" aria-hidden="true"></i> Academic Calendar</h2>
                <div class="calendar-actions">
                    <button class="btn btn-primary btn-sm" aria-label="Add new calendar event">
                        <i class="fas fa-plus" aria-hidden="true"></i> Add Event
                    </button>
                </div>
            </div>
            <div id="calendar" tabindex="0" aria-live="polite" aria-relevant="additions"></div>
        </div>

        <!-- Recent Activity Section -->
        <div class="activity-section" aria-label="Recent Activity">
            <div class="section-header">
                <h2><i class="fas fa-bell" aria-hidden="true"></i> Recent Activity</h2>
                <a href="#" class="view-all" tabindex="0" aria-label="View all recent activities">View All</a>
            </div>
            <div class="activity-list" role="list" aria-live="polite" aria-relevant="additions">
                <article class="activity-item" role="listitem" tabindex="0" aria-label="Attendance Marked: Class 10A - Mathematics with 32 students present two hours ago">
                    <div class="activity-icon success" aria-hidden="true">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Attendance Marked</h3>
                            <time class="activity-time" datetime="<?php echo date('c', strtotime('-2 hours')); ?>">2h ago</time>
                        </div>
                        <p>Class 10A - Mathematics (32 students present)</p>
                    </div>
                </article>
                <article class="activity-item" role="listitem" tabindex="0" aria-label="Lesson Notes Uploaded: Week 5 materials for all classes one day ago">
                    <div class="activity-icon primary" aria-hidden="true">
                        <i class="fas fa-upload"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Lesson Notes Uploaded</h3>
                            <time class="activity-time" datetime="<?php echo date('c', strtotime('-1 day')); ?>">1d ago</time>
                        </div>
                        <p>Week 5 materials for all classes</p>
                    </div>
                </article>
                <article class="activity-item" role="listitem" tabindex="0" aria-label="New Message: From Parent Jane Doe Regarding Term Project two days ago">
                    <div class="activity-icon info" aria-hidden="true">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>New Message</h3>
                            <time class="activity-time" datetime="<?php echo date('c', strtotime('-2 days')); ?>">2d ago</time>
                        </div>
                        <p>From Parent: Jane Doe (Regarding: Term Project)</p>
                    </div>
                </article>
                <article class="activity-item" role="listitem" tabindex="0" aria-label="Assignment Due: Algebra II assignment due tomorrow 3 days ago">
                    <div class="activity-icon warning" aria-hidden="true">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Assignment Due</h3>
                            <time class="activity-time" datetime="<?php echo date('c', strtotime('-3 days')); ?>">3d ago</time>
                        </div>
                        <p>Algebra II assignment due tomorrow</p>
                    </div>
                </article>
            </div>
        </div>
    </section>

</div>

<?php require_once "../include/footer.php"; ?>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        // Sample Chart.js donut chart initialization for student distribution - placeholder for PHP dynamic data
        const studentCtx = document.createElement('canvas');
        studentCtx.id = 'studentDonutChart';
        studentCtx.style.width = '100%';
        studentCtx.style.height = '280px';

        // Append canvas dynamically if a container exists (create container if needed)
        let distSection = document.querySelector('.distribution-section');
        if (!distSection) {
            distSection = document.createElement('section');
            distSection.classList.add('distribution-section');
            distSection.style.background = 'white';
            distSection.style.borderRadius = 'var(--rounded-xl)';
            distSection.style.padding = '16px 20px';
            distSection.style.marginBottom = '20px';
            distSection.style.boxShadow = 'var(--shadow-sm)';
            document.querySelector('.dashboard-container').insertBefore(distSection, document.querySelector('.actions-section'));
        }
        distSection.appendChild(studentCtx);

        // Prepare PHP data placeholders - replace with actual PHP echo strings if used server-side
        const labels = [
            <?php
                $classData = $conn->query("SELECT class, COUNT(*) as studentCount FROM student GROUP BY class");
                $labels = [];
                while ($row = $classData->fetchArray(SQLITE3_ASSOC)) {
                    echo "'" . addslashes($row['class']) . "', ";
                }
            ?>
        ];

        const data = [
            <?php
                $classData = $conn->query("SELECT class, COUNT(*) as studentCount FROM student GROUP BY class");
                $data = [];
                while ($row = $classData->fetchArray(SQLITE3_ASSOC)) {
                    echo (int)$row['studentCount'] . ", ";
                }
            ?>
        ];

        const studentDonutChart = new Chart(studentCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: [
                        '#6366F1', '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#8B5CF6'
                    ],
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 10,
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                family: 'Inter',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        titleFont: {
                            family: 'Inter',
                            size: 14,
                            weight: '600'
                        },
                        bodyFont: {
                            family: 'Inter',
                            size: 12
                        },
                        padding: 12,
                        usePointStyle: true,
                        cornerRadius: 8,
                        displayColors: false
                    }
                }
            }
        });

        // Initialize FullCalendar
        const calendarEl = document.getElementById('calendar');
        if (calendarEl) {
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                themeSystem: 'standard',
                events: [
                    {
                        title: 'Parent-Teacher Meeting',
                        start: new Date(),
                        backgroundColor: '#6366F1',
                        borderColor: '#6366F1'
                    },
                    {
                        title: 'Term Assessment',
                        start: new Date(new Date().setDate(new Date().getDate() + 5)),
                        backgroundColor: '#10B981',
                        borderColor: '#10B981'
                    },
                    {
                        title: 'Staff Development Day',
                        start: new Date(new Date().setDate(new Date().getDate() + 10)),
                        end: new Date(new Date().setDate(new Date().getDate() + 11)),
                        backgroundColor: '#F59E0B',
                        borderColor: '#F59E0B',
                        allDay: true
                    }
                ],
                eventClick: function(info) {
                    // Create modal for event details
                    const modal = document.createElement('div');
                    modal.classList.add('event-modal');
                    modal.innerHTML = `
                        <div class="modal-header">
                            <h3>${info.event.title}</h3>
                            <button class="close-modal" aria-label="Close modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p><i class="fas fa-calendar-day"></i> ${info.event.start.toLocaleDateString()}</p>
                            ${info.event.end ? `<p><i class="fas fa-clock"></i> ${info.event.start.toLocaleTimeString()} - ${info.event.end.toLocaleTimeString()}</p>` : ''}
                            <div class="modal-actions">
                                <button class="btn btn-secondary">Edit</button>
                                <button class="btn btn-primary">View Details</button>
                            </div>
                        </div>
                    `;
                    const overlay = document.createElement('div');
                    overlay.classList.add('modal-overlay');

                    document.body.appendChild(modal);
                    document.body.appendChild(overlay);

                    overlay.addEventListener('click', () => {
                        modal.remove();
                        overlay.remove();
                    });
                    modal.querySelector('.close-modal').addEventListener('click', () => {
                        modal.remove();
                        overlay.remove();
                    });
                },
                dayHeaderContent: function(arg) {
                    return arg.text.replace('day', '').charAt(0).toUpperCase() + arg.text.replace('day', '').slice(1);
                },
                height: 'auto'
            });
            calendar.render();
        }
    });
</script>
</body>
</html>

