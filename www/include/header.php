<?php
require_once "../include/functions.php";

$session_id = $_SESSION["id"];

if($session_id == ""){
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

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../include/fullcalendar/fullcalendar.min.css" />
    <link rel="stylesheet" href="../include/css/style.css">

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs-3.3.7/jq-2.2.4/dt-1.10.15/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="../include/js/functions.js"></script>

    <style>
        .text-center {
            font-size: 1.5rem; /* Adjust font size */
            font-weight: bold; /* Make it bold */
            color: #333; /* Change color to a darker shade */
            margin-top: 10px; /* Add some space above */
        }
    </style>
</head>
<body>

<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="p-4 pt-5">
            <a href="#" class="img logo rounded-circle mb-3" style="background-image: url(../images/logo.jpg);"></a>
            <h4 class="text-center">EduPro Suite 2.0</h4> <!-- Title positioned directly under the logo -->
            <ul class="list-unstyled components mb-5">
                <li class="active">
                    <a href="index.php?dashboard">Dashboard</a>
                </li>
                <li>
                    <a href="index.php?exams">Exams</a>
                </li>
                <li>
                    <a href="index.php?subjects">Subjects</a>
                </li>
                <li>
                    <a href="index.php?lesson_notes">Lesson notes</a>
                </li>
                <li>
                    <a href="index.php?login">Attendance register</a>
                </li>
                <li>
                    <a href="form.php">Add students</a>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Contact</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="index.php?sms">>SMS</a>
                        </li>
                        <li>
                            <a href="index.php?mails">>Emails</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Generate Reports</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                                                        <a href="#Generate_Report_Cards" data-toggle="modal" data-target="#Generate_Report_Cards">>Students Report</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div id="content" class="p-4 p-md-5 pt-5">
        <h2 class="mb-4">Welcome to EduPro Suite</h2>
        <p>Your content goes here...</p>
        <!-- Add your main content here -->
    </div>
</div>

<!-- Modal for Generate Report Cards -->
<div class="modal fade" id="Generate_Report_Cards" tabindex="-1" role="dialog" aria-labelledby="Generate_Report_Cards_Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Generate_Report_Cards_Label">Generate Report Cards</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Form for generating report cards -->
                <form>
                    <div class="form-group">
                        <label for="student_id">Student ID</label>
                        <input type="text" class="form-control" id="student_id" placeholder="Enter Student ID">
                    </div>
                    <div class="form-group">
                        <label for="term">Select Term</label>
                        <select class="form-control" id="term">
                            <option>Term 1</option>
                            <option>Term 2</option>
                            <option>Term 3</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Generate</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Optional: Add any custom JavaScript here
</script>

</body>
</html>
