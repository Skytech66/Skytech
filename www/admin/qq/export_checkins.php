<?php
include 'db_connection.php';

$type = $_GET['type']; 

$query = "SELECT * FROM faculty_checkins ORDER BY checkin_date DESC, checkin_time DESC";
$result = $db->query($query);
$data = $result->fetchAll(PDO::FETCH_ASSOC);

if ($type == "csv") {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="faculty_checkins.csv"');

    $output = fopen("php://output", "w");
    fputcsv($output, array("ID", "Name", "Faculty ID", "Class", "Date", "Time"));

    foreach ($data as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
} elseif ($type == "pdf") {
    require 'vendor/autoload.php'; 
    $mpdf = new \Mpdf\Mpdf();
    
    $html = '<h2>Faculty Check-Ins</h2><table border="1"><tr><th>ID</th><th>Name</th><th>Faculty ID</th><th>Class</th><th>Date</th><th>Time</th></tr>';
    
    foreach ($data as $row) {
        $html .= "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['teacher_id']}</td><td>{$row['class']}</td><td>{$row['checkin_date']}</td><td>{$row['checkin_time']}</td></tr>";
    }
    
    $html .= "</table>";
    $mpdf->WriteHTML($html);
    $mpdf->Output("faculty_checkins.pdf", "D");
}
?>