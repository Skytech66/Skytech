<?php
ob_start();

function getRandomConductRemark($conductRemarks) {
    return empty($conductRemarks) ? 'No conduct remarks available.' : $conductRemarks[array_rand($conductRemarks)];
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
    private $primaryColor = [0, 56, 101];   // Dark blue
    private $secondaryColor = [241, 242, 246]; // Light gray
    private $accentColor = [255, 184, 28];  // Gold
    private $successColor = [40, 167, 69];  // Green
    private $borderColor = [220, 220, 220]; // Light border
    
    function header() {
        // School crest and header
        
        $this->SetY(10);
        $this->SetFont('Helvetica', 'B', 20);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 8, 'ADINKRA INTERNATIONAL SCHOOL', 0, 1, 'C');
        
        $this->SetFont('Helvetica', 'I', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Affiliated with Ghana Education Service | EST. 2005', 0, 1, 'C');
        
        // Main report title
        $this->SetY(30);
        $this->SetFont('Helvetica', 'B', 16);
        $this->SetTextColor(0, 0, 0);
        $this->Cell(0, 8, 'End of Term Report', 0, 1, 'C');
        
        // Decorative line
        $this->SetDrawColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
        $this->SetLineWidth(0.8);
        $this->Line(10, $this->GetY() + 2, 200, $this->GetY() + 2);
        $this->Ln(12);
    }

    function footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 10, 'Property of Adinkra International School', 0, 0, 'L');
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'R');
    }

    function getRemarks() {
        $remarks = [
            "Demonstrates consistent academic growth with particular strength in analytical thinking.",
            "Shows commendable dedication to studies; continued practice will yield even better results.",
            "A conscientious learner who approaches tasks with thoroughness and care.",
            // ... (other remarks)
        ];
        return $remarks[array_rand($remarks)];
    }

    function drawCheckbox($checked = false, $size = 3) {
        $this->SetLineWidth(0.2);
        $this->SetDrawColor(100, 100, 100);
        $this->Rect($this->GetX(), $this->GetY(), $size, $size);
        if ($checked) {
            $this->Line($this->GetX(), $this->GetY(), $this->GetX() + $size, $this->GetY() + $size);
            $this->Line($this->GetX(), $this->GetY() + $size, $this->GetX() + $size, $this->GetY());
        }
        $this->SetX($this->GetX() + $size + 1);
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        $conductRemarks = [
            "Exhibits exemplary conduct and serves as a role model to peers.",
            // ... (other conduct remarks)
        ];

        $sql = "SELECT name, admno, photo FROM student WHERE class LIKE '%$class%' ORDER BY admno ASC";
        $ret = $conn->query($sql);

        $subjectOrder = ['English', 'Mathematics', 'Science', 'Social Studies', 'ICT', 'French', 'Ghanaian Language', 'RME', 'Creative Arts'];

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
                $totalScore += $row["average"];
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
            // Student Information Block
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 30, 3, 'F');
            
            // Student Photo
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 12, $this->GetY() + 5, 20, 20);
            } else {
                $this->SetFillColor(220, 220, 220);
                $this->Rect(12, $this->GetY() + 5, 20, 20, 'F');
            }

            // Student Details
            $this->SetXY(35, $this->GetY() + 5);
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(50, 7, 'Student:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(100, 7, $data['student'], 0, 1, 'L');
            
            $this->SetX(35);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(25, 7, 'Class:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(40, 7, $class, 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(25, 7, 'Admission No:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(40, 7, $admno, 0, 1, 'L');
            
            $this->SetX(35);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(25, 7, 'Term:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(40, 7, $exam, 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(25, 7, 'Date:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(40, 7, date('F j, Y'), 0, 1, 'L');
            
            $this->Ln(15);

            // Academic Performance Table
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            
            $header = ['Subject', 'Class Work', 'Exam', 'Total', 'Grade', 'Position'];
            $w = [40, 25, 25, 25, 20, 25];
            
            for($i=0; $i<count($header); $i++)
                $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
            $this->Ln();
            
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', '', 8);
            
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
                $position = $row["position"];

                $this->Cell($w[0], 6, $subject, 'LRB', 0, 'L');
                $this->Cell($w[1], 6, $midterm, 'RB', 0, 'C');
                $this->Cell($w[2], 6, $endterm, 'RB', 0, 'C');
                $this->Cell($w[3], 6, $average, 'RB', 0, 'C');

                // Grade calculation
                if ($average >= 80) $grade = 'A';
                elseif ($average >= 70) $grade = 'B';
                elseif ($average >= 60) $grade = 'C';
                elseif ($average >= 50) $grade = 'D';
                elseif ($average >= 40) $grade = 'E';
                else $grade = 'F';
                
                $this->SetFont('Helvetica', 'B', 8);
                $this->Cell($w[4], 6, $grade, 'RB', 0, 'C');
                $this->SetFont('Helvetica', '', 8);
                
                if (is_numeric($position) && $position > 0) {
                    $this->Cell($w[5], 6, ordinal($position), 'RB', 0, 'C');
                } else {
                    $this->Cell($w[5], 6, 'N/A', 'RB', 0, 'C');
                }
                $this->Ln();
            }

            // Grading Key
            $this->Ln(5);
            $this->SetFont('Helvetica', 'B', 8);
            $this->Cell(0, 5, 'GRADING SCALE: A (90-100) = Outstanding | B+ (80-89) = Excellent | B (70-79) = Very Good | C+ (60-69) = Good | C (50-59) = Satisfactory | D (40-49) = Needs Improvement | E (Below 40) = Unsatisfactory', 0, 1, 'C');
            $this->Ln(5);

            // Student Development Section
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(95, 7, 'AFFECTIVE TRAITS ASSESSMENT', 0, 0, 'L');
            $this->Cell(95, 7, 'PSYCHOMOTOR SKILLS EVALUATION', 0, 1, 'L');
            
            // Affective Traits Table (left)
            $this->SetFont('Helvetica', '', 7);
            $this->Cell(95, 5, '', 0, 0, 'L');
            $this->Cell(95, 5, '', 0, 1, 'L');
            
            $traits = [
                'Punctuality' => ['Always on time', 'Usually punctual', 'Occasionally late', 'Frequently late'],
                'Mental Alertness' => ['Exceptional', 'Above average', 'Average', 'Needs improvement'],
                'Peer Relationship' => ['Excellent', 'Good', 'Satisfactory', 'Needs work'],
                'Responsibility' => ['Highly responsible', 'Generally responsible', 'Sometimes needs reminders', 'Irresponsible'],
                'Initiative' => ['Takes consistent initiative', 'Shows initiative often', 'Occasionally shows initiative', 'Rarely shows initiative']
            ];
            
            $this->SetWidths([40, 15, 15, 15, 15]);
            $this->SetAligns(['L', 'C', 'C', 'C', 'C']);
            
            // Header row
            $this->SetFont('Helvetica', 'B', 7);
            $this->Cell(40, 6, 'Trait', 1, 0, 'C');
            $this->Cell(15, 6, '1', 1, 0, 'C');
            $this->Cell(15, 6, '2', 1, 0, 'C');
            $this->Cell(15, 6, '3', 1, 0, 'C');
            $this->Cell(15, 6, '4', 1, 1, 'C');
            
            // Content rows
            $this->SetFont('Helvetica', '', 7);
            foreach ($traits as $trait => $levels) {
                $this->Cell(40, 6, $trait, 1, 0, 'L');
                for ($i = 1; $i <= 4; $i++) {
                    $this->Cell(15, 6, '', 1, 0, 'C');
                    $this->drawCheckbox(false, 2);
                }
                $this->Ln();
            }
            
            // Psychomotor Skills Table (right)
            $this->SetXY(105, $this->GetY() - (count($traits) * 6));
            $skills = [
                'Handwriting' => ['Excellent', 'Good', 'Fair', 'Poor'],
                'Artistic Ability' => ['Exceptional', 'Skilled', 'Developing', 'Basic'],
                'Sports Performance' => ['Outstanding', 'Strong', 'Average', 'Needs practice'],
                'Practical Skills' => ['Highly proficient', 'Competent', 'Developing', 'Beginning'],
                'Coordination' => ['Excellent', 'Good', 'Developing', 'Needs work']
            ];
            
            $this->SetWidths([40, 15, 15, 15, 15]);
            $this->SetAligns(['L', 'C', 'C', 'C', 'C']);
            
            // Header row
            $this->SetFont('Helvetica', 'B', 7);
            $this->Cell(40, 6, 'Skill', 1, 0, 'C');
            $this->Cell(15, 6, '1', 1, 0, 'C');
            $this->Cell(15, 6, '2', 1, 0, 'C');
            $this->Cell(15, 6, '3', 1, 0, 'C');
            $this->Cell(15, 6, '4', 1, 1, 'C');
            
            // Content rows
            $this->SetFont('Helvetica', '', 7);
            foreach ($skills as $skill => $levels) {
                $this->Cell(40, 6, $skill, 1, 0, 'L');
                for ($i = 1; $i <= 4; $i++) {
                    $this->Cell(15, 6, '', 1, 0, 'C');
                    $this->drawCheckbox(false, 2);
                }
                $this->Ln();
            }
            
            $this->Ln(3);
            
            // Rating Key
            $this->SetFont('Helvetica', 'I', 6);
            $this->Cell(0, 4, 'Rating Scale: 1 = Excellent/Outstanding | 2 = Good/Strong | 3 = Satisfactory/Developing | 4 = Needs Improvement', 0, 1, 'C');
            $this->Ln(5);

            // Attendance and Promotion
            $this->SetFont('Helvetica', 'B', 9);
            $this->Cell(30, 6, 'Attendance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->Cell(20, 6, '_____ days', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 9);
            $this->Cell(30, 6, 'Total Possible:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->Cell(20, 6, '_____ days', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 9);
            $this->Cell(30, 6, 'Promotion Status:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->Cell(20, 6, '_____', 0, 1, 'L');
            $this->Ln(5);

            // Teacher Comments
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 7, 'Teacher Assessment:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->MultiCell(0, 6, $this->getRemarks(), 0, 'L');
            $this->Ln(3);
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 7, 'Conduct Evaluation:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->MultiCell(0, 6, getRandomConductRemark($conductRemarks), 0, 'L');
            $this->Ln(5);

            // Recommendations
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(0, 7, 'Areas for Development:', 0, 1, 'L');
            $this->SetFont('Helvetica', '', 9);
            $this->MultiCell(0, 6, "1. Focus on improving time management skills\n2. Develop more consistent study habits\n3. Participate more actively in class discussions", 0, 'L');
            $this->Ln(5);

            // Signature Block
            $this->SetFont('Helvetica', 'B', 9);
            $this->Cell(60, 5, 'Class Teacher:', 0, 0, 'L');
            $this->Cell(60, 5, 'Head of Department:', 0, 0, 'L');
            $this->Cell(60, 5, 'Head Teacher:', 0, 1, 'L');
            
            $this->SetFont('Helvetica', '', 8);
            $this->Cell(60, 15, '_________________________', 0, 0, 'L');
            $this->Cell(60, 15, '_________________________', 0, 0, 'L');
            $this->Cell(60, 15, '_________________________', 0, 1, 'L');
            
            $this->Cell(60, 5, 'Date: _____/_____/_____', 0, 0, 'L');
            $this->Cell(60, 5, 'Date: _____/_____/_____', 0, 0, 'L');
            $this->Cell(60, 5, 'Date: _____/_____/_____', 0, 1, 'L');
            
            $this->Ln(10);
            
            // Confidential Stamp
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetTextColor(255, 0, 0);
            $this->Cell(0, 10, 'OFFICIAL STUDENT REPORT', 0, 1, 'C');
            $this->SetTextColor(0, 0, 0);
            
            // Add new page for next student
            $this->AddPage();
        }
    }
    
    // Helper functions for tables
    function SetWidths($w) {
        $this->widths = $w;
    }
    
    function SetAligns($a) {
        $this->aligns = $a;
    }
    
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F') $op='f';
        elseif($style=='FD' || $style=='DF') $op='B';
        else $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r;
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
