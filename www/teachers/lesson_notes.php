<?php
// Start the session and check if it's already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "header.php"; 
require_once "db_connection.php";

// Initialize success and error message variables
$successMessage = "";
$errorMessage = "";

// Handle form submission to insert new lesson note
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data and sanitize inputs
    $class = htmlspecialchars(trim($_POST['class']));
    $periods = (int)$_POST['periods'];
    $week_ending = $_POST['week_ending'];
    $class_size = (int)$_POST['class_size'];
    $strand = htmlspecialchars(trim($_POST['strand']));
    $sub_strand = htmlspecialchars(trim($_POST['sub_strand']));
    $indicator = htmlspecialchars(trim($_POST['indicator']));
    $content_standard = htmlspecialchars(trim($_POST['content_standard']));
    $performance_indicator = htmlspecialchars(trim($_POST['performance_indicator']));
    $core_competencies = htmlspecialchars(trim($_POST['core_competencies']));
    $keywords = htmlspecialchars(trim($_POST['keywords']));
    $tlm = htmlspecialchars(trim($_POST['tlm']));
    $reference = htmlspecialchars(trim($_POST['reference']));
    $starter = htmlspecialchars(trim($_POST['starter']));
    $main = htmlspecialchars(trim($_POST['main']));
    $plenary = htmlspecialchars(trim($_POST['plenary']));
    $learning_objectives = htmlspecialchars(trim($_POST['learning_objectives']));
    $assessment_methods = htmlspecialchars(trim($_POST['assessment_methods']));

    // CSRF Token validation
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errorMessage = "Invalid CSRF token.";
    } else {
        try {
            // Insert the data into the database
            $query = "INSERT INTO lesson_notes (class, periods, week_ending, class_size, strand, sub_strand, indicator, content_standard, performance_indicator, core_competencies, keywords, tlm, reference, starter, main, plenary, learning_objectives, assessment_methods) 
                      VALUES (:class, :periods, :week_ending, :class_size, :strand, :sub_strand, :indicator, :content_standard, :performance_indicator, :core_competencies, :keywords, :tlm, :reference, :starter, :main, :plenary, :learning_objectives, :assessment_methods)";
            $stmt = $conn->prepare($query);
            $stmt->execute([
                ':class' => $class,
                ':periods' => $periods,
                ':week_ending' => $week_ending,
                ':class_size' => $class_size,
                ':strand' => $strand,
                ':sub_strand' => $sub_strand,
                ':indicator' => $indicator,
                ':content_standard' => $content_standard,
                ':performance_indicator' => $performance_indicator,
                ':core_competencies' => $core_competencies,
                ':keywords' => $keywords,
                ':tlm' => $tlm,
                ':reference' => $reference,
                ':starter' => $starter,
                ':main' => $main,
                ':plenary' => $plenary,
                ':learning_objectives' => $learning_objectives,
                ':assessment_methods' => $assessment_methods
            ]);
            $successMessage = "Lesson note submitted successfully!";
        } catch (PDOException $e) {
            $errorMessage = "Error submitting lesson note: " . $e->getMessage();
        }
    }
}

