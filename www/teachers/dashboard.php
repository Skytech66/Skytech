<?php require_once "header.php"; ?>

<!-- Premium Design Framework -->
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet">

<div class="luxury-dashboard">
    <!-- Premium Dashboard Header -->
    <div class="dashboard-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="dashboard-title">
                    <i class="fad fa-chalkboard-teacher mr-2"></i> Teacher's Portal
                </h1>
                <nav class="breadcrumb">
                    <span class="breadcrumb-item active">Dashboard</span>
                    <span class="breadcrumb-divider">/</span>
                </nav>
            </div>
            <div class="user-profile">
                <div class="notifications">
                    <i class="fad fa-bell"></i>
                    <span class="notification-badge">3</span>
                </div>
                <div class="profile-dropdown">
                    <div class="profile-icon">
                        <div class="avatar-initials"><?php echo substr($_SESSION['username'] ?? 'U', 0, 1); ?></div>
                    </div>
                    <div class="profile-info">
                        <span class="user-name"><?php echo $_SESSION['username'] ?? 'User'; ?></span>
                        <span class="user-role">Educator</span>
                    </div>
                    <i class="fas fa-chevron-down dropdown-arrow"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Premium Metrics Section -->
    <div class="metrics-section">
        <!-- Total Students Card -->
        <div class="metric-card student-card animate__animated animate__fadeIn">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fad fa-user-graduate"></i>
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
            <div class="metric-decoration"></div>
        </div>

        <!-- Active Classes Card -->
        <div class="metric-card classes-card animate__animated animate__fadeIn animate__delay-1s">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fad fa-school"></i>
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
            <div class="metric-decoration"></div>
        </div>

        <!-- Attendance Rate Card -->
        <div class="metric-card attendance-card animate__animated animate__fadeIn animate__delay-2s">
            <div class="metric-icon">
                <div class="icon-bg">
                    <i class="fad fa-calendar-check"></i>
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
            <div class="metric-decoration"></div>
        </div>
    </div>

    <!-- Premium Quick Actions Section -->
    <div class="actions-section">
        <div class="section-header">
            <h2><i class="fad fa-bolt mr-2"></i>Quick Actions</h2>
            <div class="section-controls">
                <button class="btn btn-outline-luxury">
                    <i class="fas fa-sliders-h"></i> Customize
                </button>
            </div>
        </div>
        <div class="action-grid">
            <a href="lesson_notes.php" class="action-card animate__animated animate__fadeIn">
                <div class="action-icon notes">
                    <i class="fad fa-book-open"></i>
                </div>
                <h3>Lesson Notes</h3>
                <p>Create and manage your teaching materials</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="login.php" class="action-card animate__animated animate__fadeIn animate__delay-1s">
                <div class="action-icon attendance">
                    <i class="fad fa-calendar-check"></i>
                </div>
                <h3>Attendance</h3>
                <p>Mark and track student attendance</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card animate__animated animate__fadeIn animate__delay-2s">
                <div class="action-icon assignments">
                    <i class="fad fa-tasks"></i>
                </div>
                <h3>Assignments</h3>
                <p>Create and grade student work</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card animate__animated animate__fadeIn animate__delay-3s">
                <div class="action-icon reports">
                    <i class="fad fa-chart-bar"></i>
                </div>
                <h3>Reports</h3>
                <p>Generate performance reports</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card animate__animated animate__fadeIn animate__delay-1s">
                <div class="action-icon messages">
                    <i class="fad fa-comments"></i>
                </div>
                <h3>Messages</h3>
                <p>Communicate with students/parents</p>
                <div class="action-hover-effect"></div>
            </a>
            <a href="#" class="action-card animate__animated animate__fadeIn animate__delay-2s">
                <div class="action-icon resources">
                    <i class="fad fa-folder-open"></i>
                </div>
                <h3>Resources</h3>
                <p>Access teaching resources</p>
                <div class="action-hover-effect"></div>
            </a>
        </div>
    </div>

    <!-- Premium Calendar and Activity Section -->
    <div class="bottom-section">
        <!-- Premium Calendar Section -->
        <div class="calendar-section">
            <div class="section-header">
                <h2><i class="fad fa-calendar-alt mr-2"></i>Academic Calendar</h2>
                <div class="section-controls">
                    <button class="btn btn-luxury">
                        <i class="fas fa-plus"></i> Add Event
                    </button>
                </div>
            </div>
            <div id="calendar"></div>
        </div>

        <!-- Premium Recent Activity Section -->
        <div class="activity-section">
            <div class="section-header">
                <h2><i class="fad fa-bell mr-2"></i>Recent Activity</h2>
                <a href="#" class="view-all">View All <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="activity-list">
                <div class="activity-item animate__animated animate__fadeIn">
                    <div class="activity-icon success">
                        <i class="fad fa-check-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Attendance Marked</h3>
                            <span class="activity-time">2h ago</span>
                        </div>
                        <p>Class 10A - Mathematics (32 students present)</p>
                    </div>
                    <div class="activity-decoration"></div>
                </div>
                <div class="activity-item animate__animated animate__fadeIn animate__delay-1s">
                    <div class="activity-icon primary">
                        <i class="fad fa-upload"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Lesson Notes Uploaded</h3>
                            <span class="activity-time">1d ago</span>
                        </div>
                        <p>Week 5 materials for all classes</p>
                    </div>
                    <div class="activity-decoration"></div>
                </div>
                <div class="activity-item animate__animated animate__fadeIn animate__delay-2s">
                    <div class="activity-icon info">
                        <i class="fad fa-comment-alt"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>New Message</h3>
                            <span class="activity-time">2d ago</span>
                        </div>
                        <p>From Parent: Jane Doe (Regarding: Term Project)</p>
                    </div>
                    <div class="activity-decoration"></div>
                </div>
                <div class="activity-item animate__animated animate__fadeIn animate__delay-3s">
                    <div class="activity-icon warning">
                        <i class="fad fa-exclamation-circle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-header">
                            <h3>Assignment Due</h3>
                            <span class="activity-time">3d ago</span>
                        </div>
                        <p>Algebra II assignment due tomorrow</p>
                    </div>
                    <div class="activity-decoration"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- Premium JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize Calendar with premium settings
        const calendarEl = document.getElementById('calendar');
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
                    backgroundColor: '#8B5CF6',
                    borderColor: '#8B5CF6',
                    textColor: '#FFFFFF'
                },
                {
                    title: 'Term Assessment',
                    start: new Date(new Date().setDate(new Date().getDate() + 5)),
                    backgroundColor: '#10B981',
                    borderColor: '#10B981',
                    textColor: '#FFFFFF'
                },
                {
                    title: 'Staff Development Day',
                    start: new Date(new Date().setDate(new Date().getDate() + 10)),
                    end: new Date(new Date().setDate(new Date().getDate() + 11)),
                    backgroundColor: '#F59E0B',
                    borderColor: '#F59E0B',
                    allDay: true,
                    textColor: '#FFFFFF'
                }
            ],
            eventClick: function(info) {
                // Create a premium modal for event details
                const modal = `
                    <div class="luxury-modal">
                        <div class="modal-header">
                            <h3>${info.event.title}</h3>
                            <button class="close-modal"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="modal-body">
                            <p><i class="fad fa-calendar-day"></i> ${info.event.start.toLocaleDateString()}</p>
                            ${info.event.end ? `<p><i class="fad fa-clock"></i> ${info.event.start.toLocaleTimeString()} - ${info.event.end.toLocaleTimeString()}</p>` : ''}
                            <div class="modal-actions">
                                <button class="btn btn-outline-luxury">Edit</button>
                                <button class="btn btn-luxury">View Details</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-overlay"></div>
                `;
                
                $('body').append(modal);
                
                // Animate modal appearance
                gsap.from('.luxury-modal', {
                    duration: 0.3,
                    y: 50,
                    opacity: 0,
                    ease: "power2.out"
                });
                
                $('.close-modal, .modal-overlay').on('click', function() {
                    gsap.to('.luxury-modal', {
                        duration: 0.2,
                        y: 50,
                        opacity: 0,
                        ease: "power2.in",
                        onComplete: function() {
                            $('.luxury-modal, .modal-overlay').remove();
                        }
                    });
                });
            },
            dayHeaderContent: function(arg) {
                return arg.text.replace('day', '').charAt(0).toUpperCase() + arg.text.replace('day', '').slice(1);
            },
            height: 'auto'
        });
        calendar.render();

        // Animate elements on scroll
        gsap.utils.toArray('.animate__animated').forEach(element => {
            ScrollTrigger.create({
                trigger: element,
                start: "top 80%",
                onEnter: () => element.classList.add(element.classList[1]),
                once: true
            });
        });
    });
