<?php
session_start(); // Start the session

// Connect to SQLite
$database = new SQLite3("faculty_checkin.db");

// Get total check-ins
$countQuery = "SELECT COUNT(*) AS total FROM faculty_checkin";
$countResult = $database->querySingle($countQuery, true);
$totalCheckIns = $countResult['total'] ?? 0;

// Fetch last check-in date and time
$lastCheckQuery = "SELECT checkin_date, checkin_time FROM faculty_checkin ORDER BY checkin_date DESC, checkin_time DESC LIMIT 1";
$lastCheckIn = $database->querySingle($lastCheckQuery, true);
$lastCheckInDate = $lastCheckIn['checkin_date'] ?? null;
$lastCheckInTime = $lastCheckIn['checkin_time'] ?? 'No records yet';

// Get the current date
$currentDate = date('Y-m-d');

// Check if the last check-in date is different from the current date
$newPageMessage = '';
if ($lastCheckInDate !== $currentDate && !isset($_SESSION['notice_displayed'])) {
    $newPageMessage = "Starting a new page for check-ins on: " . htmlspecialchars($currentDate);
    $_SESSION['notice_displayed'] = true; // Set session variable to indicate notice has been displayed
}

// Get filters
$search = $_GET['search'] ?? '';
$from_date = $_GET['from_date'] ?? '';
$to_date = $_GET['to_date'] ?? '';

// Build SQL query with filters
$query = "SELECT * FROM faculty_checkin WHERE 1=1";

if (!empty($search)) {
    $query .= " AND (name LIKE '%$search%' OR teacher_id LIKE '%$search%' OR class LIKE '%$search%')";
}
if (!empty($from_date)) {
    $query .= " AND checkin_date >= '$from_date'";
}
if (!empty($to_date)) {
    $query .= " AND checkin_date <= '$to_date'";
}

$query .= " ORDER BY checkin_date DESC, checkin_time DESC";

$result = $database->query($query);

// Analyze lateness patterns
$latenessQuery = "SELECT name, COUNT(*) as late_count 
                   FROM faculty_checkin 
                   WHERE checkin_time > '07:00:00' 
                   GROUP BY name 
                   HAVING late_count > 0"; // Get all who have been late at least once

$latenessResult = $database->query($latenessQuery);
$lateNames = [];

while ($row = $latenessResult->fetchArray(SQLITE3_ASSOC)) {
    $lateNames[] = htmlspecialchars($row['name']) . " (Late: " . $row['late_count'] . " times)";
}

$lateNamesMessage = '';
if (!empty($lateNames)) {
    $lateNamesMessage = "Hi Admin, I've noticed the following faculty members have been late: " . implode(", ", $lateNames) . ".";
} else {
    $lateNamesMessage = "Hi Admin, there are no faculty members who have been late.";
}

// Optional: Clear session variable at the start of a new day
if ($currentDate !== ($_SESSION['last_checkin_date'] ?? '')) {
    unset($_SESSION['notice_displayed']);
    $_SESSION['last_checkin_date'] = $currentDate; // Update last check-in date
}

