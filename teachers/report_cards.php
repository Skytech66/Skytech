<?php
// Define the function that selects a random conduct remark
function getRandomConductRemark($conductRemarks) {
    return $conductRemarks[array_rand($conductRemarks)];
}

require "fpdf.php";

class mypdf extends fpdf {
    function header() {
        // Header text with no background color
        $this->setfont('Times', 'B', 26);
        $this->cell(190, 8, 'CENTER FOR EXCELLENT CHILD', 0, 0, 'C');
        $this->Ln();

        $this->setfont('Arial', 'B', 11);
        $this->cell(122, 37, 'LOCATION: Abokobi \ Boi-Newtown', 0, 0, 'R');
        $this->Image('logo.PNG', 12, 10, 190, 23);
        $this->Image('Studentlogo.PNG', 163, 40, 40, 35);
        
		$this->setfont('Arial', 'B', 11);
        $this->cell(200, 25, 'P.M.B,40, Madina', 0, 0, 'C');
        $this->Ln();

        $this->setfont('Arial', 'B', 11);
        $this->cell(57, 0, ' ', 0, 0, 'L');
        $this->cell(60, 2, 'TEL: 0541622751 | 0277411866', 0, 0, 'R');
        $this->Ln();

        $this->cell(50, 8, '                          ', 0, 0, 'L');
        $this->Ln();
    }

    function footer() {
        // Footer with light gray background color
        $this->SetY(-30);
        $this->SetFillColor(240, 240, 240);  // Light gray background for footer
        $this->Rect(0, 275, 210, 15, 'F');  // Fill footer with color

        $this->setfont('Arial', 'i', 12);
        $this->cell(70, 8, 'Class Teacher', 0, 0, 'L');
        $this->cell(190, 8, 'Head Teacher', 0, 0, 'C');
        $this->Line(0, 275, 279, 275);
        $this->Ln();
        $this->cell(0, 8, 'Excellence is our hallmark', 0, 0, 'C');
    }

    function getRemarks($totalScore) {
        $averageScore = $totalScore / 11;
        if ($averageScore < 30) {
            return 'Needs improvement. Focus on fundamentals.';
        } else if ($averageScore < 40) {
            return 'Fair performance. Consistent effort needed.';
        } else if ($averageScore < 50) {
            return 'Acceptable. Aim for better consistency.';
        } else if ($averageScore < 60) {
            return 'Good. Keep building on strengths.';
        } else if ($averageScore < 80) {
            return 'Very good. Keep up the steady work.';
        } else if ($averageScore <= 100) {
            return 'Excellent. Continue refining your skills.';
        } else {
            return 'Exceptional. Keep challenging yourself!';
        }
    }

