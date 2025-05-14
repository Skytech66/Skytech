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
    // Professional color scheme
    private $primaryColor = [13, 36, 68];    // Navy blue
    private $secondaryColor = [241, 243, 245]; // Light gray
    private $accentColor = [255, 195, 0];   // Gold
    private $successColor = [40, 167, 69];  // Green
    private $warningColor = [255, 193, 7];  // Yellow
    private $dangerColor = [220, 53, 69];   // Red
    
    function header() {
        // School header with modern typography
        $this->SetY(10);
        
        // School name
        $this->SetFont('Helvetica', 'B', 18);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 8, 'ADINKRA INTERNATIONAL SCHOOL', 0, 1, 'C');
        
        // Subheader with motto
        $this->SetFont('Helvetica', 'I', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Excellence Through Knowledge', 0, 1, 'C');
        
        // Contact information
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(70, 70, 70);
        $this->Cell(0, 5, 'P.M.B 40, Madina | TEL: 0277411866 / 0541622751', 0, 1, 'C');
        $this->Cell(0, 5, 'LOCATION: Abokobi / Boi New Town', 0, 1, 'C');
        
        // Decorative line
        $this->SetDrawColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY() + 3, 200, $this->GetY() + 3);
        $this->Ln(8);
    }

    function footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . ' | © ' . date('Y') . ' Adinkra International School', 0, 0, 'C');
    }

    function getPersonalizedRemark($averageScore) {
        $remarks = [
            "Exceptional performance across all subjects. Demonstrates outstanding academic ability and commitment to learning.",
            "Consistent high achiever with excellent understanding of concepts. Maintains strong work ethic.",
            "Good overall performance with noticeable strengths in several subjects. Shows steady progress.",
            "Making satisfactory progress. Would benefit from more consistent effort and focus.",
            "Needs to apply more effort to reach full potential. Additional practice at home recommended."
        ];
        
        if ($averageScore >= 85) return $remarks[0];
        if ($averageScore >= 75) return $remarks[1];
        if ($averageScore >= 60) return $remarks[2];
        if ($averageScore >= 50) return $remarks[3];
        return $remarks[4];
    }

    function getConductAssessment($averageScore) {
        $conducts = [
            "Exemplary behavior. Consistently demonstrates respect, responsibility, and leadership.",
            "Very good conduct. Polite, cooperative, and follows classroom rules.",
            "Generally well-behaved with occasional reminders needed.",
            "Behavior needs improvement. Sometimes disruptive to learning environment.",
            "Frequent behavior issues that interfere with learning."
        ];
        
        if ($averageScore >= 85) return $conducts[0];
        if ($averageScore >= 75) return $conducts[1];
        if ($averageScore >= 60) return $conducts[2];
        if ($averageScore >= 50) return $conducts[3];
        return $conducts[4];
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        $conductRemarks = [
            "Consistently demonstrates outstanding behavior and a positive attitude.",
            "Polite, respectful, and a joy to have in class.",
            "Works well with others and shows good classroom etiquette.",
            "Generally well-behaved but sometimes needs reminders to stay focused.",
            "Has shown improvement in behavior this term."
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
                'photo' => $photoPath,
                'average' => $subjectCount > 0 ? $totalScore / $subjectCount : 0
            ];
        }

        arsort($totalScores);
        $ret->reset();

        foreach ($totalScores as $admno => $data) {
            // Report Title
            $this->SetFont('Helvetica', 'B', 14);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'ACADEMIC PERFORMANCE REPORT', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, $exam . ' Terminal Assessment | ' . date('F j, Y'), 0, 1, 'C');
            $this->Ln(5);

            // Student Information Box
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 25, 3, 'F');
            
            // Student Photo
            $photoX = 15;
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], $photoX, $this->GetY() + 3, 18, 18, 'JPG', '', 'L', false, 300, '', false, false, 0, false, false, false);
            } else {
                $this->SetFillColor(220, 220, 220);
                $this->Rect($photoX, $this->GetY() + 3, 18, 18, 'F');
                $this->SetFont('Helvetica', 'I', 8);
                $this->SetTextColor(150, 150, 150);
                $this->SetXY($photoX, $this->GetY() + 10);
                $this->Cell(18, 5, 'No Photo', 0, 0, 'C');
            }

            // Student Details - Organized in two rows
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(0, 0, 0);
            
            // First row of details
            $this->SetXY(40, $this->GetY() + 4);
            $this->Cell(20, 5, 'Name:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(60, 5, $data['student'], 0, 0, 'L');
            
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(20, 5, 'Class:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 5, $class, 0, 1, 'L');
            
            // Second row of details
            $this->SetX(40);
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(20, 5, 'Adm No:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(60, 5, $admno, 0, 0, 'L');
            
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(20, 5, 'Term Ends:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 5, '17th April, 2025', 0, 1, 'L');
            
            $this->Ln(10);

            // Academic Performance Table
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 9);
            
            $header = ['SUBJECT', 'CLASS(50%)', 'EXAM(50%)', 'TOTAL(100%)', 'GRADE', 'POSITION'];
            $w = [45, 25, 25, 25, 20, 25];
            
            // Ensure the total width doesn't exceed page width
            $totalWidth = array_sum($w);
            if ($totalWidth > 190) {
                $scaleFactor = 190 / $totalWidth;
                foreach ($w as &$width) {
                    $width = $width * $scaleFactor;
                }
            }
            
            for($i=0; $i<count($header); $i++)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            $this->Ln();
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', '', 9);
            
            $sqlm = "SELECT subject, midterm, endterm, average, position FROM marks WHERE admno = '$admno' AND examname = '$exam'";
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

                $this->Cell($w[0], 6, $subject, 'LRB', 0, 'L');
                $this->Cell($w[1], 6, $midterm, 'RB', 0, 'C');
                $this->Cell($w[2], 6, $endterm, 'RB', 0, 'C');
                $this->Cell($w[3], 6, $average, 'RB', 0, 'C');

                // Grade with color coding
                if ($average >= 80) {
                    $grade = 'A';
                    $this->SetTextColor($this->successColor[0], $this->successColor[1], $this->successColor[2]);
                } elseif ($average >= 70) {
                    $grade = 'B';
                    $this->SetTextColor(65, 131, 215); // Blue
                } elseif ($average >= 60) {
                    $grade = 'C';
                    $this->SetTextColor(108, 117, 125); // Gray
                } elseif ($average >= 50) {
                    $grade = 'D';
                    $this->SetTextColor($this->warningColor[0], $this->warningColor[1], $this->warningColor[2]);
                } else {
                    $grade = 'E';
                    $this->SetTextColor($this->dangerColor[0], $this->dangerColor[1], $this->dangerColor[2]);
                }
                
                $this->SetFont('Helvetica', 'B', 9);
                $this->Cell($w[4], 6, $grade, 'RB', 0, 'C');
                $this->SetTextColor(0, 0, 0);
                
                // Position
                $this->SetFont('Helvetica', 'B', 9);
                if (is_numeric($originalPosition) && $originalPosition > 0) {
                    $this->Cell($w[5], 6, ordinal($originalPosition), 'RB', 0, 'C');
                } else {
                    $this->Cell($w[5], 6, 'N/A', 'RB', 0, 'C');
                }
                $this->Ln();
            }

            // Grading Key
            $this->Ln(5);
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 10, 3, 'F');
            
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetXY(10, $this->GetY() + 3);
            $this->Cell(190, 5, 'GRADING KEY', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 8);
            $this->SetXY(10, $this->GetY() + 3);
            $this->Cell(38, 5, 'A (80-100) = Excellent', 0, 0, 'C');
            $this->Cell(38, 5, 'B (70-79) = Very Good', 0, 0, 'C');
            $this->Cell(38, 5, 'C (60-69) = Good', 0, 0, 'C');
            $this->Cell(38, 5, 'D (50-59) = Average', 0, 0, 'C');
            $this->Cell(38, 5, 'E (Below 50) = Needs Improvement', 0, 1, 'C');
            $this->Ln(5);

            // Attendance and Promotion
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, 'Attendance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(30, 6, '______ out of ______', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, 'Promoted to:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(0, 6, '_________________________', 0, 1, 'L');
            $this->Ln(3);

            // Teacher Comments
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 7, 'TEACHER COMMENTS', 0, 1, 'L');
            
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 25, 3, 'F');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetXY(15, $this->GetY() + 3);
            $this->Cell(40, 5, 'Academic Performance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->MultiCell(150, 5, $this->getPersonalizedRemark($data['average']), 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetXY(15, $this->GetY() + 1);
            $this->Cell(40, 5, 'Conduct:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->MultiCell(150, 5, $this->getConductAssessment($data['average']), 0, 'L');
            
            $this->Ln(8);

            // Signatures
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(95, 6, 'Class Teacher: _________________________', 0, 0, 'L');
            $this->Cell(95, 6, 'Date: _______________', 0, 1, 'R');
            
            $this->Cell(95, 6, 'Head Teacher: _________________________', 0, 0, 'L');
            $this->Cell(95, 6, 'Date: _______________', 0, 1, 'R');
            
            $this->Ln(5);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(0, 6, 'Parent/Guardian Signature: _________________________', 0, 1, 'L');
            
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
