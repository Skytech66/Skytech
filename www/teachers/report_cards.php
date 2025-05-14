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
    // Modern color scheme
    private $primaryColor = [0, 70, 140]; // Dark blue
    private $secondaryColor = [220, 230, 241]; // Light blue
    private $accentColor = [255, 195, 0]; // Gold
    private $borderColor = [200, 200, 200]; // Light gray
    
    function header() {
        // Modern minimalist header
        $this->SetY(10);
        $this->SetFont('Helvetica', 'B', 20);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 8, 'ADINKRA INTERNATIONAL SCHOOL', 0, 1, 'C');
        
        // Subheader with subtle styling
        $this->SetFont('Helvetica', 'I', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Excellence Through Knowledge', 0, 1, 'C');
        
        // Thin accent line
        $this->SetDrawColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
        $this->SetLineWidth(0.5);
        $this->Line(10, $this->GetY() + 3, 200, $this->GetY() + 3);
        $this->Ln(8);
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
            "Good performance, could still do better with more effort.",
            "Performance is satisfactory but needs more consistency.",
            "Has shown remarkable improvement this term.",
            "Needs to be more attentive in class to improve.",
            "Works well independently with good understanding.",
            "Demonstrates strong analytical skills in subject.",
            "Should participate more in class discussions.",
            "Encouraged to keep working hard and not settle."
        ];
        return $remarks[array_rand($remarks)];
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        $conductRemarks = [
            "Consistently demonstrates outstanding behavior and a positive attitude.",
            "Shows respect for teachers and peers, sets a good example.",
            "Generally well-behaved but sometimes needs reminders to stay focused.",
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
            $this->Cell(0, 8, 'STUDENT PROGRESS REPORT', 0, 1, 'C');
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Terminal Assessment - ' . $exam, 0, 1, 'C');
            $this->Ln(8);

            // Student Info Box with subtle background
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 25, 3, 'F');
            
            // Student Photo
            $photoY = $this->GetY() + 2.5;
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 12, $photoY, 20, 20);
            } else {
                $this->SetFillColor(230, 230, 230);
                $this->Rect(12, $photoY, 20, 20, 'F');
                $this->SetTextColor(150, 150, 150);
                $this->SetFont('Helvetica', 'I', 8);
                $this->SetXY(12, $photoY + 7);
                $this->Cell(20, 5, 'No Photo', 0, 0, 'C');
            }

            // Student Details
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetXY(35, $this->GetY() + 5);
            $this->Cell(30, 6, 'Name:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(80, 6, $data['student'], 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(25, 6, 'Adm No:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(0, 6, $admno, 0, 1, 'L');
            
            $this->SetX(35);
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(30, 6, 'Class:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(80, 6, $class, 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(25, 6, 'Term:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(0, 6, '3rd Term 2024/2025', 0, 1, 'L');
            
            $this->Ln(12);

            // Academic Performance Table - Sleek design
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 10);
            
            $header = ['SUBJECT', 'CLASS(50%)', 'EXAM(50%)', 'TOTAL', 'GRADE', 'POSITION'];
            $w = [40, 25, 25, 20, 20, 20, 40];
            
            for($i=0; $i<count($header); $i++) {
                $this->Cell($w[$i], 7, $header[$i], 0, 0, 'C', true);
            }
            $this->Ln();
            
            $this->SetTextColor(0, 0, 0);
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
                $midterm = $row["midterm"];
                $endterm = $row["endterm"];
                $average = $row["average"];
                $originalPosition = $row["position"];

                $this->SetFillColor($fill ? 245 : 255);
                $this->Cell($w[0], 7, $subject, 0, 0, 'L', $fill);
                $this->Cell($w[1], 7, $midterm, 0, 0, 'C', $fill);
                $this->Cell($w[2], 7, $endterm, 0, 0, 'C', $fill);
                $this->Cell($w[3], 7, $average, 0, 0, 'C', $fill);

                if ($average >= 80) {
                    $grade = 'A'; $gradeColor = [0, 128, 0]; // Green
                } elseif ($average >= 70) {
                    $grade = 'B'; $gradeColor = [0, 0, 255]; // Blue
                } elseif ($average >= 60) {
                    $grade = 'C'; $gradeColor = [255, 165, 0]; // Orange
                } elseif ($average >= 50) {
                    $grade = 'D'; $gradeColor = [255, 0, 0]; // Red
                } else {
                    $grade = 'F'; $gradeColor = [139, 0, 0]; // Dark Red
                }
                
                $this->SetTextColor($gradeColor[0], $gradeColor[1], $gradeColor[2]);
                $this->SetFont('Helvetica', 'B', 9);
                $this->Cell($w[4], 7, $grade, 0, 0, 'C', $fill);
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Helvetica', 'B', 9);
                
                if (is_numeric($originalPosition) && $originalPosition > 0) {
                    $this->Cell($w[5], 7, ordinal($originalPosition), 0, 0, 'C', $fill);
                } else {
                    $this->Cell($w[5], 7, 'N/A', 0, 0, 'C', $fill);
                }
                $this->Ln();
                $fill = !$fill;
            }

            // Grading Key - Compact version
            $this->Ln(5);
            $this->SetFont('Helvetica', 'B', 9);
            $this->Cell(0, 5, 'GRADING KEY: ', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->Cell(0, 5, 'A (80-100) | B (70-79) | C (60-69) | D (50-59) | E (40-49) | F (Below 40)', 0, 1, 'L');
            $this->Ln(5);

            // Psychomotor Skills Assessment - Modern table with checkboxes
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 7, 'PSYCHOMOTOR SKILLS ASSESSMENT', 0, 1, 'L');
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetFillColor(240, 240, 240);
            
            $skills = [
                'Handwriting' => ['Very Good', 'Good', 'Fair', 'Poor'],
                'Verbal Fluency' => ['Excellent', 'Good', 'Satisfactory', 'Needs Improvement'],
                'Games/Sports' => ['Excellent', 'Good', 'Fair', 'Poor'],
                'Handling Tools' => ['Skilled', 'Proficient', 'Developing', 'Beginner'],
                'Drawing/Painting' => ['Creative', 'Good', 'Basic', 'Needs Practice']
            ];
            
            $ratingOptions = ['5 - Excellent', '4 - Very Good', '3 - Good', '2 - Fair', '1 - Poor'];
            
            $this->Cell(60, 8, 'Skill/Activity', 1, 0, 'C', true);
            $this->Cell(0, 8, 'Rating', 1, 1, 'C', true);
            
            $this->SetFont('Helvetica', '', 9);
            $fill = false;
            
            foreach ($skills as $skill => $descriptions) {
                $this->SetFillColor($fill ? 245 : 255);
                $this->Cell(60, 8, $skill, 1, 0, 'L', $fill);
                
                // Create checkboxes for ratings
                $this->SetFont('ZapfDingbats', '', 10);
                for ($i = 5; $i >= 1; $i--) {
                    $this->Cell(6, 8, '', 1, 0, 'C', $fill); // Empty checkbox
                    $this->Cell(4, 8, '', 0, 0, 'C'); // Spacer
                }
                
                $this->SetFont('Helvetica', '', 7);
                $this->Cell(0, 8, '', 1, 1, 'L', $fill);
                $fill = !$fill;
            }
            
            // Add rating scale below the table
            $this->SetFont('Helvetica', 'I', 8);
            $this->Cell(0, 5, 'Rating Scale: 5 (Excellent) - 1 (Poor)', 0, 1, 'L');
            $this->Ln(5);

            // Affective Traits Assessment
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 7, 'AFFECTIVE TRAITS ASSESSMENT', 0, 1, 'L');
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetFillColor(240, 240, 240);
            
            $traits = [
                'Punctuality', 'Neatness', 'Politeness', 'Honesty', 
                'Cooperation', 'Leadership', 'Emotional Stability', 'Health'
            ];
            
            $this->Cell(60, 8, 'Trait', 1, 0, 'C', true);
            $this->Cell(0, 8, 'Rating', 1, 1, 'C', true);
            
            $this->SetFont('Helvetica', '', 9);
            $fill = false;
            
            foreach ($traits as $trait) {
                $this->SetFillColor($fill ? 245 : 255);
                $this->Cell(60, 8, $trait, 1, 0, 'L', $fill);
                
                // Create checkboxes for ratings
                $this->SetFont('ZapfDingbats', '', 10);
                for ($i = 5; $i >= 1; $i--) {
                    $this->Cell(6, 8, '', 1, 0, 'C', $fill); // Empty checkbox
                    $this->Cell(4, 8, '', 0, 0, 'C'); // Spacer
                }
                
                $this->SetFont('Helvetica', '', 7);
                $this->Cell(0, 8, '', 1, 1, 'L', $fill);
                $fill = !$fill;
            }
            
            // Add rating scale below the table
            $this->SetFont('Helvetica', 'I', 8);
            $this->Cell(0, 5, 'Rating Scale: 5 (Excellent) - 1 (Poor)', 0, 1, 'L');
            $this->Ln(5);

            // Attendance and Promotion Section - Compact
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, 'Attendance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(20, 6, '_____ / _____', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(30, 6, 'Next Term Begins:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(0, 6, '6th May, 2025', 0, 1, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, 'Promoted to:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(0, 6, '_________________________', 0, 1, 'L');
            $this->Ln(5);

            // Comments Section - Clean layout
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 7, 'TEACHER COMMENTS', 0, 1, 'L');
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', '', 10);
            $this->MultiCell(0, 6, $this->getRemarks(), 0, 'L');
            $this->Ln(3);
            
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 7, 'CONDUCT', 0, 1, 'L');
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', '', 10);
            $this->MultiCell(0, 6, getRandomConductRemark($conductRemarks), 0, 'L');
            $this->Ln(8);

            // Signature Line - Clean and professional
            $signatureWidth = 60;
            $this->SetFont('Helvetica', '', 10);
            $this->Cell($signatureWidth, 5, 'Class Teacher:', 0, 0, 'L');
            $this->Cell($signatureWidth, 5, '_________________________', 0, 0, 'L');
            $this->Cell(0, 5, 'Date: ___________', 0, 1, 'R');
            
            $this->Cell($signatureWidth, 5, 'Head Teacher:', 0, 0, 'L');
            $this->Cell($signatureWidth, 5, '_________________________', 0, 1, 'L');
            
            // Add new page for next student if not the last one
            if ($admno !== array_key_last($totalScores)) {
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
