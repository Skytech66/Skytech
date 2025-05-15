<?php
ob_start();

function getRandomConductRemark($conductRemarks) {
    if (empty($conductRemarks)) {
        return 'No conduct remarks available.';
    }
    return $conductRemarks[array_rand($conductRemarks)];
}

function ordinal($number) {
    $number = (int)$number;
    if (!in_array(($number % 100), [11, 12, 13])) {
        switch ($number % 10) {
            case 1: return $number . 'st';
            case 2: return $number . 'nd';
            case 3: return $number . 'rd';
        }
    }
    return $number . 'th';
}

require "fpdf.php";

class mypdf extends FPDF {
    // Modern color palette
    private $primaryColor = [0, 70, 140];   // Dark blue
    private $secondaryColor = [220, 230, 241]; // Light blue
    private $accentColor = [255, 195, 0];   // Gold
    private $successColor = [40, 167, 69];  // Green
    private $warningColor = [255, 193, 7];  // Yellow
    private $dangerColor = [220, 53, 69];   // Red
    
    function header() {
        // Modern minimalist header
        $this->SetY(15);
        
        // School logo placeholder
        $this->Image('logo_placeholder.png', 10, 10, 25, 25);
        
        // School name with subtle shadow
        $this->SetFont('Helvetica', 'B', 20);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 8, 'ADINKRA INTERNATIONAL SCHOOL', 0, 1, 'C');
        
