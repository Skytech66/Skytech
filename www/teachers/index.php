<?php
	if (isset($_GET['dashboard'])) {
	include 'dashboard.php';
	}
	 
	if (isset($_GET['exams'])) {
	  include 'exams.php';
	}

	if (isset($_GET['subjects'])) {
	 include 'subjects.php';
	}
	if (isset($_GET['exam_scores'])) {
	 include 'exam_scores.php';
	}

	if (isset($_GET['sms'])) {
	include 'sms.php';
	}

	if (isset($_GET['mails'])) {
	include 'mails.php';
	}

	if (isset($_GET['students_report'])) {
	include 'students_report.php';
	}
	if (isset($_GET['lesson_notes'])) {
	 include 'lesson_notes.php';
	}
    if (isset($_GET['login'])) {
	 include 'login.php';
	}
	if (isset($_GET['form'])) {
	 include 'form.php';
	}
	if (isset($_GET['class_merit'])) {
	include 'class_merit.php';
	}
	
	if (isset($_GET['view_page'])) {
	include 'view_page.php';
	}

	if (isset($_GET['subjects_report'])) {
	include 'subjects_report.php';
	}

?>
