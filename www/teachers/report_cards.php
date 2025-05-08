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
        $logoWidth = 150; // Width of the logo
        $this->Image('logo.PNG', 40, 3, $logoWidth, 23); // Adjusted X position to 40 and Y position to 3

        // Add a small line break to move the address down
        $this->Ln(2); // Adjust this value to control the spacing

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
            "Making steady progress—keep it up.",
            "A consistent effort will lead to improvement.",
            "Shows potential,             needs to stay focused.",
            "Can achieve more with greater concentration.",
            "A little more effort will bring better results.",
            "Shows interest but needs to work more independently.",
            "Needs to participate more actively in class.",
            "Good attitude toward learning—keep improving.",
            "Capable of doing better with more dedication.",
            "Beginning to take studies more seriously.",
            "Needs to revise lessons more regularly.",
            "Can perform better with greater consistency.",
            "A quiet student—encouraged to engage more.",
            "Demonstrates average understanding—more practice needed.",
            "Should aim to submit all work on time.",
            "Needs to improve attention during lessons.",
            "Able to grasp concepts but needs reinforcement.",
            "Has potential, needs to be more confident.",
            "Tries hard but needs better study habits.",
            "Needs to avoid rushing through work.",
            "A positive attitude, but focus needs improvement.",
            "Has improved slightly—more effort needed.",
            "Capable of achieving higher results.",
            "Often meets basic expectations—aim higher.",
                        "Needs to seek help when struggling.",
            "Should stay on task more consistently.",
            "A good foundation needs to build on it.",
            "Shows improvement but must keep it up.",
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

        // Randomly select a remark from the list
        return $remarks[array_rand($remarks)];
    }

    // Function to get fees based on the class
    function getFees($class) {
        switch ($class) {
            case 'Basic Six A':
            case 'Basic Six B':
            case 'Basic Three B':
            case 'Basic Three A':
                return 200;
            default:
                return 0; // Default fee if class does not match
        }
    }

    function headertable() {
        include "../include/functions.php";
        $conn = db_conn();
        $class = $_POST['askclass'];
        $exam = $_POST['exam'];

        // Fetch student data including photo from the student table
        $sql = "SELECT name, admno, photo FROM student WHERE class LIKE '%$class%' ORDER BY admno ASC";
        $ret = $conn->query($sql);

        // Check for query errors
        if (!$ret) {
            die("Query failed: " . $conn->lastErrorMsg());
        }

        // Conduct remarks array
        $conductRemarks = [
           
    "Always eager to learn and participate.",
    "Shows great enthusiasm for learning.",
    "Demonstrates kindness towards peers and teachers.",
    "Works well independently and in group settings.",
    "Displays a positive attitude every day.",
    "Shows creativity and originality in work.",
    "Tries hard and puts in great effort.",
    "Demonstrates perseverance in all tasks.",
    "Shows responsibility and completes tasks on time.",
    "Always willing to help others.",
    "Listens attentively and follows directions well.",
    "Shows improvement in focus and engagement.",
    "Demonstrates confidence in abilities.",
    "Uses time wisely and efficiently.",
    "Engages actively in class discussions.",
    "Is respectful and courteous to everyone.",
    "Shows curiosity and asks thoughtful questions.",
    "Displays strong problem-solving skills.",
    "Always strives to do their best.",
    "Shows great teamwork and cooperation.",
    "Works hard and never gives up.",
    "Shows patience and understanding with others.",
    "Accepts feedback and uses it to improve.",
    "Has a cheerful and positive attitude.",
    "Displays leadership skills among peers.",
    "Is dependable and trustworthy.",
    "Shows a strong sense of fairness and justice.",
    "Makes excellent use of class resources.",
    "Demonstrates a strong work ethic.",
    "Expresses thoughts and ideas clearly.",
    "Shows respect for different perspectives.",
    "Demonstrates self-control and patience.",
    "Handles challenges with a positive mindset.",
    "Takes initiative and seeks to improve.",
    "Shows growth and progress in learning.",
    "Brings a bright energy to the classroom.",
    "Treats others with kindness and compassion.",
    "Is always ready to help a friend in need.",
    "Demonstrates strong leadership qualities.",
    "Handles challenges with a positive mindset.",
    "Stays focused and completes tasks efficiently.",
    "Demonstrates enthusiasm in all subjects.",
    "Never gives up, even when faced with difficulties.",
    "Is a great team player and values others' ideas.",
    "Shows appreciation for different cultures and perspectives.",
    "Displays excellent organization skills.",
    "Remains calm and collected in challenging situations.",
    "Finds creative solutions to problems.",
    "Brightens the classroom with a positive attitude.",
    "Displays increasing independence and self-confidence.",
    "Tries their best in every situation.",
    "Seeks out new learning opportunities.",
    "Takes responsibility for their actions.",
    "Has a strong sense of curiosity and wonder.",
    "Brings joy and excitement to learning."
        ];

        // First pass: Calculate total scores for each student
        $totalScores = []; // Initialize the totalScores array
        while ($row1 = $ret->fetchArray(SQLITE3_ASSOC)) {
            $admno = $row1["admno"];
            $studentName = $row1["name"]; // Corrected to 'name'
            $photoPath = $row1["photo"]; // Get the photo path

            // Fetch subject scores for the current student
            $sqlm = "SELECT average FROM marks WHERE admno = '$admno' AND examname = '$exam'";
            $retm = $conn->query($sqlm);
            
            $totalScore = 0;
            $subjectCount = 0;

            while ($row = $retm->fetchArray(SQLITE3_ASSOC)) {
                $average = $row["average"]; // No decryption needed if already readable
                $totalScore += $average; // Sum of all marks
                $subjectCount++;
            }

            // Store total score and student info
            $totalScores[$admno] = [
                'total' => $totalScore,
                'student' => $studentName,
                'subjectCount' => $subjectCount, // Store subject count for average calculation
                'photo' => $photoPath // Store photo path
            ];
        }

        // Sort students by total scores in descending order
        arsort($totalScores);

        // Reset the cursor for the first student to generate reports
        $ret->reset();

        // Generate reports for each student
        foreach ($totalScores as $admno => $data) {
            $this->Ln(-10); // Move up by 10 units (adjust as needed)
            $this->SetFont('Arial', 'BU', 16);
            $this->Cell(190, 10, 'PUPIL\'S TERMINAL REPORT', 0, 0, 'C'); // Use standard apostrophe
            $this->Ln();

            // Add the passport photo
            if (!empty($data['photo']) && file_exists($data['photo'])) {
                $this->Image($data['photo'], 11, 7, 26, 20); // Display the photo
            } else {
                // If no photo is available, display a grey placeholder
                $this->SetFillColor(200, 200, 200); // Set fill color to grey
                $this->Rect(11, 7, 26, 20, 'F'); // Draw a filled rectangle as a placeholder
            }

            // Student details section
            $this->SetFont('Times', '', 12);
            $this->Cell(35, 10, 'Name:', 0, 0, 'L');
            $this->SetFont('Times', 'B', 12); // Set to bold
            $this->Cell(10, 10, $data['student'], 0, 0, 'L');
            $this->SetFont('Times', '', 12); // Reset to normal
            $this->Ln();

            // Now display the class in bold
            $this->SetFont('Times', '', 12);
            $this->Cell(35, 10, 'Class :', 0, 0, 'L');
            $this->SetFont('Times', 'B', 13); // Set to bold
            $this->Cell(70, 10, $class, 0, 0, 'L');
            $this->SetFont('Times', '', 12); // Reset to normal
            $this->Cell(30, 10, 'Exam :', 0, 0, 'L');
            $this->SetFont('Times', 'B', 12); // Set to bold
            $this->Cell(76, 10, $exam, 0, 0, 'L');
            $this->SetFont('Times', '', 12); // Reset to normal
            $this->Ln();

            $this->SetFont('Times', '', 12);
            $this->Cell(50, 10, 'Term Ending: ', 0, 0, 'L'); // Normal text
            $this->SetFont('Times', 'B', 12); // Set to bold for the date
            $this->Cell(50, 10, '17th April, 2025', 0, 0,             'L'); // Bold date
            $this->SetFont('Times', '', 12); // Reset to normal
            $this->Cell(50, 10, 'Next term begins: ', 0, 0, 'L'); // Normal text
            $this->SetFont('Times', 'B', 12); // Set to bold for the date
            $this->Cell(50, 10, '6th May, 2025', 0, 0, 'L'); // Bold date
            $this->SetFont('Times', '', 12); // Reset to normal
            $this->Ln(); // Add an extra line break for spacing

            // Table headers for subject and marks with reduced widths
            $this->SetFont('Times', 'B', 12);
            $this->Cell(27, 8, 'SUBJECT', 1, 0, 'C'); // Reduced width
            $this->Cell(25, 8, 'CLASS(50%)', 1, 0, 'C'); // Reduced width
            $this->Cell(30, 8, 'EXAM (50%)', 1, 0, 'C'); // Reduced width
            $this->Cell(30, 8, 'TOTAL (100%)', 1, 0, 'C'); // Reduced width
            $this->Cell(25, 8, 'GRADE', 1, 0, 'C'); // Reduced width
            $this->Cell(30, 8, 'REMARKS', 1, 0, 'C'); // Reduced width
            $this->Cell(25, 8, 'POSITION', 1, 0, 'C'); // Restored Position column
            $this->Ln();

            // Subject data fetch and table row population with reduced widths
            $sqlm = "SELECT subject, midterm, endterm, average, remarks, position FROM marks WHERE admno = '$admno' AND examname = '$exam' ORDER BY subject DESC";
            $retm = $conn->query($sqlm);

            // Check for query errors
            if (!$retm) {
                die("Query failed: " . $conn->lastErrorMsg());
            }

            while ($row = $retm->fetchArray(SQLITE3_ASSOC)) {
                $this->SetFont('Arial', '', 10);
                $subject = $row["subject"]; // No decryption needed
                $midterm = $row["midterm"]; // No decryption needed
                $endterm = $row["endterm"]; // No decryption needed
                $average = $row["average"]; // No decryption needed
                $originalPosition = $row["position"]; // Fetch the original position without decryption

                $this->Cell(27, 7, $subject, 1, 0, 'C'); // Reduced height
                $this->Cell(25, 7, $midterm, 1, 0, 'C'); // Reduced height
                $this->Cell(30, 7, $endterm, 1, 0, 'C'); // Reduced height
                $this->Cell(30, 7, $average, 1, 0, 'C'); // Reduced height

                // Display the grade and remarks
                if ($average >= 80) {
                    $grade = 'A';
                    $remarks = 'Excellent';
                } elseif ($average >= 70) {
                    $grade = 'B';
                    $remarks = 'Very Good';
                } elseif ($average >= 60) {
                    $grade = 'C';
                    $remarks = 'Good';
                } elseif ($average >= 50) {
                    $grade = 'D';
                    $remarks = 'Average';
                } elseif ($average >= 40) {
                    $grade = 'E';
                    $remarks = 'Credit';
                } else {
                    $grade = 'F';
                    $remarks = 'Weak';
                }
                // Set font to bold for Grade
                $this->SetFont('Arial', 'B', 10);
                $this->Cell(25, 7, $grade, 1, 0, 'C'); // Grade
                $this->SetFont('Arial', '', 10); // Reset font to normal for remarks
                $this->Cell(30, 7, $remarks, 1, 0, 'C'); // Remarks

                // Set font to bold for Position
                $this->SetFont('Arial', 'B', 10);
                if (is_numeric($originalPosition) && $originalPosition > 0) {
                    $this->Cell(25, 7, ordinal($originalPosition),                     1, 0, 'C'); // Display the original position with ordinal
                } else {
                    $this->Cell(25, 7, 'N/A', 1, 0, 'C'); // Handle invalid position
                }
                $this->Ln();
            }

            // Grading System Section - Moved directly under the position
            $this->SetFont('Arial', 'BU', 14);
            $this->Cell(0, 10, 'GRADING SYSTEM', 0, 1, 'C');
            $this->SetFont('Times', 'B', 11);
            $this->Cell(0, 10, 'A - Excellent (80 - 100)               B - Very Good (70 - 79)               C - Good (60 - 69)', 0, 1, 'C');
            $this->Cell(0, 10, '     D - Average (50 - 59)                           E - Credit (40 - 44)                         F - Weak (39 and below)', 0, 1, 'C');
            $this->SetLineWidth(0.5); // Thicker line
            $this->Line(10, $this->GetY(), 200, $this->GetY()); // Add a line under grading system
            
            // Attendance, Out of, and Promoted to Section - Directly under the Total Score and Position
            $this->Ln(3); // Adjust as needed for spacing
            $this->SetFont('Times', 'B', 12);
            $this->Cell(35, 10, 'Attendance:', 0, 0, 'L');
            $this->Cell(35, 10, '______', 0, 0, 'L');
            $this->Cell(35, 10, 'Out of:', 0, 0, 'L');
            $this->Cell(35, 10, '______', 0, 0, 'L');
            $this->Cell(35, 10, 'Promoted to:', 0, 0, 'L');
            $this->Cell(35, 10, '', 0, 1, 'L'); // Empty cell for spacing
            $this->Ln(1); // Adjust as needed for spacing

            // Get the fees based on the class
            $fees = $this->getFees($class);
            // Remarks Section
            $remarks = $this->getRemarks();  // Use the new random remark selection
            $this->SetFont('Times', 'B', 12);
            $this->Cell(35, 4, 'Remarks:', 0, 0, 'L');
            $this->SetFont('Times', '', 12);
            $this->MultiCell(0, 4, $remarks, 0, 'L');
            $this->Ln();

            // Conduct Remark Section
            $conductRemark = getRandomConductRemark($conductRemarks);
            $this->SetFont('Times', 'B', 12);
            $this->Cell(35, 4, 'Conduct:', 0, 0, 'L');
            $this->SetFont('Times', '', 12);
            $this->MultiCell(0, 4, $conductRemark, 0, 'L');
            $this->Ln(2);
            // Add the signatures directly under the conduct section
            $this->SetFont('Times', 'B', 12);
            $this->Cell(80, 8, 'Class teacher\'s signature:', 0, 0, 'L'); // Adjusted width
            $this->Cell(80, 8, 'Headmistress\'s signature:', 0, 1, 'L'); // Adjusted width

            // Set specific Y position for the signatures
            $signatureY = $this->GetY() - 10; // Adjust this value to move the signatures up or down

            // Adjust the X position to move the signatures to the right
            $classTeacherX = 58; // X position for Class Teacher's signature
            $headmistressX = 140; // X position for Headmistress's signature

            // Add the signature images directly on the lines with shorter height
            $this->Image('ern.jpg', $classTeacherX, $signatureY, 20); // Class teacher's signature (shorter height)
            $this->Image('new.jpg', $headmistressX, $signatureY, 30); // Headmistress's signature (shorter height)

            // Move the table up directly under the signature
            $this->Ln(4); // Adjust as needed for spacing

            // Create the multi-cell table for requirements and management
            $this->SetFont('Arial', 'B', 10);
            $this->Cell(95, 7, 'REQUIREMENT FOR NEXT TERM', 1, 0, 'C'); // Column header
            $this->Cell(95, 7, 'MANAGEMENT', 1, 1, 'C'); // Column header

            // Set font for the content
            $this->SetFont('Times', '', 10); // Reduced line height

                        // Add content for the first column (REQUIREMENT FOR NEXT TERM)
            $fees = $this->getFees($class); // Get the fees based on the class
            $this->MultiCell(95, 5, "   SCHOOL FEES:     GHC " . $fees . "\n   DETOL,                  1 (CAMEL).\n   TOILET ROLL 3, TOILET SOAP 2.\n   FEEDING FEE:      GHC 7.00.", 1, 'L'); // Example content
            
            // Move to the next line for the second column
            $this->SetXY(105, $this->GetY() - 20); // Adjust Y position to align with the first column

            // Add content for the second column (MANAGEMENT)
            $this->MultiCell(95, 5, "WITH OUR SINCEREST THANKSGIVING TO PARENTS AND STAKEHOLDERS OF THE SCHOOL, WE LOOK FORWARD TO WORKING WITH YOU NEXT TERM. MAY GOD BLESS YOU.", 1, 'L'); // Example content

            // Add a line break after the table
            $this->Ln(0); // Adjust as needed for spacing

            // Add a new page for the next report
            $this->AddPage(); // Ensure a new page for the next report
        }
    }
}

// Create new PDF instance
$pdf = new mypdf();
$pdf->AliasNbPages();
$pdf->AddPage('P', 'A4', 0);
$pdf->headertable();
$pdf->Output();
ob_end_flush(); // Flush the output buffer
?>