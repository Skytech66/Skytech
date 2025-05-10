<?php require_once "header.php"; ?>

<!-- Modern CSS Framework -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700&display=swap" rel="stylesheet">

<div class="dashboard-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="dashboard-title">
                    <i class="fas fa-chalkboard-teacher mr-2"></i> Educator Dashboard
                </h1>
                <nav class="breadcrumb">
                    <span class="breadcrumb-item active">Dashboard</span>
                    <span class="breadcrumb-divider">/</span>
                    <span class="breadcrumb-item">Overview</span>
                </nav>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <div class="notifications">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </div>
                    <div class="profile-dropdown">
                        <div class="profile-avatar">
                            <?php 
                                $initials = '';
                                if (isset($_SESSION['username'])) {
                                    $nameParts = explode(' ', $_SESSION['username']);
                                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                                    if (count($nameParts) > 1) {
                                        $initials .= strtoupper(substr(end($nameParts), 0, 1));
                                    }
                                }
                            ?>
                            <div class="avatar-initials"><?php echo $initials ?: 'U'; ?></div>
                        </div>
                        <div class="profile-info">
                            <span class="user-name"><?php echo $_SESSION['username'] ?? 'User'; ?></span>
                            <span class="user-role">Facilitator</span>
                        </div>
                        <i class="fas fa-chevron-down dropdown-arrow"></i>
                    </div>
                </div>
                <!-- Dark Mode Toggle moved to right -->
                <div class="dark-mode-toggle">
                    <input type="checkbox" id="darkModeToggle" class="toggle-checkbox">
                    <label for="darkModeToggle" class="toggle-label">
                        <i class="fas fa-sun"></i>
                        <i class="fas fa-moon"></i>
                        <span class="toggle-ball"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Section -->
    <div class="metrics-section">
        <!-- Total Students Card -->
        <div class="metric-card student-card">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fas fa-user-graduate"></i>
                </div>
            </div>
            <div class="metric-content">
                <span class="metric-label">Total Students</span>
                <?php
                    $stmt = $conn->query("SELECT COUNT(name) as 'tstudents' FROM student");
                    $row = $stmt->fetchArray(SQLITE3_ASSOC);
                    $totalStudents = $row['tstudents'] ?? 0;
                ?>
                <span class="metric-value"><?php echo $totalStudents; ?></span>
                <div class="metric-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>5% from last term</span>
                </div>
            </div>
            <div class="metric-wave">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none">
                    <path d="M0,10 C20,15 40,5 60,15 C80,25 100,5 100,10 L100,20 L0,20 Z"></path>
                </svg>
            </div>
        </div>

        <!-- Active Classes Card -->
        <div class="metric-card classes-card">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fas fa-school"></i>
                </div>
            </div>
            <div class="metric-content">
                <span class="metric-label">Active Classes</span>
                <?php
                    $stmt = $conn->query("SELECT COUNT(DISTINCT class) as 'tclasses' FROM student");
                    $row = $stmt->fetchArray(SQLITE3_ASSOC);
                    $totalClasses = $row['tclasses'] ?? 0;
                ?>
                <span class="metric-value"><?php echo $totalClasses; ?></span>
                <div class="metric-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>2 new this term</span>
                </div>
            </div>
            <div class="metric-wave">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none">
                    <path d="M0,10 C20,15 40,5 60,15 C80,25 100,5 100,10 L100,20 L0,20 Z"></path>
                </svg>
            </div>
        </div>

        <!-- Attendance Rate Card -->
        <div class="metric-card attendance-card">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fas fa-calendar-check"></i>
                </div>
            </div>
            <div class="metric-content">
                <span class="metric-label">Attendance Rate</span>
                <span class="metric-value">94%</span>
                <div class="metric-trend">
                    <i class="fas fa-arrow-up"></i>
                    <span>3% improvement</span>
                </div>
            </div>
            <div class="metric-wave">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none">
                    <path d="M0,10 C20,15 40,5 60,15 C80,25 100,5 100,10 L100,20 L0,20 Z"></path>
                </svg>
            </div>
        </div>

        <!-- Pending Tasks Card -->
        <div class="metric-card tasks-card">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="metric-content">
                <span class="metric-label">Pending Tasks</span>
                <span class="metric-value">7</span>
                <div class="metric-trend negative">
                    <i class="fas fa-arrow-down"></i>
                    <span>2 overdue</span>
                </div>
            </div>
            <div class="metric-wave">
                <svg viewBox="0 0 100 20" preserveAspectRatio="none">
                    <path d="M0,10 C20,15 40,5 60,15 C80,25 100,5 100,10 L100,20 L0,20 Z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Quick Actions Section -->
    <div class="actions-section">
        <div class="section-header">
            <h2><i class="fas fa-bolt mr-2"></i>Quick Actions</h2>
            <div class="section-actions">
                <button class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-cog"></i> Customize
                </button>
            </div>
        </div>
        <div class="action-grid">
            <a href="lesson_notes.php" class="action-card">
                <div class="action-icon notes">
                    <i class="fas fa-book-open"></i>
                </div>
                <h3>Lesson Notes</h3>
                <p>Create and manage your teaching materials</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="login.php" class="action-card">
                <div class="action-icon attendance">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3>Attendance</h3>
                <p>Mark and track student attendance</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon assignments">
                    <i class="fas fa-tasks"></i>
                </div>
                <h3>Assignments</h3>
                <p>Create and grade student work</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon reports">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Reports</h3>
                <p>Generate performance reports</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon messages">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>Messages</h3>
                <p>Communicate with students/parents</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card">
                <div class="action-icon resources">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>Resources</h3>
                <p>Access teaching resources</p>
                <div class="action-hover-effect"></div>
            </a>
        </div>
    </div>

    <!-- Calendar and Activity Section -->
    <div class="bottom-section">
        <!-- Calendar Section -->
        <div class="calendar-section">
            <div class="section-header">
                <h2><i class="far fa-calendar-alt mr-2"></i>Academic Calendar</h2>
                <div class="calendar-actions">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-calendar-day"></i> Today
                    </button>
                    <button class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Event
                    </button>
                </div>
            </div>
            <div id="calendar"></div>
        </div>

        <!-- Recent Activity Section -->
        <div class="activity-section">
            <div class="section-header">
                <h2><i class="fas fa-bell mr-2"></i>Recent Activity</h2>
                <a href="#" class="view-all">View All</a>
            </div>
            <div class="activity-list">
                <div class="activity-item">
                    <div class="activity-icon success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Attendance Marked</h3>
                            <span class="activity-time">2h ago</span>
                        </div>
                        <p>Class 10A - Mathematics (32 students present)</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon primary">
                        <i class="fas fa-upload"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Lesson Notes Uploaded</h3>
                            <span class="activity-time">1d ago</span>
                        </div>
                        <p>Week 5 materials for all classes</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon info">
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>New Message</h3>
                            <span class="activity-time">2d ago</span>
                        </div>
                        <p>From Parent: Jane Doe (Regarding: Term Project)</p>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon warning">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Assignment Due</h3>
                            <span class="activity-time">3d ago</span>
                        </div>
                        <p>Algebra II assignment due tomorrow</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

