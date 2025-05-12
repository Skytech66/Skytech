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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
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
</head>
<body>

<div class="wrapper d-flex align-items-stretch">
    <nav id="sidebar">
        <div class="p-4 pt-5">
            <a href="#" class="img logo rounded-circle mb-5" style="background-image: 
                <a href="#" class="circle mb-5" style="background-image: url('/www/images/icon.png');"></a>
                        <h4 class="text-center">EduPro Suite 2.0</h4> <!-- Title positioned directly under the logo -->


            <ul class="list-unstyled components mb-5">
                <li class="active">
                    <a href="index.php?dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                </li>
                <li>
                    <a href="index.php?exams"><i class="fas fa-file-alt"></i> Exams</a>
                </li>
                <li>
                    <a href="exam_scores.php?exam_scores"><i class="fas fa-chart-line"></i> Exam Scores</a>
                </li>
                <li>
                    <a href="index.php?lesson_notes"><i class="fas fa-book"></i> Lesson Notes</a>
                </li>
                <li>
                    <a href="index.php?subjects"><i class="fas fa-book-open"></i> Subjects</a>
                </li>
                <li>
                    <a href="index.php?form"><i class="fas fa-user-plus"></i> Add Students</a>
                </li>
                <li>
                    <a href="index.php?view_students"><i class="fas fa-eye"></i> View Students & Add Passport Photo</a>
                </li>
                <li>
				   <a href="index.php?login"><i class="fas fa-user-check"></i> Attendance Register</a>
                </li>
                <li>
                    <a href="index.php?mails"><i class="fas fa-envelope"></i> Emails</a>
                </li>
                <li>
                    
                    <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"><i class="fas fa-file-invoice"></i> Generate Reports</a>
                    <ul class="collapse list-unstyled" id="pageSubmenu">
                        <li>
                            <a href="#Generate_Report_Cards" data-toggle="modal" data-target="#Generate_Report_Cards"><i class="fas fa-file-alt"></i> Students Report</a>
                        </li>
                    </ul>
                </li>
            </ul>

            <div class="footer">
                <p>&copy;<script>document.write(new Date().getFullYear());</script> powered <i class="icon-heart" aria-hidden="true"></i> by <a href="https://me.co.ke" target="_blank">Swipeware tech.</a></p>
            </div>
		    </nav>

   <!-- Page Content  -->
   <div id="content" class="content-container">
  <div class="container-fluid">
    <nav class="navbar">      
	<button type="button" id="sidebarCollapse" class="btn btn-primary">
	  <i class="fa fa-bars"></i>
	  <span class="sr-only">Toggle Menu</span>
	</button>
	<button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<i class="fa fa-bars"></i>
	</button>

	<div class="collapse navbar-collapse" id="navbarSupportedContent">
	  <ul class="nav navbar-nav ml-auto">
		<li class="nav-item active">
			<a class="nav-link" href="#Change_Password"  data-toggle="modal" data-target="#Change_Password">Change password</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="../include/functions.php?logout=1">Logout</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#">Help</a>
		</li>
	  </ul>
	</div>
</nav>
</div>

            <div class="modal fade" id="Generate_Report_Cards" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-warning text-light">
                            <h5 class="modal-title" id="exampleModalLabel">SEARCH MARK SHEET</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="report_cards.php" method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col">
                                        <label for="recipient-name" class="col-form-label">Select Class:</label>
                                        <div class="form-group">
                                            <select class="form-select form-select-sm" aria-label=".form-select-md example" id="askclass" name="askclass" required>
                                                <option selected value="">Select class</option>
                                                <?php 
                                                    $sql = "SELECT * from class ORDER BY classid DESC";
                                                    $res = $conn->query($sql);
                                                    while($row = $res->fetchArray(SQLITE3_ASSOC)){
                                                        $id = $row["classname"];
                                                ?>
                                                    <option value="<?php echo $row["classname"]; ?>"> <?php echo $row["classname"]; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <label for="recipient-name" class="col-form-label">Select Exam:</label>
                                        <div class="form-group">
                                            <select class="form-select form-select-sm" aria-label=".form-select-md example" id="exam" name="exam" required>
                                                <option selected value="">Select exam</option>
                                                <?php 
                                                    $res = "SELECT * from exam ORDER BY examid DESC";
                                                    $ret1 = $conn->query($res);
                                                    while($row = $ret1->fetchArray(SQLITE3_ASSOC)) {
                                                ?>
                                                    <option value="<?php echo $row["examname"]; ?>"> <?php echo $row["examname"]; ?> </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>  
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="submit" value="search" class="btn btn-primary">Generate Report Cards</button>
                                     </div>
                               </form>   
                             </div>
                         </div></div>
                       </div>
