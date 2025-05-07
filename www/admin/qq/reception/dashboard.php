<?php require_once "header.php"; ?>

<div class="container mt-5">
    <div class="panel panel-default rounded shadow-lg">
        <div class="panel-heading text-center py-4 bg-gradient-dark rounded-top">
            <h4 class="text-white font-weight-bold">Secretary Dashboard</h4>
        </div>
        <div class="panel-body">
            <!-- Line Graph Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="text-dark text-center">Student Growth Over Time</h4>
                    <canvas id="lineChart" class="rounded shadow-sm" width="300" height="150"></canvas>
                </div>
            </div>

            <!-- Donut Chart Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="text-dark text-center">Subject Distribution</h4>
                    <canvas id="donutChart" class="rounded shadow-sm" width="300" height="150"></canvas>
                </div>
            </div>

            <!-- Navigation Button to Student Fees Management -->
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <a href="student_fees.php" class="btn btn-lg btn-success shadow-sm">
                        <i class="fas fa-cash-register"></i> Manage Student Fees
                    </a>
                </div>
            </div>

            <!-- Calendar of Events -->
            <div class="row">
                <div class="col-12">
                    <h4 class="text-dark text-center">Calendar of Events</h4>
                    <hr class="bg-dark">
                    <div id="calendar" class="rounded shadow-sm"></div>
                </div>
            </div>
            
            <div id="pageNavPosition" class="pager-nav mt-4"></div>
        </div>
    </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- Custom CSS for Professional & Beautiful Look -->
<style>
    /* Color Scheme */
    .bg-gradient-dark {
        background: linear-gradient(to right, #2c3e50, #34495e);
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    .bg-light-gray {
        background-color: #f7f7f7;
    }

    .text-dark {
        color: #333333 !important;
    }

    .text-primary {
        color: #3498db !important;
    }

    .text-warning {
        color: #f39c12 !important;
    }

    .text-success {
        color: #2ecc71 !important;
    }

    .border-0 {
        border: none;
    }

    .panel {
        background: #ffffff;
        border-radius: 10px;
    }

    .panel-heading {
        background: #2c3e50;
        border-radius: 10px 10px 0 0;
        padding: 20px;
    }

    .font-weight-bold {
        font-weight: 600;
    }

    .display-4 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    h4 {
        font-weight: 500;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .panel-body {
        padding: 30px;
    }

    .row {
        margin-bottom: 20px;
    }

    .btn {
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #27ae60;
    }

    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Fetching dynamic data for the line chart
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: <?php
                // Fetch student enrollment data
                $enrollmentData = [];
                $enrollmentLabels = [];
                $stmt = $conn->query("SELECT month, COUNT(*) as count FROM student_enrollment GROUP BY month ORDER BY month");
                while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
                    $enrollmentData[] = $row['count'];
                    $enrollmentLabels[] = $row['month'];
                }
                echo json_encode($enrollmentLabels);
            ?>,
            datasets: [{
                label: 'Students Enrolled',
                data: <?php echo json_encode($enrollmentData); ?>,
                backgroundColor: 'rgba(52, 152, 219, 0.2)',
                borderColor: 'rgba(52, 152, 219, 1)',
                borderWidth: 2,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            animation: false, // Disable animation
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Fetching dynamic data for the donut chart
    const donutCtx = document.getElementById('donutChart').getContext('2d');
    const donutChart = new Chart(donutCtx, {
        type: 'doughnut',
        data: {
            labels: <?php
                // Fetch subject distribution data
                $subjectData = [];
                $subjectLabels = [];
                $stmt = $conn->query("SELECT subject_name, COUNT(*) as count FROM subject_distribution GROUP BY subject_name");
                while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
                    $subjectData[] = $row['count'];
                    $subjectLabels[] = $row['subject_name'];
                }
                echo json_encode($subjectLabels);
            ?>,
            datasets: [{
                label: 'Subject Distribution',
                data: <?php echo json_encode($subjectData); ?>,
                backgroundColor: [
                    'rgba(46, 204, 113, 0.6)',
                    'rgba(241, 196, 15, 0.6)',
                    'rgba(231, 76, 60, 0.6)',
                    'rgba(155, 89, 182, 0.6)'
                ],
                borderColor: [
                    'rgba(46, 204, 113, 1)',
                    'rgba(241, 196, 15, 1)',
                    'rgba(231, 76, 60, 1)',
                    'rgba(155, 89, 182, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            animation: false, // Disable animation
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
</script>