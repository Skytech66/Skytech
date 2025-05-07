<?php
	 if (isset($_GET['dashboard'])) {
		include 'dashboard.php';
	 }
	 
	  if (isset($_GET['emp'])) {
		  include 'employees.php';
	  }
	  if (isset($_GET['students'])) {
		  include 'reception/students.php';
	  }

	  if (isset($_GET['sent-messages'])) {
		  include 'qq/sent-messages.php';
	  }
	  if (isset($_GET['expenses'])) {
		  include 'rec.php';
	  }
	  
	  if (isset($_GET['reg_employee'])) {
		  include 'reg_employee.php';
	  }
	  if (isset($_GET['student_fees'])) {
         include 'reception/student_fees.php';
      }
	  
	  if (isset($_GET['class'])) {
		 include 'class.php';
	  }
	  if (isset($_GET['unnamed'])) {
		 include 'qq/unnamed.php';
	  }
	   if (isset($_GET['admin_pickup'])) {
		 include 'qq/admin_pickup.php';
	  }

	  if (isset($_GET['subject'])) {
		  include 'subjects.php';
	  }
	  
	  if (isset($_GET['exam'])) {
		include 'exam.php';
	  }
	  
	  if (isset($_GET['emp_report'])) {
		include 'emp_report.php';
	  }
	  
	  if (isset($_GET['class_report'])) {
		include 'class_report.php';
	  }
	  
	  if (isset($_GET['subjects_report'])) {
		include 'subjects_report.php';
	  }
	  
	  if (isset($_GET['exam_report'])) {
		include 'exam_report.php';
	  }

?>
