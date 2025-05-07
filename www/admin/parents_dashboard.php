<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['id'])) {
    die("Unauthorized access. Please log in.");
}

$id = $_SESSION['id'];

// Connect to SQLite database
$db_file = 'school_fees_management.db';
if (!file_exists($db_file)) {
    die("Database file not found!");
}
$db = new SQLite3($db_file);

// Fetch total fees paid
$stmt = $db->prepare("SELECT SUM(amount_paid) AS total_paid FROM payments WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
$fees_paid = $result['total_paid'] ?? 0;

// Fetch total balance
$stmt = $db->prepare("SELECT SUM(balance) AS total_balance FROM payments WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);
$result_balance = $stmt->execute()->fetchArray(SQLITE3_ASSOC);
$balance = $result_balance['total_balance'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f8f9fa;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-left i {
            font-size: 24px;
            background: white;
            color: #007bff;
            padding: 10px;
            border-radius: 50%;
        }

        .header h2 {
            font-size: 22px;
            margin: 0;
            font-weight: bold;
        }

        .header-right {
            display: flex;
            gap: 15px;
        }

        .header-right a {
            color: white;
            font-size: 22px;
            text-decoration: none;
            transition: transform 0.3s;
        }

        .header-right a:hover {
            transform: scale(1.1);
        }

        .stats-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 15px;
            transition: 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.02);
            box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.15);
        }

        .icon {
            font-size: 28px;
            padding: 15px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fees-icon {
            background: #28a745;
        }

        .pickup-icon {
            background: #ffc107;
            color: #333;
        }

        .manage-icon {
            background: #17a2b8;
        }

        .info {
            display: flex;
            flex-direction: column;
        }

        .info h3 {
            font-size: 16px;
            font-weight: 600;
            color: #666;
            margin-bottom: 5px;
        }

        .info p {
            font-size: 22px;
            font-weight: bold;
            margin: 0;
        }

        .fees-info p {
            color: #28a745;
        }

        .balance-info {
            font-size: 16px;
            font-weight: 600;
            color: #dc3545;
            margin-top: 5px;
        }

        .navbar {
            position: fixed;
            bottom: 0;
            width: 100%;
            max-width: 900px;
            background: white;
            display: flex;
            justify-content: space-around;
            padding: 12px 0;
            box-shadow: 0 -4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px 15px 0 0;
        }

        .nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .nav-item i {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .nav-item:hover {
            transform: scale(1.1);
            color: #ffdd57;
        }
    </style>
</head>
<body>

    <div class="container">

        <div class="header">
            <div class="header-left">
                <i class="fas fa-user-tie"></i>
                <h2>Parent Dashboard</h2>
            </div>
            <div class="header-right">
                <a href="messages.html"><i class="fas fa-envelope"></i></a>
                <a href="#"><i class="fas fa-bell"></i></a>
            </div>
        </div>

        <div class="stats-container">
            <div class="card">
                <div class="icon fees-icon">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
                <div class="info">
                    <h3>Fees Paid</h3>
                    <p>GH₵ <?php echo number_format($fees_paid, 2); ?></p>
                    <div class="balance-info">
                        Outstanding Balance: GH₵ <?php echo number_format($balance, 2); ?>
                    </div>
                </div>
            </div>

            <!-- Clickable Secure Pickup Card -->
            <a href="Secure_pickup.php" style="text-decoration: none; color: inherit;">
                <div class="card">
                    <div class="icon pickup-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="info">
                        <h3>Secure Pickup</h3>
                        <p>Verified Guardian</p>
                    </div>
                </div>
            </a>

            <div class="card">
                <div class="icon manage-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <div class="info">
                    <h3>Manage Your Ward</h3>
                    <p>Monitor fees, assignments, and performance with ease.</p>
                </div>
            </div>
        </div>

        <div class="navbar">
            <a href="parent_dashboard.php" class="nav-item"><i class="fas fa-home"></i><span>Home</span></a>
            <a href="attendance.php" class="nav-item"><i class="fas fa-user-check"></i><span>Attendance</span></a>
            <a href="assignments.php" class="nav-item"><i class="fas fa-book-open"></i><span>Assignments</span></a>
            <a href="performance.php" class="nav-item"><i class="fas fa-chart-line"></i><span>Performance</span></a>
        </div>

    </div>

</body>
</html>