<script>
    $(document).ready(function () {
        // Dark Mode Toggle
        const darkModeToggle = document.getElementById('darkModeToggle');
        const html = document.documentElement;
        
        // Check for saved user preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            html.classList.add('dark-mode');
            darkModeToggle.checked = true;
        }
        
        darkModeToggle.addEventListener('change', function() {
            if (this.checked) {
                html.classList.add('dark-mode');
                localStorage.setItem('darkMode', 'enabled');
            } else {
                html.classList.remove('dark-mode');
                localStorage.setItem('darkMode', 'disabled');
            }
        });

        // Initialize Student Distribution Chart
        const studentCtx = document.getElementById('studentDonutChart')?.getContext('2d');
        if (studentCtx) {
            const studentDonutChart = new Chart(studentCtx, {
                type: 'doughnut',
                data: {
                    labels: [
                        <?php
                            $classData = $conn->query("SELECT class, COUNT(*) as studentCount FROM student GROUP BY class");
                            $labels = [];
                            $data = [];
                            while ($row = $classData->fetchArray(SQLITE3_ASSOC)) {
                                $labels[] = "'" . $row['class'] . "'";
                                $data[] = $row['studentCount'];
                            }
                            echo implode(",", $labels);
                        ?>
                    ],
                    datasets: [{
                        data: [<?php echo implode(",", $data); ?>],
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
                                },
                                color: 'var(--text-primary)'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'var(--bg-secondary)',
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
        }

        // Initialize Calendar
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
                    // Create a custom modal for event details
                    const modal = `
                        <div class="event-modal">
                            <div class="modal-header">
                                <h3>${info.event.title}</h3>
                                <button class="close-modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <p><i class="fas fa-calendar-day"></i> ${info.event.start.toLocaleDateString()}</p>
                                ${info.event.end ? `<p><i class="fas fa-clock"></i> ${info.event.start.toLocaleTimeString()} - ${info.event.end.toLocaleTimeString()}</p>` : ''}
                                <div class="modal-actions">
                                    <button class="btn btn-secondary">Edit</button>
                                    <button class="btn btn-primary">View Details</button>
                                </div>
                            </div>
                        </div>
                        <div class="modal-overlay"></div>
                    `;
                    
                    $('body').append(modal);
                    
                    $('.close-modal, .modal-overlay').on('click', function() {
                        $('.event-modal, .modal-overlay').remove();
                    });
                },
                dayHeaderContent: function(arg) {
                    return arg.text.replace('day', '').charAt(0).toUpperCase() + arg.text.replace('day', '').slice(1);
                },
                height: 'auto'
            });
            calendar.render();
        }

        // Profile dropdown functionality
        $('.profile-dropdown').on('click', function() {
            $(this).toggleClass('active');
            $('.dropdown-arrow').toggleClass('rotate-180');
        });

        // Notification bell animation
        $('.notifications').on('click', function() {
            $(this).find('i').addClass('animate-bell');
            setTimeout(() => {
                $(this).find('i').removeClass('animate-bell');
            }, 1000);
        });
    });
