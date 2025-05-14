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

    // Implement Circle method to draw circles as FPDF does not have it by default
    function Circle($x, $y, $r, $startAngle = 0, $endAngle = 360, $style = 'D') {
        // Convert degrees to radians
        $startAngle = deg2rad($startAngle);
        $endAngle = deg2rad($endAngle);
        $k = $this->k;
        $hp = $this->h;

        $this->_out(sprintf('%.2F %.2F m', ($x + $r * cos($startAngle)) * $k, ($hp - ($y + $r * sin($startAngle))) * $k));
        
        $arcIncrement = deg2rad(4); // Smaller increment for smoother circle
        for($angle = $startAngle + $arcIncrement; $angle <= $endAngle; $angle += $arcIncrement) {
            $this->_out(sprintf('%.2F %.2F l', ($x + $r * cos($angle)) * $k, ($hp - ($y + $r * sin($angle))) * $k));
        }
        // Final point
        $this->_out(sprintf('%.2F %.2F l', ($x + $r * cos($endAngle)) * $k, ($hp - ($y + $r * sin($endAngle))) * $k));

        switch ($style) {
            case 'F':
                $op = 'f';
                break;
            case 'FD':
            case 'DF':
                $op = 'B';
                break;
            default:
                $op = 'S';
        }
        $this->_out($op);
    }

    function drawProgressCircle($x, $y, $radius, $percentage, $label) {
        // Circle background
        $this->SetDrawColor(220, 220, 220);
        $this->Circle($x, $y, $radius, 0, 360);
        
        // Progress arc - color based on percentage
        if ($percentage >= 80) {
            $this->SetDrawColor($this->successColor[0], $this->successColor[1], $this->successColor[2]);
        } elseif ($percentage >= 60) {
            $this->SetDrawColor(65, 131, 215); // Blue
        } else {
            $this->SetDrawColor($this->warningColor[0], $this->warningColor[1], $this->warningColor[2]);
        }
        
        // Calculate end angle (360° * percentage / 100)
        $endAngle = 360 * $percentage / 100;
        $this->Circle($x, $y, $radius, 0, $endAngle, 'D');
        
        // Percentage text
        $this->SetFont('Helvetica', 'B', 8);
        $this->SetTextColor(0, 0, 0);
        $textWidth = $this->GetStringWidth($percentage . '%');
        $this->Text($x - $textWidth/2, $y + 3, $percentage . '%');
        
        // Skill label
        $this->SetFont('Helvetica', '', 7);
        $labelWidth = $this->GetStringWidth($label);
        $this->Text($x - $labelWidth/2, $y + $radius + 8, $label);
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();

        // Safely get POST parameters with fallback to empty string
        $class = isset($_POST['askclass']) ? $_POST['askclass'] : '';
        $exam = isset($_POST['exam']) ? $_POST['exam'] : '';

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
    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', 
            $x1 * $this->k, ($h - $y1) * $this->k,
            $x2 * $this->k, ($h - $y2) * $this->k,
            $x3 * $this->k, ($h - $y3) * $this->k
        ));
    }
}

$pdf = new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->headertable();
$pdf->Output();
ob_end_flush();
?>

            
