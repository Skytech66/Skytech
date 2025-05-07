<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-blue: #2563eb;
            --secondary-blue: #3b82f6;
            --background-gray: #f8fafc;
            --card-bg: #ffffff;
            --text-dark: #0f172a;
            --text-light: #6b7280;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        body {
            background: var(--background-gray);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            max-width: 600px;
            padding: 10px;
        }

        .header .icon {
            background: var(--primary-blue);
            color: white;
            font-size: 24px;
            padding: 15px;
            border-radius: 50%;
        }

        .notification {
            font-size: 20px;
            color: var(--primary-blue);
            cursor: pointer;
        }

        .card {
            background: var(--card-bg);
            width: 100%;
            max-width: 600px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 15px;
            border-left: 6px solid var(--primary-blue);
        }

        .card h3 {
            color: var(--text-dark);
            font-weight: bold;
        }

        .amount {
            font-size: 32px;
            font-weight: bold;
            color: var(--text-dark);
        }

        .info-banner {
            background: var(--primary-blue);
            color: white;
            padding: 15px;
            border-radius: 10px;
            width: 100%;
            max-width: 600px;
            margin-top: 15px;
            display: flex;
            align-items: center;
            font-size: 14px;
            gap: 10px;
        }

        .info-banner i {
            font-size: 20px;
        }

        .chart-container {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 15px;
            max-width: 600px;
            width: 100%;
            height: 250px;
            text-align: center;
        }

        canvas {
            max-height: 180px;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 15px 0;
            box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 14px;
            color: var(--text-light);
            cursor: pointer;
            position: relative;
        }

        .nav-item i {
            font-size: 18px;
            margin-bottom: 3px;
        }

        .nav-item.active i {
            color: var(--primary-blue);
        }

        .chat-icon {
            position: fixed;
            bottom: 80px;
            right: 20px;
            background: var(--primary-blue);
            color: white;
            padding: 15px;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            transition: background 0.3s ease;
        }

        .chat-icon:hover {
            background: var(--secondary-blue);
        }

        .fees-popup {
            position: fixed;
            bottom: 50%;
            left: 50%;
            transform: translate(-50%, 50%);
            background: var(--card-bg);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            width: 320px;
            text-align: center;
            display: none;
            z-index: 1000;
        }

        .fees-popup h3 {
            color: var(--text-dark);
            margin-bottom: 10px;
        }

        .fees-popup p {
            font-size: 16px;
            color: var(--text-light);
            margin: 5px 0;
        }

        .fees-popup .amount {
            font-size: 24px;
            font-weight: bold;
            color: var(--primary-blue);
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="icon"><i class="fas fa-user-friends"></i></div>
        <h2>Hi, Parent</h2>
        <i class="fas fa-bell notification"></i>
    </div>

    <div class="card">
        <h3>Fees Paid</h3>
        <p class="amount">GH₵ 1,200.00</p>
    </div>

    <div class="info-banner">
        <i class="fas fa-calendar-alt"></i>
        <div>
            <p><b>Stay Involved!</b></p>
            <p>Check your child's academic progress, attendance, and upcoming school events.</p>
        </div>
    </div>

    <div class="chart-container">
        <h3>Student Performance</h3>
        <canvas id="performanceChart"></canvas>
    </div>

    <!-- Fees Summary Popup -->
    <div class="fees-popup" id="feesPopup">
        <h3>Fees Summary</h3>
        <p>Fees Paid: <span class="amount">GH₵ 1,200.00</span></p>
        <p>Last Term Arrears: <span class="amount">GH₵ 200.00</span></p>
        <p>Balance: <span class="amount">GH₵ 300.00</span></p>
    </div>

    <!-- Bottom Navigation -->
    <div class="bottom-nav">
        <a href="#" class="nav-item active">
            <i class="fas fa-home"></i> Dashboard
        </a>
        <a href="#" class="nav-item" id="feesButton">
            <i class="fas fa-piggy-bank"></i> Fees
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-chart-line"></i> Progress
        </a>
        <a href="#" class="nav-item">
            <i class="fas fa-user-cog"></i> Profile
        </a>
    </div>

    <!-- Chat Icon -->
    <div class="chat-icon" id="chatIcon">
        <i class="fas fa-comment-alt"></i>
    </div>

    <script>
        document.getElementById('chatIcon').addEventListener('click', function() {
            window.location.href = 'messages.html';
        });

        document.getElementById('feesButton').addEventListener('click', function (event) {
            event.preventDefault();
            const popup = document.getElementById('feesPopup');
            popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', function (event) {
            const popup = document.getElementById('feesPopup');
            if (!popup.contains(event.target) && event.target.id !== 'feesButton') {
                popup.style.display = 'none';
            }
        });
    </script>

</body>
</html>