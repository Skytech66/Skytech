<?php
ob_start();

require 'vendor/autoload.php'; // Composer autoload for PHPWord

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Define the function that selects a random conduct remark
function getRandomConductRemark($conductRemarks) {
    if (empty($conductRemarks)) {
        return 'No conduct remarks available.';
    }
    return $conductRemarks[array_rand($conductRemarks)];
}

// Function to convert a number to its ordinal representation
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

class MyWordDocument {
    private $phpWord;
    private $section;

    public function __construct() {
        $this->phpWord = new PhpWord();
        $this->section = $this->phpWord->addSection();
    }

    public function addHeader($logoPath) {
        if (file_exists($logoPath)) {
            $this->section->addImage($logoPath, [
                'width' => 150,
                'height' => 23,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER,
            ]);
        }

        $this->section->addText('P.M.B 40, Madina', ['bold' => true, 'size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $this->section->addText('TEL: 0277411866 / 0541622751', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $this->section->addText('LOCATION: Abokobi / Boi New Town', ['size' => 12], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $this->section->addTextBreak(1);
    }

    public function addStudentReport($studentData, $class, $exam, $conductRemarks) {
        $this->section->addText('PUPIL\'S TERMINAL REPORT', ['bold' => true, 'size' => 16], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $this->section->addTextBreak(1);

        // Student photo or placeholder
        if (!empty($studentData['photo']) && file_exists($studentData['photo'])) {
            $this->section->addImage($studentData['photo'], [
                'width' => 90,
                'height' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::START,
            ]);
        } else {
            // Adding a grey box as placeholder is not natively supported; skipping.
        }

        $this->section->addText('Name: ' . $studentData['student'], ['bold' => true, 'size' => 12]);
        $this->section->addText('Class: ' . $class, ['bold' => true, 'size' => 12]);
        $this->section->addText('Exam: ' . $exam, ['bold' => true, 'size' => 12]);
        $this->section->addText('Term Ending: 17th April, 2025', ['bold' => false, 'size' => 12]);
        $this->section->addText('Next term begins: 6th May, 2025', ['bold' => false, 'size' => 12]);
        $this->section->addTextBreak(1);

        // Subject marks table
        $tableStyleName = 'Subject Table';
        $tableStyle = [
            'borderSize' => 6,
            'borderColor' => '000000',
            'cellMargin' => 80,
        ];
        $firstRowStyle = ['bgColor' => 'CCCCCC'];
        $phpWord = $this->phpWord;
        $phpWord->addTableStyle($tableStyleName, $tableStyle, $firstRowStyle);
        $table = $this->section->addTable($tableStyleName);

        // Table header row
        $table->addRow();
        $headers = ['SUBJECT', 'CLASS(50%)', 'EXAM (50%)', 'TOTAL (100%)', 'GRADE', 'REMARKS', 'POSITION'];
        foreach ($headers as $header) {
            $table->addCell(2500)->addText($header, ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        }

        // Subject data rows
        foreach ($studentData['subjects'] as $subject) {
            $table->addRow();
            $table->addCell(2500)->addText($subject['subject']);
            $table->addCell(2500)->addText($subject['midterm']);
            $table->addCell(2500)->addText($subject['endterm']);
            $table->addCell(2500)->addText($subject['average']);

            // Grade and remarks based on average
            $average = (float)$subject['average'];
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
            $table->addCell(2500)->addText($grade, ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            $table->addCell(2500)->addText($remarks, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

            $position = $subject['position'];
            if (is_numeric($position) && $position > 0) {
                $table->addCell(2500)->addText(ordinal($position), ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            } else {
                $table->addCell(2500)->addText('N/A', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
            }
        }
        $this->section->addTextBreak(1);

        // Grading System
        $this->section->addText('GRADING SYSTEM', ['bold' => true, 'size' => 14], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $gradingText1 = 'A - Excellent (80 - 100)     B - Very Good (70 - 79)     C - Good (60 - 69)';
        $gradingText2 = 'D - Average (50 - 59)          E - Credit (40 - 44)          F - Weak (39 and below)';
        $this->section->addText($gradingText1, ['bold' => true, 'size' => 11], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $this->section->addText($gradingText2, ['bold' => true, 'size' => 11], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $this->section->addTextBreak(1);

        // Attendance and promotion info (placeholders)
        $this->section->addText('Attendance: ______', ['bold' => true, 'size' => 12]);
        $this->section->addText('Out of: ______', ['bold' => true, 'size' => 12]);
        $this->section->addText('Promoted to: ______', ['bold' => true, 'size' => 12]);
        $this->section->addTextBreak(1);

        // Fees (computer fee added already in original)
        $fees = $this->getFees($class); // Assuming this method exists or add here if needed

        // General remarks - random from remarks
        $remarks = $this->getRemarks(); // Assuming this method exists or add here if needed
        $this->section->addText('Remarks:', ['bold' => true, 'size' => 12]);
        $this->section->addText($remarks, ['size' => 12]);
        $this->section->addTextBreak(1);

        // Conduct remarks - random from passed array
        $conductRemark = getRandomConductRemark($conductRemarks);
        $this->section->addText('Conduct:', ['bold' => true, 'size' => 12]);
        $this->section->addText($conductRemark, ['size' => 12]);
        $this->section->addTextBreak(1);

        // Signatures placeholders - teacher and headmistress
        $this->section->addText("Class teacher's signature: ______________________", ['size' => 12]);
        $this->section->addText("Headmistress's signature: ______________________", ['size' => 12]);
        $this->section->addTextBreak(1);

        // Requirements and Management table
        $this->addRequirementsAndManagementTable($fees);
    }

    public function addRequirementsAndManagementTable($fees) {
        $tableStyleName = 'Requirements Table';
        $tableStyle = ['borderSize' => 6, 'borderColor' => '000000', 'cellMargin' => 80];
        $firstRowStyle = ['bgColor' => 'CCCCCC'];

        $this->phpWord->addTableStyle($tableStyleName, $tableStyle, $firstRowStyle);
        $table = $this->section->addTable($tableStyleName);

        // Headers
        $table->addRow();
        $table->addCell(5000)->addText('REQUIREMENT FOR NEXT TERM', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $table->addCell(5000)->addText('MANAGEMENT', ['bold' => true], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // Content row
        $table->addRow();
        $requirementText = "SCHOOL FEES: GHC $fees\nCOMPUTER FEE: GHC 50\nDETOL, 1 (CAMEL).\nTOILET ROLL 3, TOILET SOAP 2.\nFEEDING FEE: GHC 7.00.";
        $managementText = "WITH OUR SINCEREST THANKSGIVING TO PARENTS AND STAKEHOLDERS OF THE SCHOOL, WE LOOK FORWARD TO WORKING WITH YOU NEXT TERM. MAY GOD BLESS YOU.";

        $table->addCell(5000)->addText($requirementText, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
        $table->addCell(5000)->addText($managementText, [], ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT]);
    }

    // Dummy placeholder for getFees
    public function getFees($class) {
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

    // Dummy placeholder for getRemarks (random remark selection)
    public function getRemarks() {
        $remarks = [
            "Making steady progress keep it up.",
            "A consistent effort will lead to improvement.",
            "Shows potential, needs to stay focused.",
            "Can achieve more with greater concentration.",
            "A little more effort will bring better results.",
            "Shows interest but needs to work more independently.",
            "Needs to participate more actively in class.",
            "Good attitude toward learning keep improving.",
            // ... add more remarks as needed
        ];
        return $remarks[array_rand($remarks)];
    }

    public function saveAndOutput($filename = 'student_report.docx') {
        // Headers for download
        header("Content-Description: File Transfer");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Cache-Control: max-age=0');

        $objWriter = IOFactory::createWriter($this->phpWord, 'Word2007');
        $objWriter->save('php://output');
    }
}

// Connect to database and get data
include "../include/functions.php";
$conn = db_conn();

$class = $_POST['askclass'] ?? '';
$exam = $_POST['exam'] ?? '';

if (empty($class) || empty($exam)) {
    die("Class and Exam must be specified.");
}

// Conduct remarks array (same as original)
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
    // ... continue as needed
];

// Fetch students
$sql = "SELECT name, admno, photo FROM student WHERE class LIKE '%$class%' ORDER BY admno ASC";
$ret = $conn->query($sql);
if (!$ret) {
    die("Query failed: " . $conn->lastErrorMsg());
}

$totalScores = [];
$studentsData = [];

while ($row1 = $ret->fetchArray(SQLITE3_ASSOC)) {
    $admno = $row1['admno'];
    $studentName = $row1['name'];
    $photoPath = $row1['photo'];

    // Get marks for this student and exam
    $sqlm = "SELECT subject, midterm, endterm, average, remarks, position FROM marks WHERE admno = '$admno' AND examname = '$exam'";
    $retm = $conn->query($sqlm);
    if (!$retm) {
        die("Query failed: " . $conn->lastErrorMsg());
    }
    $subjects = [];
    $totalScore = 0;
    $subjectCount = 0;
    while ($row = $retm->fetchArray(SQLITE3_ASSOC)) {
        $subjects[] = $row;
        $totalScore += $row['average'];
        $subjectCount++;
    }
    $totalScores[$admno] = $totalScore;
    $studentsData[$admno] = [
        'student' => $studentName,
        'photo' => $photoPath,
        'subjects' => $subjects,
        'totalScore' => $totalScore,
        'subjectCount' => $subjectCount,
    ];
}

arsort($totalScores);

$phpWordDoc = new MyWordDocument();
$phpWordDoc->addHeader('logo.PNG');

// Generate a report per student sorted by total score
foreach ($totalScores as $admno => $score) {
    $phpWordDoc->addStudentReport($studentsData[$admno], $class, $exam, $conductRemarks);
    $phpWordDoc->section->addPageBreak();
}

$phpWordDoc->saveAndOutput('student_reports.docx');

ob_end_flush();
exit;
?>

