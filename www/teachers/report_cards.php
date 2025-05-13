<?php
ob_start();

// Fix undefined 'id' key by checking if it exists before use
$id = isset($_POST['id']) ? $_POST['id'] : null;

// Functions
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
    // Color palette
    private $primaryColor = [0, 102, 204];  // Deep blue
    private $secondaryColor = [230, 230, 250];  // Light lavender
    private $accentColor = [255, 102, 0];  // Orange accent
    private $darkText = [50, 50, 50];  // Dark gray for text
    
    function header() {
        // Header with modern design
        $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->Rect(0, 0, 210, 15, 'F');
        
        // School name in header
        $this->SetY(5);
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(255, 255, 255);
        $this->Cell(0, 10, 'PREMIER INTERNATIONAL SCHOOL', 0, 1, 'C');
        
        // Address section with subtle styling
        $this->SetY(20);
        $this->SetFont('Helvetica', '', 9);
        $this->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
        $this->Cell(0, 4, 'P.M.B 40, Madina | TEL: 0277411866 / 0541622751', 0, 1, 'C');
        $this->Cell(0, 4, 'LOCATION: Abokobi / Boi New Town', 0, 1, 'C');
        
        // Decorative line
        $this->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
        $this->SetLineWidth(0.8);
        $this->Line(10, $this->GetY() + 5, 200, $this->GetY() + 5);
        $this->Ln(10);
    }

    function footer() {
        $this->SetY(-15);
        $this->SetFont('Helvetica', 'I', 8);
        $this->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function getRemarks() {
        $remarks = [
            "Making steady progress keep it up.",
            "A consistent effort will lead to improvement.",
            "Shows potential, needs to stay focused.",
            "Can achieve more with greater concentration.",
            "A little more effort will bring better results.",
            "Shows interest but needs to work more independently.",
            "Needs to participate more actively in class.",
            "Good attitude toward learning keep improving.",
            "Capable of doing better with more dedication.",
            "Beginning to take studies more seriously."
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

        // Validate and use POST variables safely
        $class = isset($_POST['askclass']) ? $_POST['askclass'] : '';
        $exam = isset($_POST['exam']) ? $_POST['exam'] : '';

        $conductRemarks = [
            "Consistently demonstrates outstanding behavior and a positive attitude.",
            "Exemplifies respect, responsibility, and integrity in all actions.",
            "Engages actively and sets a positive example for peers.",
            "Shows great potential—would benefit from improved focus during class.",
            "Adheres to classroom expectations and contributes positively to the learning environment."
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

        // Calculate total scores for ranking
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
            // Student header section with modern design
            $this->AddPage();
            
            // Photo with modern border
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 11, 30, 30, 30, 'JPG', '', 'L', false, 300, '', false, false, 1, false, false, false);
                $this->SetDrawColor(200, 200, 200);
                $this->Rect(10, 29, 32, 32);
            } else {
                $this->SetFillColor(240, 240, 240);
                $this->Rect(10, 29, 32, 32, 'F');
                $this->SetTextColor(150, 150, 150);
                $this->SetFont('Arial', 'I', 8);
                $this->Text(13, 45, 'No Photo');
                $this->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
            }

            // Report title with accent color
            $this->SetFont('Arial', 'B', 18);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 15, 'STUDENT PROGRESS REPORT', 0, 1, 'C');
            
            // Student info section
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(40, 8, 'Student Name:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(0, 8, $data['student'], 0, 1, 'L');
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(40, 8, 'Class:', 0, 0, 'L');
            $this->SetFont('Helvetica', 'B', 13);
            $this->Cell(50, 8, $class, 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(30, 8, 'Term:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(0, 8, $exam, 0, 1, 'L');
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(40, 8, 'Academic Year:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 12);
            $this->Cell(0, 8, '2024/2025', 0, 1, 'L');
            
            // Divider line
            $this->SetDrawColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Line(10, $this->GetY() + 5, 200, $this->GetY() + 5);
            $this->Ln(10);

            // Academic performance table with modern styling
            $this->SetFont('Helvetica', 'B', 11);
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            
            // Table header
            $this->Cell(30, 10, 'Subject', 1, 0, 'C', true);
            $this->Cell(25, 10, 'Class(50%)', 1, 0, 'C', true);
            $this->Cell(25, 10, 'Exam(50%)', 1, 0, 'C', true);
            $this->Cell(25, 10, 'Total', 1, 0, 'C', true);
            $this->Cell(20, 10, 'Grade', 1, 0, 'C', true);
            $this->Cell(40, 10, 'Remarks', 1, 0, 'C', true);
            $this->Cell(25, 10, 'Position', 1, 1, 'C', true);

            // Table content
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

            $this->SetFont('Helvetica', '', 10);
            foreach ($subjects as $row) {
                $subject = $row["subject"];
                $midterm = $row["midterm"];
                $endterm = $row["endterm"];
                $average = $row["average"];
                $originalPosition = $row["position"];

                // Alternate row color for readability
                $fill = $this->GetFillColor() == $this->secondaryColor ? false : true;
                
                $this->Cell(30, 8, $subject, 1, 0, 'L', $fill);
                $this->Cell(25, 8, $midterm, 1, 0, 'C', $fill);
                $this->Cell(25, 8, $endterm, 1, 0, 'C', $fill);
                $this->Cell(25, 8, $average, 1, 0, 'C', $fill);

                // Grade with color coding
                if ($average >= 80) {
                    $grade = 'A';
                    $remarks = 'Excellent';
                    $this->SetTextColor(0, 128, 0); // Green for A
                } elseif ($average >= 70) {
                    $grade = 'B';
                    $remarks = 'Very Good';
                    $this->SetTextColor(0, 128, 128); // Teal for B
                } elseif ($average >= 60) {
                    $grade = 'C';
                    $remarks = 'Good';
                    $this->SetTextColor(0, 0, 255); // Blue for C
                } elseif ($average >= 50) {
                    $grade = 'D';
                    $remarks = 'Average';
                    $this->SetTextColor(128, 0, 128); // Purple for D
                } elseif ($average >= 40) {
                    $grade = 'E';
                    $remarks = 'Credit';
                    $this->SetTextColor(255, 165, 0); // Orange for E
                } else {
                    $grade = 'F';
                    $remarks = 'Weak';
                    $this->SetTextColor(255, 0, 0); // Red for F
                }
                
                $this->Cell(20, 8, $grade, 1, 0, 'C', $fill);
                $this->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
                $this->Cell(40, 8, $remarks, 1, 0, 'C', $fill);
                
                // Position with accent color
                $this->SetTextColor($this->accentColor[0], $this->accentColor[1], $this->accentColor[2]);
                $this->SetFont('Helvetica', 'B', 10);
                $this->Cell(25, 8, is_numeric($originalPosition) ? ordinal($originalPosition) : 'N/A', 1, 1, 'C', $fill);
                $this->SetFont('Helvetica', '', 10);
                $this->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
            }

            // Grading key with modern presentation
            $this->Ln(8);
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetTextColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->Cell(0, 8, 'GRADING KEY', 0, 1, 'C');
            
            $this->SetFont('Helvetica', '', 10);
            $this->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
            $this->Cell(0, 6, 'A (80-100) = Excellent | B (70-79) = Very Good | C (60-69) = Good', 0, 1, 'C');
            $this->Cell(0, 6, 'D (50-59) = Average | E (40-49) = Credit | F (0-39) = Weak', 0, 1, 'C');
            
            // Divider line
            $this->SetDrawColor(200, 200, 200);
            $this->Line(10, $this->GetY() + 5, 200, $this->GetY() + 5);
            $this->Ln(8);

            // Attendance and promotion section
            $this->SetFont('Helvetica', 'B', 11);
            $this->Cell(35, 8, 'Attendance:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->Cell(20, 8, '______', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 11);
            $this->Cell(25, 8, 'Out of:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->Cell(20, 8, '______', 0, 0, 'L');
            
            $this->SetFont('Helvetica', 'B', 11);
            $this->Cell(35, 8, 'Promoted to:', 0, 0, 'L');
            $this->SetFont('Helvetica', '', 11);
            $this->Cell(0, 8, '________________', 0, 1, 'L');
            $this->Ln(5);

            // Comments section with modern styling
            $this->SetFillColor($this->secondaryColor[0], $this->secondaryColor[1], $this->secondaryColor[2]);
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(0, 8, 'TEACHER COMMENTS', 0, 1, 'L', true);
            
            $this->SetFont('Helvetica', '', 11);
            $this->MultiCell(0, 6, $this->getRemarks(), 0, 'L');
            $this->Ln(3);
            
            $this->SetFont('Helvetica', 'B', 12);
            $this->Cell(0, 8, 'CONDUCT', 0, 1, 'L', true);
            
            $this->SetFont('Helvetica', '', 11);
            $this->MultiCell(0, 6, getRandomConductRemark($conductRemarks), 0, 'L');
            $this->Ln(5);

            // Requirements section with two columns
            $this->SetFont('Helvetica', 'B', 12);
            $this->SetFillColor($this->primaryColor[0], $this->primaryColor[1], $this->primaryColor[2]);
            $this->SetTextColor(255, 255, 255);
            $this->Cell(95, 8, 'REQUIREMENTS FOR NEXT TERM', 1, 0, 'C', true);
            $this->Cell(95, 8, 'SCHOOL NOTICES', 1, 1, 'C', true);
            
            $this->SetTextColor($this->darkText[0], $this->darkText[1], $this->darkText[2]);
            $this->SetFont('Helvetica', '', 10);
            
            // Left column - Requirements
            $requirements = [
                "School Fees: GHC " . $this->getFees($class),
                "Computer Fee: GHC 50",
                "Detol: 1 (Camel)",
                "Toilet Roll: 3",
                "Toilet Soap: 2",
                "Feeding Fee: GHC 7.00"
            ];
            
            $this->MultiCell(95, 7, implode("\n", $requirements), 1, 'L');
            
            // Right column - Notices
            $this->SetXY(105, $this->GetY() - 42); // Position for right column
            $this->MultiCell(95, 7, "We appreciate your continued support and partnership in your child's education. The next term begins on 6th May, 2025. Please ensure all requirements are met before the term begins.", 1, 'L');
            
            // Signature line
            $this->SetY($this->GetY() + 5);
            $this->SetFont('Helvetica', 'I', 10);
            $this->Cell(0, 8, 'Principal\'s Signature: _________________________', 0, 1, 'R');
            $this->Cell(0, 8, 'Date: _________________________', 0, 1, 'R');
        }
    }
}

// Generate PDF
$pdf = new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4');
$pdf->headertable();
$pdf->Output();
ob_end_flush();