// Fetch existing classes for the dropdown
$query = "SELECT DISTINCT class FROM lesson_notes";
$stmt = $conn->prepare($query);
$stmt->execute();
$classes = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Fetch existing lesson notes for the table
$query = "SELECT * FROM lesson_notes";
$stmt = $conn->prepare($query);
$stmt->execute();
$lesson_notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .ai-header {
            background: linear-gradient(135deg, #4F46E5 0%, #10B981 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }

        h2 {
            font-size: 30px;
            font-weight: 500;
            color: #2c3e50;
            margin-bottom: 30px;
        }

        .hidden {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <!-- AI Header -->
        <div class="ai-header">
            <h3><i class="fas fa-book"></i> Add Lesson Notes</h3>
            <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage your lesson notes effectively</p>
        </div>

        <!-- Success Message -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <!-- Error Message -->
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <!-- Back Button -->
        <button class="btn btn-secondary mb-4" onclick="window.history.back()"><i class="fas fa-arrow-left"></i> Back</button>

        <!-- Class Selection -->
        <div class="mb-4">
            <label for="classSelect" class="form-label">Select Class</label>
            <select id="classSelect" class="form-control" onchange="showLessonNoteForm()">
                <option value="">-- Select a Class --</option>
                <?php foreach ($classes as $class): ?>
                    <option value="<?php echo htmlspecialchars($class); ?>"><?php echo htmlspecialchars($class); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Lesson Note Form -->
        <div id="lessonNoteForm" class="hidden">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus-circle"></i> Create New Lesson Note
                </div>
                <div class="card-body">
                    <form action="lesson_notes.php" method="POST" id="lessonNoteForm">
                        <input type="hidden" name="class" id="selectedClass">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="mb-3">
                            <label for="periods" class="form-label">Periods</label>
                            <input type="number" name="periods" id="periods" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="week_ending" class="form-label">Week Ending</label>
                            <input type="date" name="week_ending" id="week_ending" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="class_size" class="form-label">Class Size</label>
                            <input type="number" name="class_size" id="class_size" class="form-control" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="strand" class="form-label">Strand</label>
                            <input type="text" name="strand" id="strand" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="sub_strand" class="form-label">Sub-Strand</label>
                            <input type="text" name="sub_strand" id="sub_strand" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="indicator" class="form-label">Indicator</label>
                            <input type="text" name="indicator" id="indicator" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="content_standard" class="form-label">Content Standard</label>
                            <textarea name="content_standard" id="content_standard" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="performance_indicator" class="form-label">Performance Indicator</label>
                            <textarea name="performance_indicator" id="performance_indicator" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="core_competencies" class="form-label">Core Competencies</label>
                            <textarea name="core_competencies" id="core_competencies" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="keywords" class="form-label">Keywords</label>
                            <input type="text" name="keywords" id="keywords" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="tlm" class="form-label">TLM (Teaching Learning Materials)</label>
                            <textarea name="tlm" id="tlm" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="form-label">Reference</label>
                            <textarea name="reference" id="reference" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="starter" class="form-label">Starter</label>
                            <textarea name="starter" id="starter" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="main" class="form-label">Main</label>
                            <textarea name="main" id="main" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="plenary" class="form-label">Plenary</label>
                            <textarea name="plenary" id="plenary" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="learning_objectives" class="form-label">Learning Objectives</label>
                            <textarea name="learning_objectives" id="learning_objectives" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="assessment_methods" class="form-label">Assessment Methods</label>
                            <textarea name="assessment_methods" id="assessment_methods" class="form-control" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Submit Lesson Note</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table of Existing Lesson Notes -->
        <div id="lessonNotesTable" class="table-responsive mt-4 hidden">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Class</th>
                        <th>Periods</th>
                        <th>Week Ending</th>
                        <th>Class Size</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lesson_notes as $index => $lesson_note): ?>
                        <tr>
                            <td><?php echo ($index + 1); ?></td>
                            <td><?php echo htmlspecialchars($lesson_note['class']); ?></td>
                            <td><?php echo htmlspecialchars($lesson_note['periods']); ?></td>
                            <td><?php echo htmlspecialchars($lesson_note['week_ending']); ?></td>
                            <td><?php echo htmlspecialchars($lesson_note['class_size']); ?></td>
                            <td><a href='generate_pdf.php?id=<?php echo $lesson_note['id']; ?>' class='btn btn-info'><i class='fas fa-file-pdf'></i> Save as PDF</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function showLessonNoteForm() {
            const classSelect = document.getElementById('classSelect');
            const selectedClass = classSelect.value;
            const lessonNoteForm = document.getElementById('lessonNoteForm');
            const selectedClassInput = document.getElementById('selectedClass');
            const lessonNotesTable = document.getElementById('lessonNotesTable');

            if (selectedClass) {
                selectedClassInput.value = selectedClass; // Set the hidden input value
                lessonNoteForm.classList.remove('hidden'); // Show the form
                lessonNotesTable.classList.remove('hidden'); // Show the table
            } else {
                lessonNoteForm.classList.add('hidden'); // Hide the form if no class is selected
                lessonNotesTable.classList.add('hidden'); // Hide the table if no class is selected
            }
        }
    </script>
</body>
</html>