</script>

<style>
    :root {
        --luxury-primary: #6C5CE7;
        --luxury-primary-dark: #5649C0;
        --luxury-primary-light: #8B5CF6;
        --luxury-secondary: #A569BD;
        --luxury-accent: #48DBFB;
        --luxury-gold: #D4AF37;
        --luxury-platinum: #E5E4E2;
        --luxury-dark: #1A1A2E;
        --luxury-darker: #0F0F1B;
        --luxury-light: #F8F9FA;
        --luxury-lighter: #F5F7FA;
        --luxury-gray: #6C757D;
        --luxury-success: #00B894;
        --luxury-info: #0984E3;
        --luxury-warning: #FDCB6E;
        --luxury-danger: #D63031;
        --luxury-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.08);
        --luxury-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        --luxury-shadow-md: 0 10px 15px rgba(0, 0, 0, 0.08);
        --luxury-shadow-lg: 0 20px 25px rgba(0, 0, 0, 0.1);
        --luxury-shadow-xl: 0 25px 50px rgba(0, 0, 0, 0.12);
        --luxury-glass: rgba(255, 255, 255, 0.1);
        --luxury-glass-border: 1px solid rgba(255, 255, 255, 0.15);
        --luxury-transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        --luxury-rounded-sm: 6px;
        --luxury-rounded: 12px;
        --luxury-rounded-md: 16px;
        --luxury-rounded-lg: 20px;
        --luxury-rounded-xl: 28px;
        --luxury-rounded-full: 9999px;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    body {
        font-family: 'Manrope', sans-serif;
        background-color: var(--luxury-lighter);
        color: var(--luxury-dark);
        line-height: 1.6;
        -webkit-font-smoothing: antialiased;
    }

    .luxury-dashboard {
        max-width: 1800px;
        margin: 0 auto;
        padding: 40px;
    }

    /* Premium Dashboard Header */
    .dashboard-header {
        border-radius: var(--luxury-rounded-xl);
        padding: 28px 40px;
        margin-bottom: 40px;
        box-shadow: var(--luxury-shadow-lg);
        color: white;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .dashboard-header::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
    }

    .dashboard-header::after {
        content: '';
        position: top;
        bottom: -100px;
        left: -100px;
        width: 300px;
        height: 300px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .header-left {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .dashboard-title {
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        color: black;
        margin: 0;
        font-size: 2.25rem;
        display: flex;
        align-items: center;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .breadcrumb {
        display: flex;
        align-items: center;
        font-size: 0.9375rem;
        color: rgba(255, 255, 255, 0.7);
    }

    .breadcrumb-item {
        color: rgba(255, 255, 255, 0.7);
        transition: var(--luxury-transition);
    }

    .breadcrumb-item:hover {
        color: white;
    }

    .breadcrumb-item.active {
        color: var(--luxury-accent);
        font-weight: 600;
    }

    .breadcrumb-divider {
        margin: 0 10px;
        color: rgba(255, 255, 255, 0.3);
    }

    .user-profile {
        display: flex;
        align-items: center;
        gap: 28px;
    }

    .notifications {
        position: relative;
        font-size: 1.375rem;
        color: rgba(255, 255, 255, 0.8);
        cursor: pointer;
        transition: var(--luxury-transition);
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: var(--luxury-rounded-full);
        background: rgba(255, 255, 255, 0.05);
    }

    .notifications:hover {
        color: white;
        background: rgba(255, 255, 255, 0.1);
        transform: translateY(-2px);
    }

    .notification-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        background-color: var(--luxury-danger);
        color: blue;
        border-radius: var(--luxury-rounded-full);
        width: 22px;
        height: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: 0 0 0 2px var(--luxury-dark);
    }

    .profile-dropdown {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 10px 20px;
        border-radius: var(--luxury-rounded-lg);
        cursor: pointer;
        transition: var(--luxury-transition);
        background: rgba(255, 255, 255, 0.08);
        border: var(--luxury-glass-border);
    }

    .profile-dropdown:hover {
        background: rgba(255, 255, 255, 0.15);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .profile-icon {
        width: 44px;
        height: 44px;
        border-radius: var(--luxury-rounded-full);
        background: linear-gradient(135deg, var(--luxury-primary) 0%, var(--luxury-secondary) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.3rem;
        box-shadow: 0 4px 12px rgba(108, 92, 231, 0.3);
    }

    .avatar-initials {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    }

    .profile-info {
        display: flex;
        flex-direction: column;
    }

    .user-name {
        font-weight: 700;
        font-size: 1rem;
        color: white;
    }

    .user-role {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
        font-weight: 500;
    }

    .dropdown-arrow {
        font-size: 0.875rem;
        color: rgba(255, 255, 255, 0.7);
        transition: var(--luxury-transition);
    }

    .profile-dropdown:hover .dropdown-arrow {
        transform: rotate(180deg);
        color: white;
    }

    /* Premium Metrics Section */
    .metrics-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 28px;
        margin-bottom: 40px;
    }

    .metric-card {
        background: white;
        border-radius: var(--luxury-rounded-xl);
        padding: 28px;
        box-shadow: var(--luxury-shadow-md);
        transition: var(--luxury-transition);
        display: flex;
        gap: 24px;
        align-items: center;
        position: relative;
        overflow: hidden;
        border: none;
    }

    .metric-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--luxury-shadow-xl);
    }

    .student-card {
        border-left: 5px solid var(--luxury-primary);
    }

    .classes-card {
        border-left: 5px solid var(--luxury-info);
    }

    .attendance-card {
        border-left: 5px solid var(--luxury-success);
    }

    .metric-decoration {
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, rgba(108, 92, 231, 0.08) 0%, rgba(108, 92, 231, 0) 100%);
        border-bottom-left-radius: 100%;
    }

    .metric-icon {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-bg {
        width: 64px;
        height: 64px;
        border-radius: var(--luxury-rounded-md);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        transition: var(--luxury-transition);
    }

    .metric-card:hover .icon-bg {
        transform: scale(1.05) rotate(5deg);
    }

    .student-card .icon-bg {
        background: linear-gradient(135deg, var(--luxury-primary) 0%, var(--luxury-secondary) 100%);
        box-shadow: 0 6px 20px rgba(108, 92, 231, 0.4);
    }

    .classes-card .icon-bg {
        background: linear-gradient(135deg, var(--luxury-info) 0%, #48DBFB 100%);
        box-shadow: 0 6px 20px rgba(9, 132, 227, 0.4);
    }

    .attendance-card .icon-bg {
        background: linear-gradient(135deg, var(--luxury-success) 0%, #55EFC4 100%);
        box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
    }

    .metric-icon i {
        font-size: 1.75rem;
    }

    .metric-content {
        flex: 1;
    }

    .metric-label {
        display: block;
        font-size: 0.9375rem;
        color: var(--luxury-gray);
        margin-bottom: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .metric-value {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--luxury-dark);
        margin-bottom: 10px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        line-height: 1;
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9375rem;
        color: var(--luxury-success);
        font-weight: 600;
    }

    .metric-trend i {
        font-size: 0.9rem;
    }

    /* Premium Content Area */
    .content-area {
        display: flex;
        flex-direction: column;
        gap: 40px;
    }

    /* Premium Sections */
    .actions-section, .calendar-section, .activity-section {
        background: white;
        border-radius: var(--luxury-rounded-xl);
        padding: 36px;
        box-shadow: var(--luxury-shadow-md);
        position: relative;
        overflow: hidden;
        border: none;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
    }

    .section-header h2 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--luxury-dark);
        margin: 0;
        display: flex;
        align-items: center;
        position: relative;
    }

    .section-header h2::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 0;
        width: 60px;
        height: 3px;
        background: linear-gradient(90deg, var(--luxury-primary), var(--luxury-secondary));
        border-radius: 3px;
    }

    .section-header h2 i {
        margin-right: 16px;
        font-size: 1.4em;
        color: var(--luxury-primary);
    }

    .section-controls {
        display: flex;
        gap: 16px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 12px 24px;
        border-radius: var(--luxury-rounded);
        font-size: 0.9375rem;
        font-weight: 600;
        line-height: 1.5;
        cursor: pointer;
        transition: var(--luxury-transition);
        border: 2px solid transparent;
        gap: 10px;
        font-family: 'Manrope', sans-serif;
        letter-spacing: 0.5px;
    }

    .btn-luxury {
        background: linear-gradient(135deg, var(--luxury-primary) 0%, var(--luxury-secondary) 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(108, 92, 231, 0.3);
    }

    .btn-luxury:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(108, 92, 231, 0.4);
    }

    .btn-outline-luxury {
        background: white;
        color: var(--luxury-primary);
        border: 2px solid var(--luxury-primary);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .btn-outline-luxury:hover {
        background: rgba(108, 92, 231, 0.05);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Premium Action Grid */
    .action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 24px;
    }

    .action-card {
        background: white;
        border-radius: var(--luxury-rounded-lg);
        padding: 28px;
        text-decoration: none;
        transition: var(--luxury-transition);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--luxury-shadow-lg);
        border-color: var(--luxury-primary-light);
    }

    .action-icon {
        width: 64px;
        height: 64px;
        border-radius: var(--luxury-rounded-md);
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 24px;
        font-size: 1.75rem;
        color: white;
        position: relative;
        z-index: 1;
        transition: var(--luxury-transition);
    }

    .action-card:hover .action-icon {
        transform: scale(1.1);
    }

    .action-icon::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: inherit;
        z-index: -1;
        opacity: 0.2;
        transform: scale(1.3);
    }

    .action-icon.notes {
        background: linear-gradient(135deg, var(--luxury-primary) 0%, var(--luxury-secondary) 100%);
        box-shadow: 0 6px 20px rgba(108, 92, 231, 0.4);
    }

    .action-icon.attendance {
        background: linear-gradient(135deg, var(--luxury-success) 0%, #55EFC4 100%);
        box-shadow: 0 6px 20px rgba(0, 184, 148, 0.4);
    }

    .action-icon.assignments {
        background: linear-gradient(135deg, var(--luxury-warning) 0%, #FFB142 100%);
        box-shadow: 0 6px 20px rgba(253, 203, 110, 0.4);
    }

    .action-icon.reports {
        background: linear-gradient(135deg, var(--luxury-info) 0%, #48DBFB 100%);
        box-shadow: 0 6px 20px rgba(9, 132, 227, 0.4);
    }

    .action-icon.messages {
        background: linear-gradient(135deg, #8B5CF6 0%, #D946EF 100%);
        box-shadow: 0 6px 20px rgba(139, 92, 246, 0.4);
    }

    .action-icon.resources {
        background: linear-gradient(135deg, #EC4899 0%, #F97316 100%);
        box-shadow: 0 6px 20px rgba(236, 72, 153, 0.4);
    }

    .action-card h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--luxury-dark);
        margin-bottom: 12px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .action-card p {
        font-size: 0.9375rem;
        color: var(--luxury-gray);
        margin: 0;
        line-height: 1.6;
    }

    .action-hover-effect {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(108, 92, 231, 0.05) 0%, rgba(108, 92, 231, 0) 100%);
        opacity: 0;
        transition: var(--luxury-transition);
    }

    .action-card:hover .action-hover-effect {
        opacity: 1;
    }

    /* Premium Bottom Section */
    .bottom-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 36px;
    }

    @media (max-width: 1200px) {
        .bottom-section {
            grid-template-columns: 1fr;
        }
    }

    /* Premium Calendar Section */
    #calendar {
        margin-top: 20px;
    }

    .fc {
        font-family: 'Manrope', sans-serif;
    }

    .fc .fc-toolbar-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--luxury-dark);
        font-family: 'Playfair Display', serif;
    }

    .fc .fc-button {
        background-color: white;
        border: 1px solid rgba(0, 0, 0, 0.1);
        color: var(--luxury-dark);
        font-size: 0.9375rem;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: var(--luxury-rounded);
        transition: var(--luxury-transition);
        text-transform: capitalize;
    }

    .fc .fc-button:hover {
        background-color: var(--luxury-light);
        border-color: rgba(0, 0, 0, 0.15);
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active {
        background-color: var(--luxury-primary);
        border-color: var(--luxury-primary);
        color: white;
    }

    .fc .fc-daygrid-day-number {
        color: var(--luxury-dark);
        font-weight: 500;
        padding: 8px;
    }

    .fc .fc-daygrid-day.fc-day-today {
        background-color: rgba(108, 92, 231, 0.1);
    }

    .fc .fc-daygrid-event {
        border-radius: var(--luxury-rounded-sm);
        padding: 4px 8px;
        font-size: 0.8125rem;
        font-weight: 500;
    }

    .fc .fc-daygrid-event-dot {
        display: none;
    }

    /* Premium Activity Section */
    .view-all {
        font-size: 0.875rem;
        color: var(--luxury-primary);
        text-decoration: none;
        font-weight: 500;
        transition: var(--luxury-transition);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .view-all:hover {
        color: var(--luxury-primary-dark);
        text-decoration: none;
    }

    .view-all i {
        font-size: 0.8rem;
        transition: var(--luxury-transition);
    }

    .view-all:hover i {
        transform: translateX(3px);
    }

    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .activity-item {
        display: flex;
        gap: 16px;
        padding: 20px;
        border-radius: var(--luxury-rounded-lg);
        background: var(--luxury-light);
        transition: var(--luxury-transition);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
        overflow: hidden;
    }

    .activity-item:hover {
        background: white;
        transform: translateY(-3px);
        box-shadow: var(--luxury-shadow-sm);
        border-color: rgba(0, 0, 0, 0.1);
    }

    .activity-decoration {
        position: absolute;
        top: 0;
        right: 0;
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, rgba(108, 92, 231, 0.05) 0%, rgba(108, 92, 231, 0) 100%);
        border-bottom-left-radius: 100%;
    }

    .activity-icon {
        width: 48px;
        height: 48px;
        border-radius: var(--luxury-rounded-full);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: white;
        font-size: 1.25rem;
    }

    .activity-icon.success {
        background: linear-gradient(135deg, var(--luxury-success) 0%, #55EFC4 100%);
    }

    .activity-icon.primary {
        background: linear-gradient(135deg, var(--luxury-primary) 0%, var(--luxury-secondary) 100%);
    }

    .activity-icon.info {
        background: linear-gradient(135deg, var(--luxury-info) 0%, #48DBFB 100%);
    }

    .activity-icon.warning {
        background: linear-gradient(135deg, var(--luxury-warning) 0%, #FFB142 100%);
    }

    .activity-content {
        flex: 1;
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .activity-header h3 {
        font-size: 0.9375rem;
        font-weight: 600;
        color: var(--dark);
    }

    .activity-time {
        font-size: 0.8125rem;
        color: var(--secondary);
    }

    .activity-content p {
        font-size: 0.875rem;
        color: var(--secondary);
        margin: 0;
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
        transition: all 0.2s;
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
    }

    .btn-secondary {
        background-color: white;
        color: var(--dark);
        border-color: var(--gray-300);
    }

    .btn-secondary:hover {
        background-color: var(--gray-100);
    }

    .btn-outline-secondary {
        background-color: white;
        color: var(--secondary);
        border-color: var(--gray-300);
    }

    .btn-outline-secondary:hover {
        background-color: var(--gray-100);
        color: var(--dark);
    }

    /* Event Modal */
    .event-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        border-radius: var(--rounded-xl);
        padding: 24px;
        width: 90%;
        max-width: 500px;
        z-index: 1001;
        box-shadow: var(--shadow-xl);
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
        color: var(--dark);
    }

    .close-modal {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--secondary);
        cursor: pointer;
        transition: color 0.2s;
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
