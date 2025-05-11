<?php
// Start output buffering to prevent "headers already sent" errors
ob_start();

// Define the function that selects a random conduct remark
function getRandomConductRemark($conductRemarks) {
    if (empty($conductRemarks)) {
        return 'No conduct remarks available.'; // Default remark if the array is empty
    }
    return $conductRemarks[array_rand($conductRemarks)];
}

// Function to convert a number to its ordinal representation
function ordinal($number) {
    // Ensure $number is an integer
    $number = (int)$number; // Convert to integer

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
    function header() {
        // Add the watermark image
        $this->addWatermark();

        // Header text with no background color
        $this->SetFont('Arial', 'B', 26);
        $this->Cell(190, 8, '', 0, 0, 'C');
        $this->Ln();
        
        // Add the logo image at the centered position
        $this->Image('../images/logo.PNG', 40, 12, 150, 23);

        // Add a small line break to move the address down
        $this->Ln(11); // Adjust this value to control the spacing

        // Add the address directly under the logo
        $this->SetFont('Times', 'B', 11);
        $this->Cell(190, 10, 'P.M.B 40, Madina', 0, 0, 'C'); // Address
        $this->Ln(); // Line break after the address

        $this->Cell(190, 10, 'TEL: 0277411866 / 0541622751', 0, 0, 'C');
        $this->Ln();

        $this->Cell(190, 10, 'LOCATION: Abokobi / Boi New Town', 0, 0, 'C');
        $this->Ln();

        // Draw the line under LOCATION
        $this->SetLineWidth(1); // Thicker line
        $this->Line(10, $this->GetY(), 200, $this->GetY()); // Line under location
        $this->Ln(); // Add a line break after the line
    }

    function addWatermark() {
        // Add the watermark image
        $this->Image('watermark_transparent_v3.png', 0, 0, 210, 297); // Full page size for A4
    }

    function footer() {
        // Footer content can be added here if needed
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function getRemarks() {
        // List of remarks
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
            "Beginning to take studies more seriously.",
            "Needs to revise lessons more regularly.",
            "Can perform better with greater consistency.",
            "A quiet student, encouraged to engage more.",
            "Demonstrates average understanding, more practice needed.",
            "Should aim to submit all work on time.",
            "Needs to improve attention during lessons.",
            "Able to grasp concepts but needs reinforcement.",
            "Has potential, needs to be more confident.",
            "Tries hard but needs better study habits.",
            "Needs to avoid rushing through work.",
            "A positive attitude, but focus needs improvement.",
            "Has improved slightly, more effort needed.",
            "Capable of achieving higher results.",
            "Needs to seek help when struggling.",
            "Should stay on task more consistently.",
            "A good foundation needs to build on it.",
            "Can benefit from regular revision.",
            "Can achieve higher potentials.",
            "Shows average results can improve with guidance.",
            "Can do better if distractions are minimized.",
            "Improvement seen encouraged to continue.",
            "Progressing slowly but steadily.",
            "Should challenge self with more effort.",
            "Needs to ask more questions when unsure.",
            "A cooperative student needs to show more initiative.",
            "Needs to take learning more seriously.",
            "Has the ability but needs to apply it more.",
            "Should strive to exceed basic expectations.",
            "Progresses at an average pace can do more.",
            "Will benefit from a more focused approach.",
            "Good behavior needs academic push.",
            "Needs to improve work completion rate.",
            "Can shine with more confidence.",
            "Should improve organization of work.",
            "Shows average results across subjects.",
            "Should build stronger study routines.",
            "Can reach greater heights with extra effort.",
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
                return 200; // Fee including computer fee
            default:
                return 0;
        }
    }

    function getSignatureImage($class) {
        $signatureImages = [
            'Basic Six A' => 'ern.jpg',
            'Basic Three A' => 'free.png',
            'Basic Three B' => 'lion.png',
            'Basic One' => 'Feli.png',
        ];
        return isset($signatureImages[$class]) ? $signatureImages[$class] : 'new.jpg';
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        $conductRemarks = [
            "Consistently demonstrates outstanding behavior and a positive attitude.",
            "Exemplifies respect, responsibility, and integrity in all actions.",
            "Engages actively and sets a positive example for peers.",
            "Shows great potential—would benefit from improved focus during class.",
            "Adheres to classroom expectations and contributes positively to the learning environment.",
            "Demonstrates empathy, kindness, and strong interpersonal skills.",
            "Encouraged to show greater respect and attentiveness during lessons.",
            "Exhibits natural leadership and inspires others through actions.",
            "Remarkable progress in behavior—keep up the great effort!",
            "Takes initiative and displays a strong sense of responsibility.",
            "Works well independently and in group settings.",
            "Demonstrates resilience and perseverance in challenging tasks.",
            "Is respectful to peers and teachers at all times.",
            "A reliable and dependable student.",
            "Cheerful and brings a positive energy to the class.",
            "Actively listens and contributes meaningfully to discussions.",
            "Regularly helps and encourages classmates.",
            "Handles responsibilities with maturity and care.",
            "Stays calm under pressure and manages conflict well.",
            "Needs gentle reminders to stay on task but shows willingness to improve.",
            "Maintains a positive attitude towards learning and growth.",
            "Is developing good self-control and patience.",
            "Willing to accept feedback and strives to do better.",
            "Consistently completes tasks with care and attention.",
            "Needs to work on being more cooperative during group activities.",
            "Kind-hearted and always ready to support others.",
            "Takes pride in personal and academic growth.",
            "Enthusiastic and motivated to learn new things.",
            "Sometimes distracted—encouraged to stay focused during lessons.",
            "A great example of punctuality and preparedness.",
            "Respectfully communicates with peers and adults.",
            "Demonstrates honesty and trustworthiness.",
            "Increasingly confident in expressing thoughts and ideas.",
            "Appreciates structure and responds well to routines.",
            "Can improve by being more mindful of class rules.",
            "Always willing to take part in class activities.",
            "Demonstrates a strong sense of fairness and justice.",
            "Well-mannered and considerate of others’ feelings.",
            "Responds positively to encouragement and support.",
            "Making steady improvement in behavior and attitude.",
            "Demonstrates a calm and thoughtful presence.",
            "Follows instructions carefully and consistently.",
            "Is beginning to show initiative in taking responsibility.",
            "Needs to focus on being more respectful during class discussions.",
            "Displays maturity in handling challenges.",
            "Always completes tasks on time and with effort.",
            "Cooperates well and contributes meaningfully to team efforts.",
            "Learns from mistakes and shows a growth mindset.",
            "Needs reminders but shows willingness to correct behavior.",
            "Polite, respectful, and a joy to have in class.",
            "An excellent role model for classmates."
        ];

        $sql = "SELECT name, admno, photo FROM student WHERE class LIKE '%$class%' ORDER BY admno ASC";
        $ret = $conn->query($sql);
        if (!$ret) {
            die("Query failed: " . $conn->lastErrorMsg());
        }

        $subjectOrder = ['English','Science','Owop','R.M.E','History','Computing','Creative','Twi','French'];

        $totalScores = [];
        while ($row1 = $ret->fetchArray(SQLITE3_ASSOC)) {
            $admno = $row1['admno'];
            $studentName = $row1['name'];
            $photoPath = $row1['photo'];

            $sqlm = "SELECT average FROM marks WHERE admno = '$admno' AND examname = '$exam'";
            $retm = $conn->query($sqlm);
            $totalScore = 0;
            $subjectCount = 0;

            while ($row = $retm->fetchArray(SQLITE3_ASSOC)) {
                $totalScore += $row['average'];
                $subjectCount++;
            }

            $totalScores[$admno] = [
                'total' => $totalScore,
                'student' => $studentName,
                'subjectCount' => $subjectCount,
                'photo' => $photoPath,
            ];
        }

        arsort($totalScores);
        $ret->reset();

        foreach ($totalScores as $admno => $data) {
            // Start each report card content generation here
            $this->Ln(-10);
            $this->SetFont('Arial', 'BU', 16);
            $this->Cell(190, 10, "PUPIL'S TERMINAL REPORT", 0, 0, 'C');
            $this->Ln();

            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 11, 15, 26, 20);
            } else {
                $this->SetFillColor(200, 200, 200);
                $this->Rect(11, 15, 26, 20, 'F');
            }

            $this->SetFont('Times', '', 12);
            $this->Cell(35, 10, 'Name:', 0, 0, 'L');
            $this->SetFont('Times', 'B', 12);
            $this->Cell(10, 10, $data['student'], 0, 0, 'L');
            $this->Ln();

            $this->SetFont('Times', '', 12);
            $this->Cell(35, 10, 'Class :', 0, 0, 'L');
            $this->SetFont('Times', 'B', 13);
            $this->Cell(70, 10, $class, 0, 0, 'L');
            $this->SetFont('Times', '', 12);
            $this->Cell(30, 10, 'Exam :', 0, 0, 'L');
            $this->SetFont('Times', 'B', 12);
            $this->Cell(76, 10, $exam, 0, 0, 'L');
            $this->Ln();

            // You can continue with the rest of your existing rendering logic here,
            // similar to what you've written already.
            // ...
            // Finally, add a new page for the next report card.
            $this->AddPage();
        }
    }
}

// Instantiate and generate the report cards PDF
$pdf = new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->headertable();
$pdf->Output();

ob_end_flush(); // Flush the output buffer
?>