        // Subheader with divider
        $this->SetFont('Helvetica', 'I', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Excellence Through Knowledge', 0, 1, 'C');
        
        // Thin divider line
        $this->SetDrawColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY() + 5, 200, $this->GetY() + 5);
        $this->Ln(12);
    }

    function footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor(150, 150, 150);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' | Generated on ' . date('M d, Y'), 0, 0, 'C');
    }

    function getRemarks() {
        $remarks = [
            "Making steady progress keep it up.",
            "A consistent effort will lead to improvement.",
            "Shows potential, needs to stay focused.",
            "Excellent performance across all subjects.",
            "Demonstrates strong analytical skills.",
            "Participation in class could be improved.",
            "Works well independently and in groups.",
            "Creative thinker with good problem-solving skills.",
            "Should focus more on completing assignments on time.",
            "Encouraged to keep working hard and not settle."
        ];
        return $remarks[array_rand($remarks)];
    }

    function getFees($class) {
        switch ($class) {
            case 'Basic Six A':
            case 'Basic Six B':
            case 'Basic Three B':
            case 'Basic Three A':
            case 'KG2':
            case 'Basic One':
                return 200;
            default:
                return 0;
        }
    }

    // Function to draw progress circle
    function progressCircle($x, $y, $r, $percent, $color) {
        // Circle background
        $this->SetDrawColor(220, 220, 220);
        $this->Circle($x, $y, $r, 0, 360);
        
        // Progress arc
        $this->SetDrawColor($color[0], $color[1], $color[2]);
        $this->SetLineWidth(2);
        $endAngle = 360 * ($percent / 100);
        $this->Circle($x, $y, $r, 0, $endAngle, 'D');
        
        // Percentage text
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetTextColor(70, 70, 70);
        $this->SetXY($x - 3, $y - 3);
        $this->Cell(6, 6, round($percent) . '%', 0, 0, 'C');
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        $conductRemarks = [
            "Consistently demonstrates outstanding behavior and a positive attitude.",
            "Works well with others and shows respect for classmates.",
            "Sometimes needs reminders to stay on task during class.",
            "Polite, respectful, and a joy to have in class."
        ];

        $sql = "SELECT name, admno, photo FROM student WHERE class LIKE '%$class%' ORDER BY admno ASC";
        $ret = $conn->query($sql);

        if (!$ret) {
            die("Query failed: " . $conn->lastErrorMsg());
        }

        $subjectOrder = [
            'English', 'Science', 'Owop', 'R.M.E', 'History', 
            'Computing', 'Creative', 'Twi', 'French'
        ];

        $totalScores = [];
        while ($row1 = $ret->fetchArray(SQLITE3_ASSOC)) {
            $admno = $row1["admno"];
            $studentName = $row1["name"];
            $photoPath = $row1["photo"];

            $sqlm = "SELECT average FROM marks WHERE admno = '$admno' AND examname = '$exam'";
            $retm = $conn->query($sqlm);
            
            $totalScore = 0;
            $subjectCount = 0;

            while ($row = $retm->fetchArray(SQLITE3_ASSOC)) {
                $average = $row["average"];
                $totalScore += $average;
                $subjectCount++;
            }

            $totalScores[$admno] = [
                'total' => $totalScore,
                'student' => $studentName,
                'subjectCount' => $subjectCount,
                'photo' => $photoPath
            ];
        }

        arsort($totalScores);
        $ret->reset();

        foreach ($totalScores as $admno => $data) {
            // Student Report Header
            $this->SetFont('Helvetica', 'B', 16);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'ACADEMIC PERFORMANCE REPORT', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Terminal Assessment - ' . $exam, 0, 1, 'C');
            $this->Ln(8);

            // Student Info Card with soft shadow effect
            $this->SetFillColor(250, 250, 250);
            $this->SetDrawColor(220, 220, 220);
            $this->RoundedRect(10, $this->GetY(), 190, 32, 5, 'DF');
            
            // Student Photo
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 15, $this->GetY() + 6, 20, 20, 'JPG', '', 'L', false, 300, '', false, false, 1, false, false, false);
            } else {
                $this->SetFillColor(230, 230, 230);
                $this->RoundedRect(15, $this->GetY() + 6, 20, 20, 3, 'F');
                $this->SetFont('Helvetica', 'I', 8);
                $this->SetTextColor(150, 150, 150);
                $this->SetXY(15, $this->GetY() + 14);
                $this->Cell(20, 4, 'No Photo', 0, 0, 'C');
            }

            // Student Details
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor(70, 70, 70);
            $this->SetXY(40, $this->GetY() + 6);
            $this->Cell(50, 6, $data['student'], 0, 1, 'L');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetX(40);
            $this->Cell(30, 6, 'Class:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, $class, 0, 0, 'L');
            
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(30, 6, 'Admission No:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, $admno, 0, 1, 'L');
            
            $this->SetX(40);
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(30, 6, 'Term Ends:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, '17th April, 2025', 0, 0, 'L');
            
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(30, 6, 'Resumes:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, '6th May, 2025', 0, 1, 'L');
            
            $this->Ln(12);

            // Academic Performance Section
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'ACADEMIC PERFORMANCE', 0, 1, 'L');
            
            // Table Header with gradient background
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 10);
            
            $header = ['SUBJECT', 'SCORE', 'GRADE', 'POSITION', 'PROGRESS'];
            $w = [50, 25, 25, 30, 60];
            
            for($i=0; $i<count($header); $i++)
                $this->Cell($w[$i], 8, $header[$i], 0, 0, 'C', true);
            $this->Ln();
            
            // Table Rows
            $this->SetTextColor(70, 70, 70);
            $this->SetFont('Helvetica', '', 9);
            
            $sqlm = "SELECT subject, midterm, endterm, average, remarks, position FROM marks WHERE admno = '$admno' AND examname = '$exam'";
            $retm = $conn->query($sqlm);

            $subjects = [];
            while ($row = $retm->fetchArray(SQLITE3_ASSOC)) {
                $subjects[] = $row;
            }

            usort($subjects, function($a, $b) use ($subjectOrder) {
                $posA = array_search($a['subject'], $subjectOrder);
                $posB = array_search($b['subject'], $subjectOrder);
                return $posA - $posB;
            });

            $fill = false;
            foreach ($subjects as $row) {
                $subject = $row["subject"];
                $average = $row["average"];
                $originalPosition = $row["position"];
                
                // Alternate row colors
                if ($fill) {
                    $this->SetFillColor(245, 245, 245);
                } else {
                    $this->SetFillColor(255, 255, 255);
                }
                $fill = !$fill;
                
                // Subject name
                $this->Cell($w[0], 12, $subject, 'LR', 0, 'L', true);
                
                // Score
                $this->SetFont('Helvetica', 'B', 10);
                $this->Cell($w[1], 12, $average, 'LR', 0, 'C', true);
                
                // Grade with color coding
                if ($average >= 80) {
                    $grade = 'A'; 
                    $gradeColor = $this->successColor;
                } elseif ($average >= 70) {
                    $grade = 'B'; 
                    $gradeColor = [65, 176, 81]; // Lighter green
                } elseif ($average >= 60) {
                    $grade = 'C'; 
                    $gradeColor = $this->warningColor;
                } elseif ($average >= 50) {
                    $grade = 'D'; 
                    $gradeColor = [253, 126, 20]; // Orange
                } else {
                    $grade = 'F'; 
                    $gradeColor = $this->dangerColor;
                }
                
                $this->SetTextColor($gradeColor[0], $gradeColor[1], $gradeColor[2]);
                $this->Cell($w[2], 12, $grade, 'LR', 0, 'C', true);
                $this->SetTextColor(70, 70, 70);
                
                // Position
                $this->SetFont('Helvetica', '', 9);
                if (is_numeric($originalPosition) && $originalPosition > 0) {
                    $this->Cell($w[3], 12, ordinal($originalPosition), 'LR', 0, 'C', true);
                } else {
                    $this->Cell($w[3], 12, 'N/A', 'LR', 0, 'C', true);
                }
                
                // Progress circle
                $this->SetXY($this->GetX(), $this->GetY() + 2);
                $this->Cell($w[4], 8, '', 'LR', 0, 'C', true);
                $this->progressCircle($this->GetX() - 30, $this->GetY() + 4, 8, $average, $gradeColor);
                $this->Ln(12);
            }
            
            // Close the table
            $this->Cell(array_sum($w), 0, '', 'T');
            $this->Ln(8);

            // Summary Cards
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'PERFORMANCE SUMMARY', 0, 1, 'L');
            
            // Calculate overall average
            $overallAverage = $data['subjectCount'] > 0 ? $data['total'] / $data['subjectCount'] : 0;
            
            // Summary cards container
            $this->SetFillColor(250, 250, 250);
            $this->SetDrawColor(220, 220, 220);
            $this->RoundedRect(10, $this->GetY(), 190, 30, 5, 'DF');
            
            // Overall Performance Card
            $this->SetXY(15, $this->GetY() + 5);
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(40, 6, 'Overall Average:', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 14);
            if ($overallAverage >= 80) {
                $this->SetTextColor($this->successColor[0], $this->successColor[1], $this->successColor[2]);
            } elseif ($overallAverage >= 60) {
                $this->SetTextColor($this->warningColor[0], $this->warningColor[1], $this->warningColor[2]);
            } else {
                $this->SetTextColor($this->dangerColor[0], $this->dangerColor[1], $this->dangerColor[2]);
            }
            $this->Cell(20, 6, number_format($overallAverage, 1), 0, 0, 'L');
            
            // Class Position
            $this->SetXY(80, $this->GetY());
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(40, 6, 'Class Position:', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 14);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            
            // Find class position (this is simplified - you might need actual ranking logic)
            $classPosition = array_search($admno, array_keys($totalScores)) + 1;
            $this->Cell(20, 6, ordinal($classPosition), 0, 0, 'L');
            
            // Attendance
            $this->SetXY(145, $this->GetY());
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(30, 6, 'Attendance:', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 14);
            $this->SetTextColor(70, 70, 70);
            $this->Cell(20, 6, '95%', 0, 1, 'L');
            
            $this->Ln(10);

            // Comments Section
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'TEACHER COMMENTS', 0, 1, 'L');
            
            // Comments card
            $this->SetFillColor(250, 250, 250);
            $this->SetDrawColor(220, 220, 220);
            $this->RoundedRect(10, $this->GetY(), 190, 30, 5, 'DF');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetXY(15, $this->GetY() + 5);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(40, 6, 'Academic Remarks:', 0, 0, 'L');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetXY(15, $this->GetY() + 12);
            $this->MultiCell(180, 6, $this->getRemarks(), 0, 'L');
            
            $this->SetXY(15, $this->GetY() + 5);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, 'Conduct Remarks:', 0, 0, 'L');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetXY(15, $this->GetY() + 6);
            $this->MultiCell(180, 6, getRandomConductRemark($conductRemarks), 0, 'L');
            
            $this->Ln(15);

            // Grading Key
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'GRADING SYSTEM', 0, 1, 'L');
            
            $this->SetFillColor(250, 250, 250);
            $this->SetDrawColor(220, 220, 220);
            $this->RoundedRect(10, $this->GetY(), 190, 15, 5, 'DF');
            
            $this->SetFont('Helvetica', '', 9);
            $this->SetXY(10, $this->GetY() + 5);
            $this->SetTextColor(100, 100, 100);
            
            $this->Cell(38, 5, 'A (80-100) = Excellent', 0, 0, 'C');
            $this->Cell(38, 5, 'B (70-79) = Very Good', 0, 0, 'C');
            $this->Cell(38, 5, 'C (60-69) = Good', 0, 0, 'C');
            $this->Cell(38, 5, 'D (50-59) = Average', 0, 0, 'C');
            $this->Cell(38, 5, 'E (40-49) = Credit', 0, 1, 'C');
            
            $this->Ln(10);

            // Signatures Section
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            
            $this->Cell(60, 6, 'Class Teacher:', 0, 0, 'L');
            $this->Cell(60, 6, '_________________________', 0, 0, 'L');
            $this->Cell(60, 6, 'Date: ' . date('M d, Y'), 0, 1, 'R');
            
            $this->Cell(60, 6, 'Head Teacher:', 0, 0, 'L');
            $this->Cell(60, 6, '_________________________', 0, 0, 'L');
            $this->Cell(60, 6, 'Parent Signature:', 0, 1, 'R');
            
            $this->Cell(60, 6, 'Principal:', 0, 0, 'L');
            $this->Cell(60, 6, '_________________________', 0, 0, 'L');
            $this->Cell(60, 6, '_________________________', 0, 1, 'R');
            
            // Add new page for next student
            if (next($totalScores) !== false) {
                $this->AddPage();
            }
        }
    }
    
    // Helper function for rounded rectangles
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }
    
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
}

$pdf = new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->headertable();
$pdf->Output();
ob_end_flush();
?>
