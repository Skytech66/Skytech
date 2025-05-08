<?php require_once "header.php"; ?>

<!-- Include Font Awesome for Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

<div class="container mt-4">
    <div class="panel panel-default">
        <div class="panel-heading bg-light rounded d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-dark">
                <i class="fas fa-chalkboard-teacher"></i> Teachers Dashboard
            </h4>
            <div class="user-icon text-center">
                <i class="fas fa-user-circle" style="font-size: 60px; color: #007bff;"></i>
                <p class="text-dark mt-2 mb-0">Facilitator</p>
            </div>
        </div>
        <div class="panel-body bg-white rounded shadow-sm">
            <div class="row mb-5">
                <!-- Total Students Card -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="dash-box bg-gradient-primary rounded shadow-lg p-4 d-flex flex-column align-items-center">
                        <i class="fas fa-user-graduate text-white" style="font-size: 40px;"></i>
                        <h5 class="text-white mt-2">Total Students</h5>
                        <?php
                            $stmt = $conn->query("SELECT COUNT(name) as 'tstudents' FROM student");
                            $row = $stmt->fetchArray(SQLITE3_ASSOC);
                            $totalStudents = $row['tstudents'] ?? 0;
                        ?>
                        <h3 class="text-white"><?php echo $totalStudents; ?></h3>
                    </div>
                </div>

                <!-- Student Distribution Donut Chart -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="dash-box bg-gradient-success rounded shadow-lg p-4">
                        <h5 class="text-dark text-center">Student Distribution</h5>
                        <canvas id="studentDonutChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>
            </div>

            <!-- Links to Lesson Notes and Attendance Register -->
            <div class="row mb-4">
                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="dash-box bg-gradient-info rounded shadow-lg p-4 d-flex flex-column align-items-center">
                        <i class="fas fa-book text-white" style="font-size: 40px;"></i>
                        <h5 class="text-white mt-2">Go to Lesson Notes</h5>
                        <a href="lesson_notes.php" class="btn btn-light text-dark mt-2">Manage Lesson Notes</a>
                    </div>
                </div>

                <div class="col-md-6 mb-4 mb-md-0">
                    <div class="dash-box bg-gradient-warning rounded shadow-lg p-4 d-flex flex-column align-items-center">
                        <i class="fas fa-calendar-check text-white" style="font-size: 40px;"></i>
                        <h5 class="text-white mt-2">Add Class Attendance Register</h5>
                        <a href="login.php" class="btn btn-light text-dark mt-2">Add Attendance</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="row mt-5">
            <div class="col-12">
                <h4 class="mb-4 text-dark text-center">Calendar of Events</h4>
                <hr>
                <div class="response"></div>
                <div id="calendar"></div>
            </div>
        </div>
    </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- Add these scripts for better interactivity -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function () {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // Student Distribution Donut Chart Data
        const studentCtx = document.getElementById('studentDonutChart').getContext('2d');
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
                    label: 'Number of Students',
                    data: [<?php echo implode(",", $data); ?>],
                    backgroundColor: [
                        '#007bff', // Blue
                        '#20c997', // Soft Teal
                        '#ffc107', // Orange
                        '#0056b3', // Dark Blue
                        '#dc3545', // Red
                        '#6f42c1'  // Purple
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    /* Custom Styles */
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0056b3);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #20c997, #1c7430);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107, #e0a800);
    }

    .dash-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        color: white;
        text-align: center;
    }

    .dash-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-icon {
        margin-left: auto;
    }

    .user-icon p {
        font-size: 14px;
        color: #333;
        margin-top: 5px;
    }

    #calendar {
        background: #fff;
        border-radius: 5px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    @media (max-width: 768px) {
        .dash-box {
            margin-bottom: 20px;
        }
    }
</style>