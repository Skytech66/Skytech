<?php
require_once "../include/functions.php";

$session_id = $_SESSION["id"];

if ($session_id == "") {
    header("Location: ../index.php?error= Invalid username or password");
    exit();
}

$conn = db_conn();
?>
<!doctype html>
<html lang="en">
<head>
    <title>EduPro</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../include/fullcalendar/fullcalendar.min.css" />
    <link rel="stylesheet" href="../include/css/style.css">

    <style>
        /* Custom styles for the sidebar */
        body {
            display: flex;
            min-height: 100vh;
            margin: 0;
            padding: 0; /* Remove default body padding */
        }

        #sidebar {
            min-width: 250px;
            background: #2C3E50;
            color: #fff;
            transition: all 0.3s;
        }

        #sidebar .components {
            padding: 20px;
        }

        ul.list-unstyled li a {
            color: #fff; /* Default link color */
            padding: 10px;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        ul.list-unstyled li a:hover {
            background-color: #007bff; /* Change background on hover */
            color: white; /* Change text color on hover */
        }

        ul.list-unstyled li.active a {
            background-color: #0056b3; /* Active link background */
            color: white; /* Active link text color */
        }

        #content {
            flex: 1;
            padding: 4px; /* You can adjust this padding as needed */
            background: #f8f9fa;
            margin: 0; /* Remove any margin */
            margin-top: 90px; /* Add a small space at the top */
        }

        .footer {
            text-align: center;
            padding: 20px;
            background: #343a40;
            color: #fff;
            position: relative;
            bottom: 0;
            width: 100%;
        }

        /* Media query to hide sidebar on mobile */
        @media (max-width: 768px) {
            #sidebar {
                display: none; /* Hide sidebar on mobile devices */
            }
        }
    </style>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/dt-1.10.15/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../include/js/functions.js"></script>
</head>
<body>

<!-- Toggle Button for Sidebar -->
<button id="toggleButton" style="position: fixed; top: 20px; left: 20px; z-index: 1000;">
    <i class="fas fa-bars"></i>
</button>

<div id="sidebar">
    <div class="p-4 pt-5">
        <a href="#" class="img logo rounded-circle mb-5" style="background-image: url(../images/logo.jpg);"></a>
        <ul class="list-unstyled components mb-5">
            <li class="active">
                <a href="index.php?dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <li>
                <a href="index.php?emp"><i class="fas fa-money-check-alt"></i> Fees Management</a>
            </li>
            <li>
                <a href="index.php?emp"><i class="fas fa-user-graduate"></i> Student Management            </li>
            <li>
                <a href="index.php?emp"><i class="fas fa-check-circle"></i> Attendance Check-ins</a>
            </li>
            <li>
                <a href="index.php?emp"><i class="fas fa-file-invoice-dollar"></i> Expenses</a>
            </li>
            <li>
                <a href="index.php?emp"><i class="fas fa-comments"></i> Parent Communication</a>
            </li>
            <li>
                <a href="index.php?emp"><i class="fas fa-lock"></i> Secure Pickup</a>
            </li>
            <li>
                <a href="index.php?emp"><i class="fas fa-users"></i> Employees</a>
            </li>
            <li>
                <a href="index.php?class"><i class="fas fa-chalkboard-teacher"></i> Classes</a>
            </li>
            <li>
                <a href="index.php?subject"><i class="fas fa-book"></i> Subject Management</a>
            </li>
            <li>
                <a href="index.php?exam"><i class="fas fa-pencil-alt"></i> Exam Management</a>
            </li>
            <li>
                <a href="#Change_Password" data-toggle="modal" data-target="#Change_Password"><i class="fas fa-user"></i> Profile</a>
            </li>
            <li>
                <a href="../include/functions.php?logout=1"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
            <li>
                <a href="#"><i class="fas fa-question-circle"></i> Help</a>
            </li>
        </ul>
    </div>
    <div class="footer">
        <p>&copy;<script>document.write(new Date().getFullYear());</script> Powered by <a href="https://me.co" target="_blank">Swipeware Technologies</a></p>
    </div>
</div>

<!-- Page Content  -->
<div id="content">
    <!-- This space is now fully occupied by the content -->
</div>

<script>
    // JavaScript to toggle the sidebar
    const toggleButton = document.getElementById('toggleButton');
    const sidebar = document.getElementById('sidebar');
    let isSidebarVisible = false; // Track sidebar visibility

    toggleButton.addEventListener('click', () => {
        isSidebarVisible = !isSidebarVisible; // Toggle visibility state
        sidebar.style.display = isSidebarVisible ? 'block' : 'none'; // Show/hide sidebar
        toggleButton.innerHTML = isSidebarVisible 
            ? '<i class="fas fa-times"></i>' // Change to close icon
            : '<i class="fas fa-bars"></i>'; // Change to open icon
    });
</script>

</body>
</html>