</script>

<style>
    :root {
        /* Light Mode Colors */
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
        --text-primary: #1F2937;
        --text-secondary: #6B7280;
        --bg-primary: #FFFFFF;
        --bg-secondary: #F9FAFB;
        --border-color: #E5E7EB;
        
        /* Shadows */
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        
        /* Border Radius */
        --rounded-sm: 0.125rem;
        --rounded: 0.25rem;
        --rounded-md: 0.375rem;
        --rounded-lg: 0.5rem;
        --rounded-xl: 0.75rem;
        --rounded-full: 9999px;
        
        /* Transitions */
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Dark Mode Variables */
    .dark-mode {
        --text-primary: #F9FAFB;
        --text-secondary: #D1D5DB;
        --bg-primary: #111827;
        --bg-secondary: #1F2937;
        --border-color: #374151;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.3), 0 1px 2px 0 rgba(0, 0, 0, 0.2);
        --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
        --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
        --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: var(--bg-primary);
        color: var(--text-primary);
        line-height: 1.5;
        transition: var(--transition);
    }

    /* Dashboard Container */
    .dashboard-container {
        max-width: 1800px;
        margin: 0 auto;
        padding: 24px;
        min-height: 100vh;
    }

    /* Dashboard Header */
    .dashboard-header {
        background: var(--bg-secondary);
        border-radius: var(--rounded-xl);
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .header-right {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .header-left {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .dashboard-title {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        font-size: 1.75rem;
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        font-size: 0.875rem;
        color: var(--text-secondary);
    }

    .breadcrumb-item {
        color: var(--text-secondary);
        transition: var(--transition);
    }

    .breadcrumb-item.active {
        color: var(--primary);
        font-weight: 500;
    }

    .breadcrumb-divider {
        margin: 0 8px;
        color: var(--border-color);
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .notifications {
        position: relative;
        font-size: 1.25rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
    }

    .notifications:hover {
        color: var(--text-primary);
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

    .animate-bell {
        animation: bellRing 0.5s ease-in-out;
        transform-origin: top center;
    }

    @keyframes bellRing {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(15deg); }
        50% { transform: rotate(-15deg); }
        75% { transform: rotate(5deg); }
    }

    .profile-dropdown {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px 12px;
        border-radius: var(--rounded-lg);
        cursor: pointer;
        transition: var(--transition);
        position: relative;
    }

    .profile-dropdown:hover {
        background-color: var(--border-color);
    }

    .profile-avatar {
        width: 40px;
        height: 40px;
        border-radius: var(--rounded-full);
        background-color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 1rem;
    }

    .profile-info {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 600;
        font-size: 0.9375rem;
        color: var(--text-primary);
        transition: var(--transition);
    }

    .user-role {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        transition: var(--transition);
    }

    .dropdown-arrow {
        font-size: 0.75rem;
        color: var(--text-secondary);
        transition: var(--transition);
    }

    .rotate-180 {
        transform: rotate(180deg);
    }

    /* Dark Mode Toggle */
    .dark-mode-toggle {
        position: relative;
        z-index: 1;
    }

    .toggle-checkbox {
        display: none;
    }

    .toggle-label {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 30px;
        background-color: var(--bg-secondary);
        border-radius: var(--rounded-full);
        cursor: pointer;
        box-shadow: var(--shadow-md);
        transition: var(--transition);
    }

    .toggle-label i {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        font-size: 14px;
        transition: var(--transition);
    }

    .toggle-label .fa-sun {
        left: 8px;
        color: #F59E0B;
    }

    .toggle-label .fa-moon {
        right: 8px;
        color: #6366F1;
    }

    .toggle-ball {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 24px;
        height: 24px;
        background-color: var(--primary);
        border-radius: var(--rounded-full);
        transition: transform 0.3s ease;
    }

    .toggle-checkbox:checked + .toggle-label .toggle-ball {
        transform: translateX(30px);
    }

    /* Metrics Section */
    .metrics-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .metric-card {
        background: var(--bg-secondary);
        border-radius: var(--rounded-xl);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        display: flex;
        gap: 16px;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

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
        z-index: 1;
    }

    .icon-bg {
        width: 48px;
        height: 48px;
        border-radius: var(--rounded-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
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
        font-size: 1.25rem;
    }

    .metric-content {
        flex: 1;
        z-index: 1;
    }

    .metric-label {
        display: block;
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin-bottom: 4px;
        transition: var(--transition);
    }

    .metric-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 4px;
        transition: var(--transition);
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 0.8125rem;
        color: var(--success);
    }

    .metric-trend.negative {
        color: var(--danger);
    }

    .metric-trend i {
        font-size: 0.75rem;
    }

    .metric-wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 20px;
        opacity: 0.1;
    }

    .metric-wave svg {
        width: 100%;
        height: 100%;
    }

    .student-card .metric-wave path {
        fill: var(--primary);
    }

    .classes-card .metric-wave path {
        fill: var(--info);
    }

    .attendance-card .metric-wave path {
        fill: var(--success);
    }

    .tasks-card .metric-wave path {
        fill: var(--warning);
    }

    /* Actions Section */
    .actions-section {
        background: var(--bg-secondary);
        border-radius: var(--rounded-xl);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        margin-bottom: 24px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .section-header h2 {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        transition: var(--transition);
    }

    .section-header h2 i {
        margin-right: 10px;
        font-size: 1.1em;
    }

    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }

    .action-card {
        background: var(--bg-primary);
        border-radius: var(--rounded-lg);
        padding: 20px;
        text-decoration: none;
        transition: var(--transition);
        border: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-light);
    }

    .action-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--rounded-lg);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 16px;
        font-size: 1.25rem;
        color: white;
        transition: var(--transition);
    }

    .action-icon.notes {
        background-color: var(--primary);
    }

    .action-icon.attendance {
        background-color: var(--success);
    }

    .action-icon.assignments {
        background-color: var(--warning);
    }

    .action-icon.reports {
        background-color: var(--info);
    }

    .action-icon.messages {
        background-color: #8B5CF6;
    }

    .action-icon.resources {
        background-color: #EC4899;
    }

    .action-card h3 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
        transition: var(--transition);
    }

    .action-card p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        transition: var(--transition);
    }

    .action-hover-effect {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        opacity: 0;
        transition: var(--transition);
    }

    .action-card:hover .action-hover-effect {
        opacity: 1;
    }

    /* Bottom Section */
    .bottom-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    @media (max-width: 1200px) {
        .bottom-section {
            grid-template-columns: 1fr;
        }
    }

    /* Calendar Section */
    .calendar-section {
        background: var(--bg-secondary);
        border-radius: var(--rounded-xl);
        padding: 24px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
    }

    #calendar {
        margin-top: 16px;
    }

    .fc {
        font-family: 'Inter', sans-serif;
    }

    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .fc .fc-button {
        background-color: var(--bg-primary);
        border: 1px solid var(--border-color);
        color: var(--text-primary);
        font-size: 0.875rem;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: var(--rounded-md);
        transition: var(--transition);
    }

    .fc .fc-button:hover {
        background-color: var(--border-color);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: var(--primary);
        border-color: var(--primary);
    }

    .fc .fc-daygrid-day-number {
        color: var(--text-primary);
        font-weight: 500;
        padding: 4px;
    }

    .fc .fc-daygrid-day.fc-day-today {
        background-color: var(--primary-light);
    }

    .fc .fc-daygrid-event {
        border-radius: var(--rounded-sm);
        padding: 2px 4px;
        font-size: 0.8125rem;
    }

    .fc .fc-daygrid-event-dot {
        display: none;
    }

    /* Activity Section */
    .view-all {
        font-size: 0.875rem;
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
        transition: var(--transition);
    }

    .view-all:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .activity-item {
        display: flex;
        gap: 16px;
        padding: 16px;
        border-radius: var(--rounded-lg);
        background: var(--bg-primary);
        transition: var(--transition);
        border: 1px solid var(--border-color);
    }

    .activity-item:hover {
        background: var(--bg-secondary);
        transform: translateX(5px);
        box-shadow: var(--shadow-sm);
        border-color: var(--border-color);
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--rounded-full);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: white;
    }

    .activity-icon.success {
        background-color: var(--success);
    }

    .activity-icon.primary {
        background-color: var(--primary);
    }

    .activity-icon.info {
        background-color: var(--info);
    }

    .activity-icon.warning {
        background-color: var(--warning);
    }

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
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--text-primary);
        transition: var(--transition);
    }

    .activity-time {
        font-size: 0.8125rem;
        color: var(--text-secondary);
        transition: var(--transition);
    }

    .activity-content p {
        font-size: 0.875rem;
        color: var(--text-secondary);
        margin: 0;
        transition: var(--transition);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 16px;
        border-radius: var(--rounded-md);
        font-size: 0.875rem;
        font-weight: 500;
        line-height: 1.5;
        cursor: pointer;
        transition: var(--transition);
        border: 1px solid transparent;
        gap: 8px;
    }

    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8125rem;
    }

    .btn-primary {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    .btn-primary:hover {
        background-color: var(--primary-dark);
        border-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .btn-secondary {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        border-color: var(--border-color);
    }

    .btn-secondary:hover {
        background-color: var(--bg-secondary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    .btn-outline-secondary {
        background-color: var(--bg-primary);
        color: var(--text-secondary);
        border-color: var(--border-color);
    }

    .btn-outline-secondary:hover {
        background-color: var(--bg-secondary);
        color: var(--text-primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-sm);
    }

    /* Event Modal */
    .event-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: var(--bg-primary);
        border-radius: var(--rounded-xl);
        padding: 24px;
        width: 90%;
        max-width: 500px;
        z-index: 1001;
        box-shadow: var(--shadow-xl);
        border: 1px solid var(--border-color);
    }

    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: var(--transition);
    }

    .close-modal:hover {
        color: var(--text-primary);
    }

    .modal-body p {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        color: var(--text-primary);
    }

    .modal-body i {
        width: 20px;
        text-align: center;
        color: var(--text-secondary);
    }

    .modal-actions {
        display: flex;
        gap: 12px;
        margin-top: 20px;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .dashboard-container {
            padding: 16px;
        }
        
        .metrics-section {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .user-profile {
            width: 100%;
            justify-content: space-between;
        }
        
        .metrics-section {
            grid-template-columns: 1fr;
        }
        
        .action-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .action-grid {
            grid-template-columns: 1fr;
        }
        
        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }
        
        .form-select {
            width: 100%;
        }
    }
</style>
