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
    private $primaryColor = [13, 36, 68];    // Navy blue
    private $secondaryColor = [241, 243, 245]; // Light gray
    private $accentColor = [255, 195, 0];   // Gold
    private $successColor = [40, 167, 69];  // Green
    private $warningColor = [255, 193, 7];  // Yellow
    private $dangerColor = [220, 53, 69];   // Red
    private $infoColor = [23, 162, 184];    // Teal
    
    // Method to draw a circle (needed because FPDF has no Circle method)
    function Circle($x, $y, $r, $style = '') {
        // Approximate circle using 4 Bézier curves
        $this->Ellipse($x, $y, $r, $r, $style);
    }

    // Helper function to draw ellipse (used by Circle)
    function Ellipse($x, $y, $rx, $ry, $style = '') {
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $lx = 4/3*(M_SQRT2-1)*$rx;
        $ly = 4/3*(M_SQRT2-1)*$ry;

        $k = $this->k;
        $h = $this->h;

        $this->_out(sprintf('%.2F %.2F m', ($x+$rx)*$k, ($h-($y))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k, ($h-($y-$ly))*$k,
            ($x+$lx)*$k, ($h-($y-$ry))*$k,
            $x*$k, ($h-($y-$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k, ($h-($y-$ry))*$k,
            ($x-$rx)*$k, ($h-($y-$ly))*$k,
            ($x-$rx)*$k, ($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k, ($h-($y+$ly))*$k,
            ($x-$lx)*$k, ($h-($y+$ry))*$k,
            $x*$k, ($h-($y+$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$lx)*$k, ($h-($y+$ry))*$k,
            ($x+$rx)*$k, ($h-($y+$ly))*$k,
            ($x+$rx)*$k, ($h-$y)*$k));
        $this->_out($op);
    }
    
    // Add progress circle drawing function
    function drawProgressCircle($x, $y, $radius, $percent, $label) {
        // Circle background
        $this->SetFillColor(230, 230, 230);
        $this->Circle($x, $y, $radius, 'F');
        
        // Calculate arc for progress
        $startAngle = 0;
        $endAngle = ($percent / 100) * 360;
        
        // Progress arc with color based on percentage
        if ($percent >= 80) {
            $this->SetFillColor($this->successColor[0], $this->successColor[1], $this->successColor[2]);
        } elseif ($percent >= 60) {
            $this->SetFillColor($this->infoColor[0], $this->infoColor[1], $this->infoColor[2]);
        } else {
            $this->SetFillColor($this->warningColor[0], $this->warningColor[1], $this->warningColor[2]);
        }
        
        $this->Sector($x, $y, $radius, $startAngle, $endAngle, 'F', false);
        
        // Outer circle border
        $this->SetDrawColor(200, 200, 200);
        $this->Circle($x, $y, $radius);
        
        // Percentage text
        $this->SetFont('Helvetica', 'B', 9);
        $this->SetTextColor(0, 0, 0);
        $this->SetXY($x - $radius, $y - 3);
        $this->Cell($radius * 2, 6, $percent . '%', 0, 0, 'C');
        
        // Skill label
        $this->SetFont('Helvetica', '', 8);
        $this->SetTextColor(100, 100, 100);
        $this->SetXY($x - $radius, $y + $radius - 2);
        $this->Cell($radius * 2, 5, $label, 0, 0, 'C');
    }
    
    function header() {
        // School header with modern typography
        $this->SetY(10);
        
        // Decorative top bar
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, 0, 210, 8, 'F');
        
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
        $this->SetLineWidth(0.8);
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
            "Exceptional performance across all subjects. Demonstrates outstanding academic ability and commitment to learning. Shows excellent critical thinking skills and creativity in problem-solving.",
            "Consistent high achiever with excellent understanding of concepts. Maintains strong work ethic. Works well collaboratively and communicates ideas effectively.",
            "Good overall performance with noticeable strengths in several subjects. Shows steady progress. Demonstrates good organizational skills and applies knowledge effectively.",
            "Making satisfactory progress. Would benefit from more consistent effort and focus. Shows potential with additional support in key areas.",
            "Needs to apply more effort to reach full potential. Additional practice at home recommended. Would benefit from targeted support in core subjects."
        ];
        
        if ($averageScore >= 85) return $remarks[0];
        if ($averageScore >= 75) return $remarks[1];
        if ($averageScore >= 60) return $remarks[2];
        if ($averageScore >= 50) return $remarks[3];
        return $remarks[4];
    }

    function getConductAssessment($averageScore) {
        $conducts = [
            "Exemplary behavior. Consistently demonstrates respect, responsibility, and leadership. An excellent role model for peers.",
            "Very good conduct. Polite, cooperative, and follows classroom rules. Shows good collaboration skills with classmates.",
            "Generally well-behaved with occasional reminders needed. Communicates well with teachers and peers.",
            "Behavior needs improvement. Sometimes disruptive to learning environment. Requires guidance to stay organized.",
            "Frequent behavior issues that interfere with learning. Needs to develop better self-regulation skills."
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
            // Report Title with modern styling
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 14);
            $this->Cell(0, 10, 'ACADEMIC PERFORMANCE REPORT', 0, 1, 'C', true);
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, $exam . ' Terminal Assessment | ' . date('F j, Y'), 0, 1, 'C');
            $this->Ln(5);

            // Student Information Box with modern styling
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 25, 3, 'F');
            $this->SetDrawColor(200, 200, 200);
            $this->RoundedRect(10, $this->GetY(), 190, 25, 3, 'D');
            
            // Student Photo with shadow effect
            $photoX = 15;
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                // Add shadow effect
                $this->SetFillColor(200, 200, 200);
                $this->RoundedRect($photoX+1, $this->GetY() + 4, 18, 18, 2, 'F');
                $this->Image($data['photo'], $photoX, $this->GetY() + 3, 18, 18, 'JPG', '', 'L', false, 300, '', false, false, 0, false, false, false);
            } else {
                $this->SetFillColor(220, 220, 220);
                $this->RoundedRect($photoX, $this->GetY() + 3, 18, 18, 2, 'F');
                $this->SetFont('Helvetica', 'I', 8);
                $this->SetTextColor(150, 150, 150);
                $this->SetXY($photoX, $this->GetY() + 10);
                $this->Cell(18, 5, 'No Photo', 0, 0, 'C');
            }

            // Student Details - Organized in two rows with better spacing
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

            // Academic Performance Table with modern styling
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

            $fill = false;
            foreach ($subjects as $row) {
                $subject = $row["subject"];
                $midterm = $row["midterm"];
                $endterm = $row["endterm"];
                $average = $row["average"];
                $originalPosition = $row["position"];

                // Alternate row colors for better readability
                if ($fill) {
                    $this->SetFillColor(245, 245, 245);
                } else {
                    $this->SetFillColor(255, 255, 255);
                }
                $fill = !$fill;
                
                $this->Cell($w[0], 6, $subject, 'LRB', 0, 'L', true);
                $this->Cell($w[1], 6, $midterm, 'RB', 0, 'C', true);
                $this->Cell($w[2], 6, $endterm, 'RB', 0, 'C', true);
                $this->Cell($w[3], 6, $average, 'RB', 0, 'C', true);

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
                $this->Cell($w[4], 6, $grade, 'RB', 0, 'C', true);
                $this->SetTextColor(0, 0, 0);
                
                // Position
                $this->SetFont('Helvetica', 'B', 9);
                if (is_numeric($originalPosition) && $originalPosition > 0) {
                    $this->Cell($w[5], 6, ordinal($originalPosition), 'RB', 0, 'C', true);
                } else {
                    $this->Cell($w[5], 6, 'N/A', 'RB', 0, 'C', true);
                }
                $this->Ln();
            }

            // Overall Performance Summary
            $this->Ln(5);
            $this->SetFillColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(95, 8, 'OVERALL PERFORMANCE', 1, 0, 'C', true);
            
            $this->SetFillColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(95, 8, 'Average Score: ' . number_format($data['average'], 1) . '%', 1, 1, 'C', true);
            
            // Skills Assessment Section
            $this->Ln(5);
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'SKILLS ASSESSMENT', 0, 1, 'L');
            
            $skills = [
                'Critical Thinking' => rand(60, 100),
                'Creativity' => rand(60, 100),
                'Collaboration' => rand(60, 100),
                'Communication' => rand(60, 100),
                'Organization' => rand(60, 100)
            ];
            
            $x = 30;
            $y = $this->GetY();
            $radius = 15;
            
            foreach ($skills as $skill => $score) {
                $this->drawProgressCircle($x, $y + 15, $radius, $score, $skill);
                $x += 38;
            }
            
            $this->SetY($y + 40);
            $this->Ln(5);

            // Grading Key with modern styling
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 10, 3, 'F');
            $this->SetDrawColor(200, 200, 200);
            $this->RoundedRect(10, $this->GetY(), 190, 10, 3, 'D');
            
            $this->SetFont('Helvetica', 'B', 9);
            $this->SetXY(10, $this->GetY() + 3);
            $this->Cell(190, 5, 'GRADING KEY', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 8);
            $this->SetXY(10, $this->GetY() + 3);
            
            // Color-coded grading key
            $this->SetFillColor($this->successColor[0], $this->successColor[1], $this->successColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->Cell(38, 5, 'A (80-100) = Excellent', 0, 0, 'C', true);
            
            $this->SetFillColor(65, 131, 215);
            $this->Cell(38, 5, 'B (70-79) = Very Good', 0, 0, 'C', true);
            
            $this->SetFillColor(108, 117, 125);
            $this->Cell(38, 5, 'C (60-69) = Good', 0, 0, 'C', true);
            
            $this->SetFillColor($this->warningColor[0], $this->warningColor[1], $this->warningColor[2]);
            $this->Cell(38, 5, 'D (50-59) = Average', 0, 0, 'C', true);
            
            $this->SetFillColor($this->dangerColor[0], $this->dangerColor[1], $this->dangerColor[2]);
            $this->Cell(38, 5, 'E (Below 50) = Needs Improvement', 0, 1, 'C', true);
            $this->SetTextColor(0, 0, 0);
            $this->Ln(5);

            // Attendance and Promotion with better layout
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 15, 3, 'F');
            $this->SetDrawColor(200, 200, 200);
            $this->RoundedRect(10, $this->GetY(), 190, 15, 3, 'D');
            
            $this->SetXY(15, $this->GetY() + 4);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, 'Attendance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(50, 6, '______ out of ______', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(40, 6, 'Promoted to:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(0, 6, '_________________________', 0, 1, 'L');
            
            $this->SetXY(15, $this->GetY() + 2);
            $this->SetFont('Helvetica', 'I', 8);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Next term begins: 5th May, 2025', 0, 1, 'L');
            $this->SetTextColor(0, 0, 0);
            $this->Ln(3);

            // Teacher Comments with modern styling
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 7, 'TEACHER COMMENTS', 0, 1, 'L');
            
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 40, 3, 'F');
            $this->SetDrawColor(200, 200, 200);
            $this->RoundedRect(10, $this->GetY(), 190, 40, 3, 'D');
            
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

            // Signatures with decorative lines
            $this->SetFont('Helvetica', '', 10);
            $this->Cell(95, 6, 'Class Teacher: _________________________', 0, 0, 'L');
            $this->Cell(95, 6, 'Date: _______________', 0, 1, 'R');
            
            $this->Cell(95, 6, 'Head Teacher: _________________________', 0, 0, 'L');
            $this->Cell(95, 6, 'Date: _______________', 0, 1, 'R');
            
            $this->Ln(5);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(0, 6, 'Parent/Guardian Signature: _________________________', 0, 1, 'L');
            $this->SetFont('Helvetica', 'I', 8);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Please review this report with your child and return the signed copy', 0, 1, 'L');
            
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
    
    // Add sector drawing function for progress circles
    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90) {
        $d0 = $a - $b;
        if ($cw) {
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        } else {
            $b += $o;
            $a += $o;
        }
        while($a<0)
            $a += 360;
        while($a>360)
            $a -= 360;
        while($b<0)
            $b += 360;
        while($b>360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $a + ($b - $a);
        $k = $this->k;
        $hp = $this->h;
        if ($style=='F')
            $op='f';
        elseif ($style=='FD' || $style=='DF')
            $op='b';
        else
            $op='s';
        if (sin(deg2rad($b-$a))) {
            $MyArc = 4/3*(1-cos(deg2rad($b-$a)/2)/sin(deg2rad($b-$a)/2));
        } else {
            $MyArc = 0;
        }
        // first put the center
        $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
        // put the first point
        $this->_out(sprintf('%.2F %.2F l',($xc+$r*cos(deg2rad($a)))*$k,(($hp-($yc-$r*sin(deg2rad($a)))))*$k));
        // draw the arc
        if ($d0<180) {
            // arc less than 180 degrees
            $this->_Arc($xc+$r*cos(deg2rad($a))+$r*$MyArc*cos(deg2rad(90+$a)),
                        $yc-$r*sin(deg2rad($a))-$r*$MyArc*sin(deg2rad(90+$a)),
                        $xc+$r*cos(deg2rad($b))+$r*$MyArc*cos(deg2rad($b-90)),
                        $yc-$r*sin(deg2rad($b))-$r*$MyArc*sin(deg2rad($b-90)),
                        $xc+$r*cos(deg2rad($b)),
                        $yc-$r*sin(deg2rad($b)));
        } else {
            // arc more than 180 degrees
            $this->_Arc($xc+$r*cos(deg2rad($a))+$r*$MyArc*cos(deg2rad(90+$a)),
                        $yc-$r*sin(deg2rad($a))-$r*$MyArc*sin(deg2rad(90+$a)),
                        $xc+$r*cos(deg2rad($b))+$r*$MyArc*cos(deg2rad($b-90)),
                        $yc-$r*sin(deg2rad($b))-$r*$MyArc*sin(deg2rad($b-90)),
                        $xc+$r*cos(deg2rad($b)),
                        $yc-$r*sin(deg2rad($b)));
        }
        // terminate drawing
        $this->_out($op);
    }
}

$pdf = new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->headertable();
$pdf->Output();
ob_end_flush();
?>

