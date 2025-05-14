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
    // School colors
    private $primaryColor = [0, 70, 140]; // Dark blue
    private $secondaryColor = [220, 230, 241]; // Light blue
    private $accentColor = [255, 195, 0]; // Gold
    
    function header() {
        // Modern header with school branding
        $this->SetY(10);
        $this->SetFont('Helvetica', 'B', 24);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 10, 'ADINKRA INTERNATIONAL SCHOOL', 0, 1, 'C');
        
        // Subheader with motto
        $this->SetFont('Helvetica', 'I', 12);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 6, 'Excellence Through Knowledge', 0, 1, 'C');
        
        // Contact information
        $this->SetFont('Helvetica', '', 10);
        $this->SetTextColor(70, 70, 70);
        $this->Cell(0, 6, 'P.M.B 40, Madina | TEL: 0277411866 / 0541622751', 0, 1, 'C');
        $this->Cell(0, 6, 'LOCATION: Abokobi / Boi New Town', 0, 1, 'C');
        
        // Decorative line
        $this->SetDrawColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
        $this->SetLineWidth(0.8);
        $this->Line(10, $this->GetY() + 5, 200, $this->GetY() + 5);
        $this->Ln(10);
    }

    function footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function getRemarks() {
        $remarks = [
            "Making steady progress keep it up.",
            "A consistent effort will lead to improvement.",
            "Shows potential, needs to stay focused.",
            // ... (keep all your existing remarks)
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

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        $conductRemarks = [
            "Consistently demonstrates outstanding behavior and a positive attitude.",
            // ... (keep all your existing conduct remarks)
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
            $this->SetFont('Helvetica', 'B', 18);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 10, 'STUDENT PROGRESS REPORT', 0, 1, 'C');
            $this->SetFont('Helvetica', '', 12);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(0, 6, 'Terminal Assessment - ' . $exam, 0, 1, 'C');
            $this->Ln(5);

            // Student Photo and Info Section
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 30, 5, 'F');
            
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 15, $this->GetY() + 5, 20, 20);
            } else {
                $this->SetFillColor(200, 200, 200);
                $this->Rect(15, $this->GetY() + 5, 20, 20, 'F');
            }

            $this->SetXY(40, $this->GetY() + 5);
            $this->SetFont('Helvetica', 'B', 14);
            $this->Cell(50, 7, 'Name:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 14);
            $this->Cell(100, 7, $data['student'], 0, 1, 'L');
            
            $this->SetX(40);
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(50, 7, 'Class:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(50, 7, $class, 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(30, 7, 'Term Ends:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(50, 7, '17th April, 2025', 0, 1, 'L');
            
            $this->SetX(40);
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(50, 7, 'Admission No:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(50, 7, $admno, 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(30, 7, 'Resumes:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(50, 7, '6th May, 2025', 0, 1, 'L');
            
            $this->Ln(15);

            // Academic Performance Table
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 11);
            
            $header = ['SUBJECT', 'CLASS(50%)', 'EXAM(50%)', 'TOTAL(100%)', 'GRADE', 'REMARKS', 'POSITION'];
            $w = [30, 25, 25, 25, 20, 40, 25];
            
            for($i=0; $i<count($header); $i++)
                $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
            $this->Ln();
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', '', 10);
            
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

            foreach ($subjects as $row) {
                $subject = $row["subject"];
                $midterm = $row["midterm"];
                $endterm = $row["endterm"];
                $average = $row["average"];
                $originalPosition = $row["position"];

                $this->Cell($w[0], 7, $subject, 'LRB', 0, 'C');
                $this->Cell($w[1], 7, $midterm, 'RB', 0, 'C');
                $this->Cell($w[2], 7, $endterm, 'RB', 0, 'C');
                $this->Cell($w[3], 7, $average, 'RB', 0, 'C');

                if ($average >= 80) {
                    $grade = 'A'; $remarks = 'Excellent';
                } elseif ($average >= 70) {
                    $grade = 'B'; $remarks = 'Very Good';
                } elseif ($average >= 60) {
                    $grade = 'C'; $remarks = 'Good';
                } elseif ($average >= 50) {
                    $grade = 'D'; $remarks = 'Average';
                } elseif ($average >= 40) {
                    $grade = 'E'; $remarks = 'Credit';
                } else {
                    $grade = 'F'; $remarks = 'Weak';
                }
                
                $this->SetFont('Helvetica', 'B', 10);
                $this->Cell($w[4], 7, $grade, 'RB', 0, 'C');
                $this->SetFont('Helvetica', '', 10);
                $this->Cell($w[5], 7, $remarks, 'RB', 0, 'C');
                $this->SetFont('Helvetica', 'B', 10);
                
                if (is_numeric($originalPosition) && $originalPosition > 0) {
                    $this->Cell($w[6], 7, ordinal($originalPosition), 'RB', 0, 'C');
                } else {
                    $this->Cell($w[6], 7, 'N/A', 'RB', 0, 'C');
                }
                $this->Ln();
            }

            // Grading Key
            $this->Ln(5);
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 15, 3, 'F');
            
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetXY(10, $this->GetY() + 3);
            $this->Cell(190, 5, 'GRADING KEY', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetXY(10, $this->GetY() + 3);
            $this->Cell(38, 5, 'A (80-100) = Excellent', 0, 0, 'C');
            $this->Cell(38, 5, 'B (70-79) = Very Good', 0, 0, 'C');
            $this->Cell(38, 5, 'C (60-69) = Good', 0, 0, 'C');
            $this->Cell(38, 5, 'D (50-59) = Average', 0, 0, 'C');
            $this->Cell(38, 5, 'E (40-49) = Credit', 0, 1, 'C');
            $this->Ln(5);

            // Attendance and Promotion Section
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 15, 3, 'F');
            
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetXY(15, $this->GetY() + 5);
            $this->Cell(40, 5, 'Attendance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->Cell(20, 5, '______', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 11);
            $this->Cell(40, 5, 'Out of:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->Cell(20, 5, '______', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 11);
            $this->Cell(40, 5, 'Promoted to:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->Cell(20, 5, '______', 0, 1, 'L');
            $this->Ln(5);

            // Comments Section
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(40, 7, 'Teacher Remarks:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->MultiCell(0, 7, $this->getRemarks(), 0, 'L');
            $this->Ln(3);
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(40, 7, 'Conduct:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->MultiCell(0, 7, getRandomConductRemark($conductRemarks), 0, 'L');
            $this->Ln(5);

            // Requirements and Management Section
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(95, 8, 'REQUIREMENTS FOR NEXT TERM', 1, 0, 'C', true);
            $this->Cell(95, 8, 'MANAGEMENT MESSAGE', 1, 1, 'C', true);
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', '', 10);
            
            $fees = $this->getFees($class);
            $requirements = "   SCHOOL FEES:     GHC " . $fees . "\n   COMPUTER FEE:   GHC 50\n   DETOL,                  1 (CAMEL).\n   TOILET ROLL 3, TOILET SOAP 2.\n   FEEDING FEE:      GHC 7.00.";
            
            $management = "WITH OUR SINCEREST THANKSGIVING TO PARENTS AND STAKEHOLDERS OF THE SCHOOL, WE LOOK FORWARD TO WORKING WITH YOU NEXT TERM. MAY GOD BLESS YOU.";
            
            $this->MultiCell(95, 6, $requirements, 1, 'L');
            $this->SetXY(105, $this->GetY() - 30);
            $this->MultiCell(95, 6, $management, 1, 'L');
            
            $this->Ln(10);
            
            // Signature Line
            $this->SetFont('Helvetica', 'B', 11);
            $this->Cell(0, 5, 'Class Teacher: _________________________', 0, 1, 'R');
            $this->Cell(0, 5, 'Head Teacher: _________________________', 0, 1, 'R');
            $this->Cell(0, 5, 'Date: _________________________', 0, 1, 'R');
            
            // Add new page for next student
            $this->AddPage();
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
