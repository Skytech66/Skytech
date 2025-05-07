<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expenses Management | Financial Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --success-color: #4bb543;
            --danger-color: #ff3333;
            --warning-color: #ffcc00;
            --info-color: #17a2b8;
            --border-radius: 10px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f7ff;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-title i {
            font-size: 28px;
        }

        .header-title h1 {
            font-weight: 600;
            font-size: 24px;
            margin: 0;
        }

        .header-controls {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: white;
            color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #f0f0f0;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-outline {
            background-color: transparent;
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-outline:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .metrics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .metric-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--box-shadow);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }

        .metric-card.clickable {
            cursor: pointer;
        }

        .metric-card.clickable:hover {
            background-color: #f8f9ff;
        }

        .metric-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 20px;
            color: white;
        }

        .metric-value {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark-color);
        }

        .metric-title {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }

        .metric-trend {
            display: flex;
            align-items: center;
            margin-top: 10px;
            font-size: 13px;
            font-weight: 500;
        }

        .trend-up {
            color: var(--success-color);
        }

        .trend-down {
            color: var(--danger-color);
        }

        .trend-neutral {
            color: var(--warning-color);
        }

        .charts-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .chart-card {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--box-shadow);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .chart-actions {
            display: flex;
            gap: 10px;
        }

        .chart-btn {
            background-color: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            font-size: 14px;
            transition: var(--transition);
            padding: 5px 10px;
            border-radius: 6px;
        }

        .chart-btn:hover {
            background-color: #f8f9fa;
            color: var(--primary-color);
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .charts-container {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .header-controls {
                width: 100%;
                flex-wrap: wrap;
            }
            
            .btn {
                flex-grow: 1;
                justify-content: center;
            }
            
            .metrics-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 576px) {
            .metrics-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 15px;
            }
            
            .chart-card {
                padding: 15px;
            }
        }

        /* Loading spinner */
        .spinner {
            display: none;
            width: 40px;
            height: 40px;
            margin: 20px auto;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top-color: var(--primary-color);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: var(--success-color);
            color: white;
            padding: 15px 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            display: none;
            z-index: 1100;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <div class="header-title">
                <i class="fas fa-chart-pie"></i>
                <h1>Expenses Management Dashboard</h1>
            </div>
            <div class="header-controls">
                <button class="btn btn-outline">
                    <i class="fas fa-download"></i> Export Report
                </button>
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Transaction
                </button>
            </div>
        </header>

        <div class="metrics-grid">
            <div class="metric-card">
                <div class="metric-icon" style="background-color: var(--primary-color);">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="metric-value">₵<span id="total-expenses-card">0.00</span></div>
                <div class="metric-title">Total Expenses</div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i> 12% from last month
                </div>
            </div>
            
            <div class="metric-card clickable" onclick="window.location.href='ex.php';">
                <div class="metric-icon" style="background-color: var(--success-color);">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="metric-value">₵<span id="monthly-expenses">0.00</span></div>
                <div class="metric-title">Monthly Expenses</div>
                <div class="metric-trend trend-down">
                    <i class="fas fa-arrow-down"></i> 5% from last month
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon" style="background-color: var(--info-color);">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="metric-value">₵<span id="total-income">0.00</span></div>
                <div class="metric-title">Total Income</div>
                <div class="metric-trend trend-up">
                    <i class="fas fa-arrow-up"></i> 8% from last month
                </div>
            </div>
            
            <div class="metric-card">
                <div class="metric-icon" style="background-color: var(--warning-color);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="metric-value">₵<span id="budget">0.00</span></div>
                <div class="metric-title">Remaining Budget</div>
                <div class="metric-trend trend-neutral">
                    <i class="fas fa-equals"></i> On track
                </div>
            </div>
        </div>

        <div class="charts-container">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Expense Distribution</h3>
                    <div class="chart-actions">
                        <button class="chart-btn"><i class="fas fa-calendar"></i> This Month</button>
                        <button class="chart-btn"><i class="fas fa-filter"></i> Filter</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="donutChart"></canvas>
                </div>
            </div>
            
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Income vs Expenses</h3>
                    <div class="chart-actions">
                        <button class="chart-btn"><i class="fas fa-calendar"></i> Last 6 Months</button>
                        <button class="chart-btn"><i class="fas fa-download"></i> Export</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

        <div class="spinner" id="loadingSpinner"></div>
        <div class="toast" id="toastNotification"></div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        // DOM Elements
        const loadingSpinner = document.getElementById("loadingSpinner");
        const toastNotification = document.getElementById("toastNotification");

        // Initialize Charts
        function initializeCharts() {
            // Donut Chart
            const donutCtx = document.getElementById('donutChart').getContext('2d');
            const donutChart = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Supplies', 'Maintenance', 'Salaries', 'Events', 'Utilities'],
                    datasets: [{
                        data: [35, 25, 20, 15, 5],
                        backgroundColor: [
                            '#4361ee',
                            '#3f37c9',
                            '#4895ef',
                            '#4cc9f0',
                            '#f72585'
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Poppins',
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${label}: ₵${value} (${percentage}%)`;
                                }
                            }
                        },
                        datalabels: {
                            formatter: (value) => {
                                return `₵${value}`;
                            },
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    },
                    cutout: '70%',
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                },
                plugins: [ChartDataLabels]
            });

            // Line Chart
            const lineCtx = document.getElementById('lineChart').getContext('2d');
            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [
                        {
                            label: 'Income',
                            data: [12000, 19000, 15000, 18000, 21000, 19000, 23000],
                            borderColor: '#4bb543',
                            backgroundColor: 'rgba(75, 181, 67, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#4bb543',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Expenses',
                            data: [8000, 12000, 10000, 11000, 15000, 13000, 14000],
                            borderColor: '#ff3333',
                            backgroundColor: 'rgba(255, 51, 51, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#ff3333',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    family: 'Poppins'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.dataset.label || '';
                                    const value = context.raw || 0;
                                    return `${label}: ₵${value.toLocaleString()}`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₵' + value.toLocaleString();
                                }
                            },
                            grid: {
                                drawBorder: false,
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Fetch data from server
        async function fetchFinancialData() {
            showLoading(true);
            
            try {
                // Fetch total income
                const incomeResponse = await fetch('get_total_income.php');
                const incomeData = await incomeResponse.json();
                document.getElementById('total-income').textContent = incomeData.total_income.toFixed(2);
                
                // Fetch total expenses
                const expensesResponse = await fetch('get_total_expenses.php');
                const expensesData = await expensesResponse.json();
                document.getElementById('total-expenses-card').textContent = expensesData.total_expenses.toFixed(2);
                
                // You would similarly fetch monthly expenses and budget data here
                
                showToast("Data loaded successfully", "success");
            } catch (error) {
                console.error("Error fetching financial data:", error);
                showToast("Failed to load data", "error");
            } finally {
                showLoading(false);
            }
        }

        // Helper functions
        function showLoading(show) {
            loadingSpinner.style.display = show ? "block" : "none";
        }

        function showToast(message, type = "success") {
            toastNotification.textContent = message;
            toastNotification.style.backgroundColor = type === "success" ? "var(--success-color)" : "var(--danger-color)";
            toastNotification.style.display = "block";
            
            setTimeout(() => {
                toastNotification.style.display = "none";
            }, 3000);
        }

        // Initialize the page
        document.addEventListener("DOMContentLoaded", () => {
            initializeCharts();
            fetchFinancialData();
        });
    </script>
</body>
</html>