    function getRating($score) {
        if ($score >= 90) {
            return 5;  // Excellent
        } elseif ($score >= 75) {
            return 4;  // Very Good
        } elseif ($score >= 60) {
            return 3;  // Good
        } elseif ($score >= 50) {
            return 2;  // Satisfactory
        } else {
            return 1;  // Needs Improvement
        }
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        $sql = "SELECT student, admno FROM marks WHERE examname LIKE '%$exam%' AND class LIKE '%$class%' GROUP BY admno ORDER BY admno ASC";
        $ret = $conn->query($sql);

        // Conduct remarks array
        $conductRemarks = [
            "Excellent behavior, always polite and respectful.",
            "Good behavior, shows improvement.",
            "Satisfactory behavior, but room for improvement.",
            "Needs improvement in behavior and attitude.",
            "Consistently disruptive in class."
        ];

        while ($row1 = $ret->fetchArray(SQLITE3_ASSOC)) {
            // Set line width and table header
            $this->SetLineWidth(1);
            $this->Line(0, 55, 162, 55);
            $this->SetLineWidth(0.2);
            $this->Ln();
            $this->setfont('Arial', 'BU', 18);
            $this->cell(190, 3, 'TERMINAL REPORT', 0, 0, 'C');
            $this->Ln();

            // Student details section
            $this->setfont('Times', 'B', 12);
            $admno = $row1["admno"];

            $this->cell(35, 10, 'Class :', 0, 0, 'L');
            $this->setfont('Times', 'i', 14);
            $this->cell(70, 10, $class, 0, 0, 'L');
            $this->setfont('Times', 'B', 14);
            $this->cell(30, 10, 'Exam :', 0, 0, 'L');
            $this->setfont('Times', 'i', 12);
            $this->cell(76, 10, $exam, 0, 0, 'L');
            $this->Ln();

            $this->setfont('Times', 'B', 12);
            $this->cell(80, 10, 'Student Name:', 0, 0, 'L');
            $this->setfont('Times', 'i', 12);
            $this->cell(10, 10, decryptthis($row1["student"], $key), 0, 0, 'L');
            $this->Ln();

            // Table headers for subject and marks with color
            $this->setfont('Times', 'B', 12);
            $this->SetFillColor(70, 130, 180); // Steel Blue background for headers
            $this->cell(35, 11, 'SUBJECT', 1, 0, 'C', true);
            $this->cell(28, 11, 'CLASS (50%)', 1, 0, 'C', true);
            $this->cell(35, 11, 'EXAM (50%)', 1, 0, 'C', true);
            $this->cell(34, 11, 'TOTAL (100%)', 1, 0, 'C', true);
            $this->cell(34, 11, 'GRADE', 1, 0, 'C', true);
            $this->cell(30, 11, 'Remarks', 1, 0, 'C', true);
            $this->Ln();

            // Subject data fetch and table row population with alternating row colors
            $sqlm = "SELECT subject, midterm, endterm, average, remarks FROM marks WHERE admno LIKE '%$admno%' AND examname LIKE '%$exam%' GROUP BY subject ORDER BY subject DESC";
            $retm = $conn->query($sqlm);

            $fill = false;  // Variable to alternate row color
            while ($row = $retm->fetchArray(SQLITE3_ASSOC)) {
                $this->setfont('Arial', '', 10);
                $subject = decryptthis($row["subject"], $key);
                $midterm = decryptthis($row["midterm"], $key);
                $endterm = decryptthis($row["endterm"], $key);
                $average = decryptthis($row["average"], $key);

                // Set alternating row background color
                if ($fill) {
                    $this->SetFillColor(240, 255, 255);  // Light Cyan for alternating rows
                } else {
                    $this->SetFillColor(255, 255, 255);  // White for alternating rows
                }
                $fill = !$fill;

                $this->cell(35, 8, $subject, 1, 0, 'C', true);
                $this->cell(28, 8, $midterm, 1, 0, 'C', true);
                $this->cell(35, 8, $endterm, 1, 0, 'C', true);
                $this->cell(34, 8, $average, 1, 0, 'C', true);

                // Map grades to remarks
                if ($average >= 90) {
                    $grade = 'A';
                    $remarks = 'Outstanding';
                } elseif ($average >= 80) {
                    $grade = 'B';
                    $remarks = 'Commendable';
                } elseif ($average >= 60) {
                    $grade = 'C';
                    $remarks = 'Satisfactory';
                } elseif ($average >= 40) {
                    $grade = 'D';
                    $remarks = 'Improving';
                } else {
                    $grade = 'E';
                    $remarks = 'Developing';
                }

                $this->cell(34, 8, $grade, 1, 0, 'C', true); 
                $this->cell(30, 8, $remarks, 1, 0, 'C', true);
                $this->Ln();
            }

            // Affected Traits and Rating with Professional Color Scheme
            $this->Ln();
            $this->setfont('Times', 'B', 11);
            $this->cell(35, 10, 'Affected Traits and Ratings:', 0, 0, 'L');
            $this->Ln();

            // Define affected traits (modified)
            $traits = ['Concepts Understanding', 'Class Participation', 'Behavior/Attitude'];
            $this->SetFillColor(100, 149, 237); // Cornflower Blue for header
            $this->cell(70, 8, 'Affected Traits', 1, 0, 'C', true);
            $this->cell(10, 8, '1', 1, 0, 'C', true);
            $this->cell(10, 8, '2', 1, 0, 'C', true);
            $this->cell(10, 8, '3', 1, 0, 'C', true);
            $this->cell(10, 8, '4', 1, 0, 'C', true);
            $this->cell(10, 8, '5', 1, 0, 'C', true);
            $this->Ln();

            // Loop through traits and assign ratings (up to 5)
            foreach ($traits as $trait) {
                $rating = $this->getRating(rand(40, 90)); // Simulated rating based on performance
                $this->cell(70, 8, $trait, 1, 0, 'L');
                
                // Loop for rating numbers (1 to 5)
                for ($i = 1; $i <= 5; $i++) {
                    $this->cell(10, 8, '', 1, 0, 'C');
                    if ($i == $rating) {
                        
                    }
                }
                $this->Ln();
            }

            // Remarks Section (Moved Before Conduct)
            $this->Ln();
            $remarks = $this->getRemarks(rand(50, 90));  // Random score for remarks
            $this->setfont('Times', 'B', 12);
            $this->cell(35, 4, 'Remarks:', 0, 0, 'L');
            $this->setfont('Times', 'i', 12);
            $this->multiCell(0, 4, $remarks, 0, 'L');
            $this->Ln();

            // Conduct Remark Section (Now Directly Under Remarks)
            $conductRemark = getRandomConductRemark($conductRemarks);
            $this->setfont('Times', 'B', 12);
            $this->cell(35, 4, 'Conduct:', 0, 0, 'L');
            $this->setfont('Times', 'i', 12);
            $this->multiCell(0, 4, $conductRemark, 0, 'L');
            $this->Ln();

            // Signature lines and Remarks
            $this->setfont('Times', 'B', 12);
            $this->Line(10, 265, 50, 265);
            $this->Line(150, 265, 200, 265);
            $this->AddPage();
            $this->Line(5, 55, 205, 55);
            $this->Ln();
            $this->setfont('Times', 'B', 20);
        }
    }
}

// Create new PDF instance
$pdf = new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->headertable();
$pdf->Output();
?>
