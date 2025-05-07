<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUXE Calendar | Professional Scheduling</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ===== Global Styles ===== */
        :root {
            /* Color Scheme */
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --primary-light: #e0e7ff;
            --secondary: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --light: #f8fafc;
            --dark: #0f172a;
            --dark-light: #1e293b;
            --gray: #64748b;
            --gray-light: #f1f5f9;
            
            /* Design Tokens */
            --border-radius: 12px;
            --box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.1);
            --box-shadow-hover: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            
            /* Glass Morphism */
            --glass-bg: rgba(255, 255, 255, 0.85);
            --glass-border: rgba(255, 255, 255, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== Layout Structure ===== */
        .dashboard {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* ===== Sidebar Navigation ===== */
        .sidebar {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-right: 1px solid var(--glass-border);
            padding: 2rem 0;
            position: fixed;
            top: 0;
            left: 0;
            width: 280px;
            height: 100vh;
            z-index: 100;
            display: flex;
            flex-direction: column;
        }

        .branding {
            display: flex;
            align-items: center;
            padding: 0 2rem 2rem;
            margin-bottom: 1rem;
        }

        .brand-icon {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }

        .brand-name {
            font-weight: 700;
            font-size: 1.3rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-menu {
            list-style: none;
            padding: 0 1.5rem;
            flex-grow: 1;
        }

        .nav-item {
            margin-bottom: 0.5rem;
            border-radius: var(--border-radius);
            transition: var(--transition);
        }

        .nav-item:hover {
            background: rgba(99, 102, 241, 0.1);
        }

        .nav-item.active {
            background: rgba(99, 102, 241, 0.1);
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--dark-light);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-link i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
            color: var(--gray);
        }

        .nav-item.active .nav-link {
            color: var(--primary-dark);
            font-weight: 600;
        }

        .nav-item.active .nav-link i {
            color: var(--primary-dark);
        }

        /* ===== Main Content Area ===== */
        .main-content {
            margin-left: 280px;
            padding: 2.5rem;
        }

        /* Header Section */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark);
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-radius: 2px;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        /* Button Styles */
        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            box-shadow: var(--box-shadow);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px rgba(79, 70, 229, 0.2);
        }

        .btn-secondary {
            background: white;
            color: var(--dark);
            border: 1px solid var(--gray-light);
        }

        .btn-secondary:hover {
            background: var(--gray-light);
            transform: translateY(-2px);
        }

        /* ===== Calendar Component ===== */
        .calendar-container {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            border: 1px solid var(--glass-border);
            margin-bottom: 2rem;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .month-year {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--dark);
        }

        .calendar-nav {
            display: flex;
            gap: 0.5rem;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 0.5rem;
        }

        .calendar-day-header {
            text-align: center;
            font-weight: 600;
            color: var(--gray);
            padding: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
        }

        .calendar-day {
            background: white;
            border-radius: 8px;
            padding: 0.75rem;
            min-height: 100px;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .calendar-day:hover {
            transform: translateY(-3px);
            box-shadow: var(--box-shadow-hover);
        }

        .day-number {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        /* Event Indicators */
        .event {
            background: var(--primary-light);
            color: var(--primary-dark);
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            margin-bottom: 0.25rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event.important {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
        }

        .event.meeting {
            background: rgba(16, 185, 129, 0.1);
            color: var(--secondary);
        }

        .event.personal {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .current-day {
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid var(--primary);
        }

        .current-day .day-number {
            color: var(--primary-dark);
            font-weight: 700;
        }

        /* ===== Events Section ===== */
        .events-section {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2rem;
            box-shadow: var(--box-shadow);
            border: 1px solid var(--glass-border);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 1.5rem;
        }

        .events-list {
            display: grid;
            gap: 1rem;
        }

        .event-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 1rem;
            display: flex;
            align-items: center;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .event-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--box-shadow-hover);
        }

        .event-time {
            background: var(--primary-light);
            color: var(--primary-dark);
            width: 50px;
            height: 50px;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-weight: 600;
            flex-shrink: 0;
        }

        .event-time .hour {
            font-size: 1.1rem;
        }

        .event-time .period {
            font-size: 0.7rem;
            opacity: 0.8;
        }

        .event-details {
            flex-grow: 1;
            min-width: 0;
        }

        .event-title {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: var(--dark);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .event-location {
            font-size: 0.85rem;
            color: var(--gray);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .event-location i {
            font-size: 0.8rem;
        }

        .event-actions {
            display: flex;
            gap: 0.5rem;
            flex-shrink: 0;
        }

        .event-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--gray-light);
            color: var(--gray);
            border: none;
            cursor: pointer;
            transition: var(--transition);
        }

        .event-btn:hover {
            background: var(--primary-light);
            color: var(--primary-dark);
        }

        /* ===== Responsive Design ===== */
        @media (max-width: 1200px) {
            .dashboard {
                grid-template-columns: 1fr;
            }

            .sidebar {
                left: -100%;
                transition: all 0.3s ease;
            }

            .sidebar.active {
                left: 0;
            }

            .main-content {
                margin-left: 0;
                padding: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .calendar-grid {
                grid-template-columns: repeat(1, 1fr);
            }

            .calendar-day {
                min-height: auto;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .header-actions {
                width: 100%;
                justify-content: space-between;
            }
        }

        /* ===== Animations ===== */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .animate-slide-up {
            animation: slideUp 0.4s ease-out;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="branding">
                <div class="brand-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <span class="brand-name">LUXE</span>
            </div>
            
            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="#" class="nav-link">
                        <i class="fas fa-calendar-days"></i>
                        <span>Calendar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-tasks"></i>
                        <span>Tasks</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        <span>Events</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Page Header -->
            <header class="page-header">
                <h1 class="page-title">Calendar</h1>
                <div class="header-actions">
                    <button class="btn btn-secondary">
                        <i class="fas fa-print"></i>
                        <span>Print</span>
                    </button>
                    <button class="btn btn-primary" id="add-event-btn">
                        <i class="fas fa-plus"></i>
                        <span>Add Event</span>
                    </button>
                </div>
            </header>

            <!-- Calendar Section -->
            <section class="calendar-container animate-fade-in">
                <div class="calendar-header">
                    <h2 class="month-year">June 2023</h2>
                    <div class="calendar-nav">
                        <button class="btn btn-secondary">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-secondary">
                            Today
                        </button>
                        <button class="btn btn-secondary">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
                
                <div class="calendar-grid">
                    <!-- Day Headers -->
                    <div class="calendar-day-header">Sun</div>
                    <div class="calendar-day-header">Mon</div>
                    <div class="calendar-day-header">Tue</div>
                    <div class="calendar-day-header">Wed</div>
                    <div class="calendar-day-header">Thu</div>
                    <div class="calendar-day-header">Fri</div>
                    <div class="calendar-day-header">Sat</div>

                    <!-- Calendar Days -->
                    <div class="calendar-day">
                        <div class="day-number">28</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">29</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">30</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">31</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">1</div>
                        <div class="event meeting">Team Meeting</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">2</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">3</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">4</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">5</div>
                        <div class="event important">Project Deadline</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">6</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">7</div>
                        <div class="event personal">Doctor's Appointment</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">8</div>
                    </div>
                    <div class="calendar-day current-day">
                        <div class="day-number">11</div>
                        <div class="event meeting">Client Call</div>
                        <div class="event personal">Birthday Party</div>
                    </div>
                    <!-- Additional days... -->
                </div>
            </section>

            <!-- Upcoming Events Section -->
            <section class="events-section animate-slide-up">
                <h2 class="section-title">Upcoming Events</h2>
                <div class="events-list">
                    <article class="event-card">
                        <div class="event-time">
                            <span class="hour">10:00</span>
                            <span class="period">AM</span>
                        </div>
                        <div class="event-details">
                            <h3 class="event-title">Team Standup Meeting</h3>
                            <p class="event-location">
                                <i class="fas fa-video"></i>
                                Zoom Call
                            </p>
                        </div>
                        <div class="event-actions">
                            <button class="event-btn">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="event-btn">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </article>
                    
                    <article class="event-card">
                        <div class="event-time">
                            <span class="hour">2:30</span>
                            <span class="period">PM</span>
                        </div>
                        <div class="event-details">
                            <h3 class="event-title">Client Presentation</h3>
                            <p class="event-location">
                                <i class="fas fa-building"></i>
                                Conference Room A
                            </p>
                        </div>
                        <div class="event-actions">
                            <button class="event-btn">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="event-btn">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </article>
                </div>
            </section>
        </main>
    </div>

    <script>
        // Initialize calendar day animations
        document.addEventListener('DOMContentLoaded', () => {
            const calendarDays = document.querySelectorAll('.calendar-day');
            calendarDays.forEach((day, index) => {
                day.style.animationDelay = `${index * 0.05}s`;
                day.classList.add('animate-fade-in');
            });
        });
    </script>
</body>
</html>