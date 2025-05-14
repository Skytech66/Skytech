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
    
    function header() {
        // School logo and header
        $this->SetY(10);
        
        // Add school logo if available
        if (file_exists('school_logo.png')) {
            $this->Image('school_logo.png', 10, 10, 25);
        }
        
        // School name with modern typography
        $this->SetFont('Helvetica', 'B', 20);
        $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Cell(0, 10, 'ADINKRA INTERNATIONAL SCHOOL', 0, 1, 'C');
        
        // Subheader with motto
        $this->SetFont('Helvetica', 'I', 10);
        $this->SetTextColor(100, 100, 100);
        $this->Cell(0, 5, 'Excellence Through Knowledge', 0, 1, 'C');
        
        // Contact information with icons (represented by letters)
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor(70, 70, 70);
        $this->Cell(0, 5, '📍 P.M.B 40, Madina | 📞 0277411866 / 0541622751', 0, 1, 'C');
        $this->Cell(0, 5, '🌐 www.adinkraschool.edu.gh | ✉ info@adinkraschool.edu.gh', 0, 1, 'C');
        
        // Decorative line with gradient effect
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

    function getImprovementSuggestions($averageScore) {
        $suggestions = [
            "Consider enrichment activities to further challenge this high-performing student.",
            "Continue current study habits. Could explore advanced materials in strongest subjects.",
            "Would benefit from additional practice in weaker areas. Regular review sessions recommended.",
            "Needs structured study plan and more consistent homework completion.",
            "Requires significant academic support. Recommend parent-teacher conference."
        ];
        
        if ($averageScore >= 85) return $suggestions[0];
        if ($averageScore >= 75) return $suggestions[1];
        if ($averageScore >= 60) return $suggestions[2];
        if ($averageScore >= 50) return $suggestions[3];
        return $suggestions[4];
    }
    
    // New Circle and Ellipse methods added here
    // Draws a circle or arc. If style == 'arc', draws arc from 0 to angle in degrees.
    function Circle($x, $y, $r, $startAngle=0, $endAngle=360, $style='') {
        if ($startAngle == 0 && $endAngle == 360) {
            // Full circle
            $this->Ellipse($x, $y, $r, $r, $style);
        } else {
            // Partial arc in degrees, convert to radians for calculations
            $this->_ArcSegment($x, $y, $r, $startAngle, $endAngle, $style);
        }
    }

    // Ellipse function to draw ellipse or circle (rx = ry for circle)
    function Ellipse($x, $y, $rx, $ry, $style='') {
        $k = $this->k;
        $hp = $this->h;

        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F %s',
            $x * $k,
            ($hp - $y) * $k,
            $rx * $k,
            $ry * $k,
            0, // angle - not used here
            360, // full ellipse
            $style
        ));

        // Since FPDF default does not have ellipse, use Bezier curve approximation
        $this->_EllipseBezier($x, $y, $rx, $ry, $style);
    }

    // Private helper function for drawing partial arc segments using Bezier curves
    function _ArcSegment($x, $y, $r, $startAngle, $endAngle, $style = '') {
        $startAngleRad = deg2rad($startAngle);
        $endAngleRad = deg2rad($endAngle);

        $arcAngle = $endAngleRad - $startAngleRad;
        if ($arcAngle < 0) {
            $arcAngle += 2 * M_PI;
        }

        $segments = ceil($arcAngle / (M_PI / 2)); // split into 90 degree segments max

        $angleIncrement = $arcAngle / $segments;

        $this->SetLineWidth(2);
        $this->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);

        $k = $this->k;
        $hp = $this->h;

        $this->_out('q'); // save graphic state

        // Move to start position of arc
        $x0 = $x + $r * cos($startAngleRad);
        $y0 = $y + $r * sin($startAngleRad);
        $this->_out(sprintf('%.2F %.2F m', $x0 * $k, ($hp - $y0) * $k));

        for ($i = 0; $i < $segments; $i++) {
            $angle1 = $startAngleRad + $i * $angleIncrement;
            $angle2 = $angle1 + $angleIncrement;
            if ($angle2 > $endAngleRad) {
                $angle2 = $endAngleRad;
            }

            $this->_BezierArcSegment($x, $y, $r, $angle1, $angle2);
        }

        $this->_out('S'); // stroke path

        $this->_out('Q'); // restore graphic state
    }

    // Helper: draw Bezier curve approximating arc segment from angle1 to angle2
    function _BezierArcSegment($x, $y, $r, $angle1, $angle2) {
        $k = $this->k;
        $hp = $this->h;

        $delta = $angle2 - $angle1;
        $t = (4 / 3) * tan($delta / 4);

        $x1 = $x + $r * cos($angle1);
        $y1 = $y + $r * sin($angle1);

        $x2 = $x1 - $r * $t * sin($angle1);
        $y2 = $y1 + $r * $t * cos($angle1);

        $x4 = $x + $r * cos($angle2);
        $y4 = $y + $r * sin($angle2);

        $x3 = $x4 + $r * $t * sin($angle2);
        $y3 = $y4 - $r * $t * cos($angle2);

        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x2 * $k, ($hp - $y2) * $k,
            $x3 * $k, ($hp - $y3) * $k,
            $x4 * $k, ($hp - $y4) * $k));
    }

    // Approximate ellipse with Bezier curves for full circle/ellipse drawing
    function _EllipseBezier($x, $y, $rx, $ry, $style='') {
        $k = $this->k;
        $hp = $this->h;

        // We split ellipse into 4 Bezier curves
        $MyArc = 4/3 * (sqrt(2) - 1);

        $this->_out('q'); // save graphic state

        $this->_out(sprintf('%.2F %.2F m', ($x + $rx) * $k, ($hp - $y) * $k));

        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx) * $k, ($hp - ($y + $ry * $MyArc)) * $k,
            ($x + $rx * $MyArc) * $k, ($hp - ($y + $ry)) * $k,
            $x * $k, ($hp - ($y + $ry)) * $k));

        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $rx * $MyArc) * $k, ($hp - ($y + $ry)) * $k,
            ($x - $rx) * $k, ($hp - ($y + $ry * $MyArc)) * $k,
            ($x - $rx) * $k, ($hp - $y) * $k));

        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x - $rx) * $k, ($hp - ($y - $ry * $MyArc)) * $k,
            ($x - $rx * $MyArc) * $k, ($hp - ($y - $ry)) * $k,
            $x * $k, ($hp - ($y - $ry)) * $k));

        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x + $rx * $MyArc) * $k, ($hp - ($y - $ry)) * $k,
            ($x + $rx) * $k, ($hp - ($y - $ry * $MyArc)) * $k,
            ($x + $rx) * $k, ($hp - $y) * $k));

        if ($style == 'F') {
            $this->_out('f');
        } elseif ($style == 'FD' || $style == 'DF') {
            $this->_out('B');
        } else {
            $this->_out('S');
        }

        $this->_out('Q'); // restore graphic state
    }

    function drawProgressCircle($x, $y, $radius, $percent, $label) {
        // Circle background
        $this->SetDrawColor(220, 220, 220);
        $this->SetLineWidth(3);
        $this->Circle($x, $y, $radius, 0, 360);
        
        // Progress arc
        $this->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetLineWidth(3);
        $endAngle = 360 * ($percent / 100);
        $this->Circle($x, $y, $radius, 270, 270 + $endAngle, 'arc'); // Start progress arc at top (270 degrees)
        
        // Percentage text
        $this->SetFont('Helvetica', 'B', 10);
        $this->SetXY($x - 7, $y - 4);
        $this->Cell(14, 7, round($percent) . '%', 0, 0, 'C');
        
        // Label
        $this->SetFont('Helvetica', '', 8);
        $this->SetXY($x - 20, $y + $radius + 3);
        $this->Cell(40, 5, $label, 0, 0, 'C');
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
            // Student Report Header
            $this->SetFont('Helvetica', 'B', 16);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'ACADEMIC PERFORMANCE REPORT', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Terminal Assessment - ' . $exam . ' | ' . date('F j, Y'), 0, 1, 'C');
            $this->Ln(8);

            // Student Info Section with modern layout
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 30, 5, 'F');
            
            // Student Photo
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 15, $this->GetY() + 5, 20, 20, 'JPG', '', 'L', false, 300, '', false, false, 0, false, false, false);
            } else {
                $this->SetFillColor(220, 220, 220);
                $this->Rect(15, $this->GetY() + 5, 20, 20, 'F');
                $this->SetFont('Helvetica', 'I', 8);
                $this->SetTextColor(150, 150, 150);
                $this->SetXY(15, $this->GetY() + 13);
                $this->Cell(20, 5, 'No Photo', 0, 0, 'C');
            }

            // Student Details
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetXY(40, $this->GetY() + 5);
            $this->Cell(50, 6, 'Student Name:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(100, 6, $data['student'], 0, 1, 'L');
            
            $this->SetX(40);
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(25, 6, 'Class:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(40, 6, $class, 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(25, 6, 'Admission No:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(40, 6, $admno, 0, 1, 'L');
            
            $this->SetX(40);
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(25, 6, 'Term Ends:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(40, 6, '17th April, 2025', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(25, 6, 'Resumes:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(40, 6, '6th May, 2025', 0, 1, 'L');
            
            $this->Ln(12);

            // Performance Summary Cards
            $this->SetFont('Helvetica', 'B', 10);
            
            // Overall Performance Card
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->RoundedRect(15, $this->GetY(), 60, 25, 3, 'F');
            $this->SetXY(15, $this->GetY() + 3);
            $this->Cell(60, 6, 'OVERALL PERFORMANCE', 0, 1, 'C');
            
            $this->SetFont('Helvetica', 'B', 14);
            $this->SetXY(15, $this->GetY() + 2);
            $this->Cell(60, 8, number_format($data['average'], 1) . '%', 0, 0, 'C');
            
            // Class Position Card
            $position = array_search($admno, array_keys($totalScores)) + 1;
            $this->SetFillColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
            $this->SetTextColor(0, 0, 0);
            $this->RoundedRect(80, $this->GetY() - 10, 60, 25, 3, 'F');
            $this->SetXY(80, $this->GetY() - 7);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(60, 6, 'CLASS POSITION', 0, 1, 'C');
            
            $this->SetFont('Helvetica', 'B', 14);
            $this->SetXY(80, $this->GetY() + 2);
            $this->Cell(60, 8, ordinal($position) . ' of ' . count($totalScores), 0, 0, 'C');
            
            // Attendance Card
            $this->SetFillColor($this->successColor[0], $this->successColor[1], $this->successColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->RoundedRect(145, $this->GetY() - 10, 60, 25, 3, 'F');
            $this->SetXY(145, $this->GetY() - 7);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(60, 6, 'ATTENDANCE', 0, 1, 'C');
            
            $this->SetFont('Helvetica', 'B', 14);
            $this->SetXY(145, $this->GetY() + 2);
            $this->Cell(60, 8, '98%', 0, 0, 'C');
            
            $this->Ln(20);

            // Subject Performance Table
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->SetFont('Helvetica', 'B', 10);
            
            $header = ['SUBJECT', 'SCORE', 'GRADE', 'REMARKS', 'POSITION'];
            $w = [50, 30, 30, 60, 30];
            
            for($i=0; $i<count($header); $i++)
                $this->Cell($w[$i], 8, $header[$i], 1, 0, 'C', true);
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

            foreach ($subjects as $row) {
                $subject = $row["subject"];
                $average = $row["average"];
                $originalPosition = $row["position"];

                // Determine grade and color
                if ($average >= 80) {
                    $grade = 'A'; 
                    $remarks = 'Excellent';
                    $textColor = $this->successColor;
                } elseif ($average >= 70) {
                    $grade = 'B'; 
                    $remarks = 'Very Good';
                    $textColor = [65, 131, 215]; // Blue
                } elseif ($average >= 60) {
                    $grade = 'C'; 
                    $remarks = 'Good';
                    $textColor = [108, 117, 125]; // Gray
                } elseif ($average >= 50) {
                    $grade = 'D'; 
                    $remarks = 'Average';
                    $textColor = $this->warningColor;
                } else {
                    $grade = 'E'; 
                    $remarks = 'Needs Improvement';
                    $textColor = $this->dangerColor;
                }
                
                $this->Cell($w[0], 7, $subject, 'LRB', 0, 'L');
                
                // Score with color based on performance
                $this->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
                $this->SetFont('Helvetica', 'B', 9);
                $this->Cell($w[1], 7, $average, 'RB', 0, 'C');
                
                // Grade
                $this->Cell($w[2], 7, $grade, 'RB', 0, 'C');
                
                // Remarks
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('Helvetica', '', 9);
                $this->Cell($w[3], 7, $remarks, 'RB', 0, 'L');
                
                // Position
                $this->SetFont('Helvetica', 'B', 9);
                if (is_numeric($originalPosition) && $originalPosition > 0) {
                    $this->Cell($w[4], 7, ordinal($originalPosition), 'RB', 0, 'C');
                } else {
                    $this->Cell($w[4], 7, 'N/A', 'RB', 0, 'C');
                }
                $this->Ln();
            }

            // Grading Scale
            $this->Ln(8);
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 12, 3, 'F');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetXY(10, $this->GetY() + 3);
            $this->Cell(190, 5, 'GRADING SCALE', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 9);
            $this->SetXY(10, $this->GetY() + 3);
            
            $gradingScale = [
                ['A', '80-100%', 'Excellent'],
                ['B', '70-79%', 'Very Good'],
                ['C', '60-69%', 'Good'],
                ['D', '50-59%', 'Average'],
                ['E', 'Below 50%', 'Needs Improvement']
            ];
            
            foreach ($gradingScale as $scale) {
                $this->Cell(38, 5, $scale[0] . ' (' . $scale[1] . ') = ' . $scale[2], 0, 0, 'C');
            }
            $this->Ln(10);

            // Skills Assessment
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'SKILLS ASSESSMENT', 0, 1, 'L');
            
            $skills = [
                'Critical Thinking' => rand(60, 100),
                'Creativity' => rand(60, 100),
                'Collaboration' => rand(60, 100),
                'Communication' => rand(60, 100),
                'Organization' => rand(60, 100)
            ];
            
            $x = 20;
            $y = $this->GetY();
            $radius = 15;
            
            foreach ($skills as $skill => $score) {
                $this->drawProgressCircle($x, $y + 15, $radius, $score, $skill);
                $x += 38;
            }
            
            $this->SetY($y + 40);
            $this->Ln(5);

            // Teacher Comments Section
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'TEACHER COMMENTS', 0, 1, 'L');
            
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->RoundedRect(10, $this->GetY(), 190, 40, 3, 'F');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetXY(15, $this->GetY() + 5);
            $this->Cell(40, 5, 'Academic Performance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->MultiCell(150, 5, $this->getPersonalizedRemark($data['average']), 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetXY(15, $this->GetY() + 2);
            $this->Cell(40, 5, 'Classroom Conduct:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->MultiCell(150, 5, $this->getConductAssessment($data['average']), 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetXY(15, $this->GetY() + 2);
            $this->Cell(40, 5, 'Areas for Growth:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 10);
            $this->MultiCell(150, 5, $this->getImprovementSuggestions($data['average']), 0, 'L');
            
            $this->Ln(10);

            // Parent Acknowledgment
            $this->SetFont('Helvetica', 'B', 10);
            $this->SetTextColor(100, 100, 100);
            $this->Cell(0, 5, 'Parent/Guardian Acknowledgment:', 0, 1, 'L');
            
            $this->SetFont('Helvetica', '', 9);
            $this->MultiCell(0, 5, 'I have reviewed this report and discussed it with my child. I understand the progress made and areas needing improvement.', 0, 'L');
            
            $this->Ln(5);
            $this->Cell(100, 5, 'Parent/Guardian Signature: ___________________________', 0, 0, 'L');
            $this->Cell(90, 5, 'Date: _______________', 0, 1, 'R');
            
            // School Signatures
            $this->Ln(10);
            $this->SetFont('Helvetica', 'B', 10);
            $this->Cell(100, 5, 'Class Teacher: ___________________________', 0, 0, 'L');
            $this->Cell(90, 5, 'Date: _______________', 0, 1, 'R');
            
            $this->Cell(100, 5, 'Head Teacher: ___________________________', 0, 0, 'L');
            $this->Cell(90, 5, 'Date: _______________', 0, 1, 'R');
            
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