// Function to delete all check-in records
if (isset($_POST['delete_all'])) {
    $deleteQuery = "DELETE FROM faculty_checkin";
    $database->exec($deleteQuery);
    header("Location: admin.php"); // Redirect to the same page after deletion
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Employees Attendance Check-in</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #4F46E5;
            --ai-accent: #10B981;
            --surface: #F8FAFC;
            --border: #E2E8F0;
            --text-color: #333; /* Professional text color */
            --header-height: 80px; /* Adjusted header height */
            --table-bg: #f9f9f9; /* Light gray background for table */
            --table-border: #ddd; /* Border color for table */
        }
        body { 
            font-family: 'Segoe UI            ', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f7fa; 
            margin: 0; 
            padding: 20px; 
        }
        .container { 
            max-width: 1200px; 
            margin: auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
        }
        .ai-header {
            background: linear-gradient(135deg, #3B3F5C 0%, #4F46E5 100%); /* Deepened gradient */
            color: black; /* Set title color to black */
            border-radius: 12px;
            padding: 15px; /* Reduced padding for a sleeker look */
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Add shadow for depth */
        }
        .subject-title {
            font-size: 2em; /* Adjusted font size */
            font-weight: bold; /* Make the text bold */
            margin: 0; /* Remove default margin */
        }
        .ai-header i {
            margin-right: 8px;
            font-size: 1.5em; /* Increase icon size */
        }
        .stats { 
            display: flex; 
            justify-content: space-between; 
            margin-bottom: 20px; 
            padding: 15px; 
            background: #2980B9; 
            color: white; 
            border-radius: 5px; 
        }
        .stats div { 
            flex: 1; 
            text-align: center; 
            position: relative; /* For positioning the icon */
        }
        .stats div i {
            position: absolute;
            left: 10px; /* Positioning the icon */
            top: 50%;
            transform: translateY(-50%); /* Center the icon vertically */
            color: white; /* Change clock icon color to white */
            font-size: 1.5em; /* Icon size */
        }
        .filters { 
            margin-bottom: 20px; 
            text-align: center; 
            display: flex; /* Use flexbox for inline layout */
            justify-content: center; /* Center the filters */
            align-items: center; /* Align items vertically */
        }
        .filters input { 
            padding: 10px; 
            margin: 5px; 
            border: 1px solid #2980B9; 
            border-radius: 5px; 
            font-size: 14px; 
        }
        .filters button { 
            padding: 10px; 
            margin: 5px; 
            background-color: #2980B9; 
            color: white; 
            border: none; 
            cursor: pointer; 
            transition: background-color 0.3s; 
        }
        .filters button:hover { 
            background-color: #3498DB; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            border-radius: 8px; 
            overflow: hidden; 
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: var(--table-bg); /* Light gray background for table */
        }
        th, td { 
            padding: 12px; 
            text-align: left; 
            border-bottom: 1px solid var(--table-border); /* Border color for table */
            color: var(--text-color); /* Professional font color */
        }
        th { 
            background-color: #2980B9; 
            color: white; 
        }
        tr:hover { 
            background-color: #f1f1f1; 
        }
        .back-button, .reset-button { 
            display: block; 
            width: 100%; 
            max-width: 200px; 
            margin: 20px auto; 
            padding: 10px; 
            background: #5BA4E6; 
            color: white; 
            text-align: center; 
            border-radius: 5px; 
            text-decoration: none; 
            transition: background-color 0.3s; 
        }
        .back-button:hover, .reset-button:hover { 
            background: #4A90E2; 
        }
        .late-checkin { 
            color: #555; /* Changed to a more professional color */
            font-weight: bold; 
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header Card -->
    <div class="ai-header">
        <h2 class="subject-title"><i class="fas fa-user-check"></i> Employees Attendance Check-in</h2>
        <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage employee attendance efficiently!</p>
    </div>

    <div class="stats">
        <div>
            <i class="fas fa-check-circle"></i>
            <strong>Total Check-Ins:</strong> <?= $totalCheckIns ?>
        </div>
        <div>
            <i class="fas fa-clock"></i>
            <strong>Last Check-In Time:</strong> <?= htmlspecialchars($lastCheckInTime) ?>
        </div>
    </div>

    <div class="filters">
        <i class="fas fa-search"></i>
        <input type="text" id="search" placeholder="Search by Name, ID, or Class" value="<?= htmlspecialchars($search) ?>">
        <i class="fas fa-calendar-alt"></i>
        <input type="date" id="from_date" value="<?= htmlspecialchars($from_date) ?>">
        <i class="fas fa-calendar-alt"></i>
        <input type="date" id="to_date" value="<?= htmlspecialchars($to_date) ?>">
        <button onclick="filterRecords()">Filter</button>
        <form method="POST" style="display:inline;">
            <button type="submit" name="delete_all" class="reset-button">Reset All Check-Ins</button>
        </form>
        <button onclick="exportCSV()">Download CSV</button>
    </div>

    <table>
        <thead>
            <tr>
                <th><i class="fas fa-user"></i> Name</th>
                <th><i class="fas fa-id-badge"></i> ID</th>
                <th><i class="fas fa-chalkboard-teacher"></i> Class</th>
                <th><i class="fas fa-clock"></i> Time</th>
                <th><i class="fas fa-calendar-alt"></i> Date</th>
            </tr>
        </thead>
        <tbody id="checkinTableBody">
            <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <?php
                    $checkin_time = $row['checkin_time'];
                    $is_late = (strtotime($checkin_time) > strtotime("07:00:00")) ? 'late-checkin' : '';
                ?>
                <tr class="<?= $is_late ?>">
                    <td><i class="fas fa-user"></i> <?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['teacher_id']) ?></td>
                    <td><?= htmlspecialchars($row['class']) ?></td>
                    <td><?= htmlspecialchars($row['checkin_time']) ?></td>
                    <td><?= htmlspecialchars($row['checkin_date']) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

        <a href="unnamed.html" class="back-button">Go to Scanner</a>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script>
    // Show SweetAlert modal on page load
    window.onload = function() {
        const newPageMessage = "<?= $newPageMessage ?>";
        if (newPageMessage) {
            swal({
                title: "Notice",
                text: newPageMessage,
                icon: "info",
                button: false,
                timer: 4000 // Auto-close after 4 seconds
            });
        }
    };

    // Function to filter records
    function filterRecords() {
        const search = document.getElementById('search').value;
        const from_date = document.getElementById('from_date').value;
        const to_date = document.getElementById('to_date').value;
        window.location.href = `admin.php?search=${search}&from_date=${from_date}&to_date=${to_date}`;
    }

    // Function to export CSV
    function exportCSV() {
        window.location.href = 'export.php';
    }
</script>

</body>
</html>