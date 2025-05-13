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
    // Validate CSRF Token first
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $errorMessage = "Invalid CSRF token. Please try again.";
    } else {
        // Get and sanitize form data
        $fields = [
            'class' => FILTER_SANITIZE_STRING,
            'periods' => FILTER_SANITIZE_NUMBER_INT,
            'week_ending' => FILTER_SANITIZE_STRING,
            'class_size' => FILTER_SANITIZE_NUMBER_INT,
            'strand' => FILTER_SANITIZE_STRING,
            'sub_strand' => FILTER_SANITIZE_STRING,
            'indicator' => FILTER_SANITIZE_STRING,
            'content_standard' => FILTER_SANITIZE_STRING,
            'performance_indicator' => FILTER_SANITIZE_STRING,
            'core_competencies' => FILTER_SANITIZE_STRING,
            'keywords' => FILTER_SANITIZE_STRING,
            'tlm' => FILTER_SANITIZE_STRING,
            'reference' => FILTER_SANITIZE_STRING,
            'starter' => FILTER_SANITIZE_STRING,
            'main' => FILTER_SANITIZE_STRING,
            'plenary' => FILTER_SANITIZE_STRING,
            'learning_objectives' => FILTER_SANITIZE_STRING,
            'assessment_methods' => FILTER_SANITIZE_STRING
        ];
        
        $formData = filter_input_array(INPUT_POST, $fields);
        
        // Validate required fields
        $required = ['class', 'periods', 'week_ending', 'class_size', 'strand', 'sub_strand'];
        foreach ($required as $field) {
            if (empty($formData[$field])) {
                $errorMessage = "Please fill in all required fields.";
                break;
            }
        }
        
        if (empty($errorMessage)) {
            try {
                // Insert the data into the database
                $query = "INSERT INTO lesson_notes (
                    class, periods, week_ending, class_size, strand, sub_strand, indicator, 
                    content_standard, performance_indicator, core_competencies, keywords, 
                    tlm, reference, starter, main, plenary, learning_objectives, assessment_methods
                ) VALUES (
                    :class, :periods, :week_ending, :class_size, :strand, :sub_strand, :indicator, 
                    :content_standard, :performance_indicator, :core_competencies, :keywords, 
                    :tlm, :reference, :starter, :main, :plenary, :learning_objectives, :assessment_methods
                )";
                
                $stmt = $conn->prepare($query);
                $stmt->execute([
                    ':class' => $formData['class'],
                    ':periods' => $formData['periods'],
                    ':week_ending' => $formData['week_ending'],
                    ':class_size' => $formData['class_size'],
                    ':strand' => $formData['strand'],
                    ':sub_strand' => $formData['sub_strand'],
                    ':indicator' => $formData['indicator'],
                    ':content_standard' => $formData['content_standard'],
                    ':performance_indicator' => $formData['performance_indicator'],
                    ':core_competencies' => $formData['core_competencies'],
                    ':keywords' => $formData['keywords'],
                    ':tlm' => $formData['tlm'],
                    ':reference' => $formData['reference'],
                    ':starter' => $formData['starter'],
                    ':main' => $formData['main'],
                    ':plenary' => $formData['plenary'],
                    ':learning_objectives' => $formData['learning_objectives'],
                    ':assessment_methods' => $formData['assessment_methods']
                ]);
                
                $successMessage = "Lesson note submitted successfully!";
                // Clear form by resetting POST array
                $_POST = [];
            } catch (PDOException $e) {
                $errorMessage = "Error submitting lesson note: " . $e->getMessage();
                error_log("Database error: " . $e->getMessage());
            }
        }
    }
}

// Fetch existing classes for the dropdown
$classes = [];
try {
    $query = "SELECT DISTINCT class FROM lesson_notes ORDER BY class";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $classes = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    error_log("Error fetching classes: " . $e->getMessage());
}

// Fetch existing lesson notes for the table
$lesson_notes = [];
try {
    $query = "SELECT id, class, periods, week_ending, class_size FROM lesson_notes ORDER BY week_ending DESC, class";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $lesson_notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching lesson notes: " . $e->getMessage());
}

// Generate CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Professional Lesson Notes Management System">
    <title>EduPlan Pro | Lesson Notes Management</title>
    
    <!-- Favicon -->
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --light-gray: #e9ecef;
            --success: #4cc9f0;
            --warning: #f72585;
            --border-radius: 8px;
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .app-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }
        
        .app-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
            position: relative;
            overflow: hidden;
        }
        
        .app-header::before {
            content: "";
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
            transform: rotate(30deg);
        }
        
        .app-title {
            font-family: 'Source Sans Pro', sans-serif;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.8rem;
            position: relative;
        }
        
        .app-subtitle {
            font-weight: 400;
            opacity: 0.9;
            margin-bottom: 0;
            position: relative;
        }
        
        .card {
            border: none;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            margin-bottom: 1.5rem;
            overflow: hidden;
            background-color: white;
        }
        
        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid var(--light-gray);
            font-weight: 600;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-control, .form-select {
            padding: 0.75rem;
            border: 1px solid var(--light-gray);
            border-radius: var(--border-radius);
            transition: var(--transition);
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: var(--border-radius);
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        
        .btn-outline-primary {
            color: var(--primary);
            border-color: var(--primary);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary);
            border-color: var(--primary);
        }
        
        .alert {
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .table {
            border-radius: var(--border-radius);
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table th {
            background-color: var(--primary);
            color: white;
            font-weight: 500;
            padding: 1rem;
            position: sticky;
            top: 0;
        }
        
        .table td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
            border-top: 1px solid var(--light-gray);
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(67, 97, 238, 0.05);
        }
        
        .badge {
            padding: 0.5em 0.75em;
            border-radius: var(--border-radius);
            font-weight: 500;
            font-size: 0.85rem;
        }
        
        .hidden {
            display: none;
        }
        
        .section-divider {
            border-top: 1px dashed var(--light-gray);
            margin: 1.5rem 0;
            position: relative;
        }
        
        .section-divider::after {
            content: "◆";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 0 0.5rem;
            color: var(--primary);
            font-size: 0.8rem;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            color: var(--primary);
            font-weight: 600;
        }
        
        .action-buttons .btn {
            min-width: 100px;
        }
        
        .floating-action-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-lg);
            z-index: 100;
            transition: var(--transition);
        }
        
        .floating-action-btn:hover {
            transform: translateY(-3px) scale(1.05);
            background-color: var(--primary-dark);
        }
        
        .form-section {
            background-color: white;
            border-radius: var(--border-radius);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }
        
        .form-section:hover {
            box-shadow: var(--shadow-md);
        }
        
        .status-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 6px;
        }
        
        .status-active {
            background-color: var(--success);
            box-shadow: 0 0 0 2px rgba(76, 201, 240, 0.3);
        }
        
        .status-inactive {
            background-color: var(--gray);
            box-shadow: 0 0 0 2px rgba(108, 117, 125, 0.3);
        }
        
        @media (max-width: 768px) {
            .app-container {
                padding: 0 0.5rem;
            }
            
            .app-header {
                padding: 1rem;
            }
            
            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            .table-responsive {
                border-radius: 0;
            }
            
            .floating-action-btn {
                bottom: 1rem;
                right: 1rem;
            }
        }
        
        /* Modern scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
        
        /* Animation for form sections */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .form-section {
            animation: fadeIn 0.3s ease-out forwards;
        }
        
        /* Delay animations for each section */
        .form-section:nth-child(1) { animation-delay: 0.1s; }
        .form-section:nth-child(2) { animation-delay: 0.2s; }
        .form-section:nth-child(3) { animation-delay: 0.3s; }
        .form-section:nth-child(4) { animation-delay: 0.4s; }
        .form-section:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>

<body>
    <div class="app-container">
        <!-- Application Header -->
        <div class="app-header">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h1 class="app-title">
                        <i class="fas fa-book-open"></i> EduPlan Pro
                    </h1>
                    <p class="app-subtitle">
                        <i class="fas fa-pencil-alt"></i> Professional Lesson Notes Management System
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="status-indicator status-active"></span>
                    <span class="text-white-50">Connected</span>
                </div>
            </div>
        </div>
        
        <!-- Notification Messages -->
        <?php if ($successMessage): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i>
                <div><?php echo htmlspecialchars($successMessage); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i>
                <div><?php echo htmlspecialchars($errorMessage); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        
        <!-- Navigation Controls -->
        <div class="d-flex justify-content-between mb-4">
            <button class="btn btn-outline-primary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Back
            </button>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-cog"></i> Settings
                </button>
                <button class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
        
        <!-- Class Selection Card -->
        <div class="card">
            <div class="card-header">
                <i class="fas fa-chalkboard-teacher"></i> Select Class
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="classSelect" class="form-label">
                        <i class="fas fa-filter"></i> Choose a class to manage lesson notes
                    </label>
                    <select id="classSelect" class="form-select" onchange="showLessonNoteForm()">
                        <option value="">-- Select a Class --</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo htmlspecialchars($class); ?>" <?php echo (isset($_POST['class']) && $_POST['class'] === $class ? 'selected' : ''); ?>>
                                <?php echo htmlspecialchars($class); ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="new">+ Add New Class</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Lesson Note Form (Initially Hidden) -->
        <div id="lessonNoteForm" class="<?php echo (isset($_POST['class']) ? '' : 'hidden'); ?>">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-plus-circle"></i> Create New Lesson Note
                    </div>
                    <div class="badge bg-primary">
                        <i class="fas fa-save"></i> Draft
                    </div>
                </div>
                <div class="card-body">
                    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" id="lessonNoteFormElement">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        
                        <!-- Basic Information Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-info-circle"></i> Basic Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="class" class="form-label">
                                        <i class="fas fa-users"></i> Class
                                    </label>
                                    <input type="text" name="class" id="selectedClass" class="form-control" value="<?php echo htmlspecialchars($_POST['class'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="periods" class="form-label">
                                        <i class="fas fa-clock"></i> Periods
                                    </label>
                                    <input type="number" name="periods" id="periods" class="form-control" min="1" value="<?php echo htmlspecialchars($_POST['periods'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="week_ending" class="form-label">
                                        <i class="fas fa-calendar-week"></i> Week Ending
                                    </label>
                                    <input type="date" name="week_ending" id="week_ending" class="form-control" value="<?php echo htmlspecialchars($_POST['week_ending'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="class_size" class="form-label">
                                        <i class="fas fa-user-graduate"></i> Class Size
                                    </label>
                                    <input type="number" name="class_size" id="class_size" class="form-control" min="1" value="<?php echo htmlspecialchars($_POST['class_size'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Curriculum Information Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-book"></i> Curriculum Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="strand" class="form-label">
                                        <i class="fas fa-link"></i> Strand
                                    </label>
                                    <input type="text" name="strand" id="strand" class="form-control" value="<?php echo htmlspecialchars($_POST['strand'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="sub_strand" class="form-label">
                                        <i class="fas fa-link"></i> Sub-Strand
                                    </label>
                                    <input type="text" name="sub_strand" id="sub_strand" class="form-control" value="<?php echo htmlspecialchars($_POST['sub_strand'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="indicator" class="form-label">
                                        <i class="fas fa-bullseye"></i> Indicator
                                    </label>
                                    <input type="text" name="indicator" id="indicator" class="form-control" value="<?php echo htmlspecialchars($_POST['indicator'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="keywords" class="form-label">
                                        <i class="fas fa-key"></i> Keywords
                                    </label>
                                    <input type="text" name="keywords" id="keywords" class="form-control" value="<?php echo htmlspecialchars($_POST['keywords'] ?? ''); ?>" required>
                                </div>
                                <div class="col-12">
                                    <label for="content_standard" class="form-label">
                                        <i class="fas fa-ruler"></i> Content Standard
                                    </label>
                                    <textarea name="content_standard" id="content_standard" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['content_standard'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="performance_indicator" class="form-label">
                                        <i class="fas fa-chart-line"></i> Performance Indicator
                                    </label>
                                    <textarea name="performance_indicator" id="performance_indicator" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['performance_indicator'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="core_competencies" class="form-label">
                                        <i class="fas fa-brain"></i> Core Competencies
                                    </label>
                                    <textarea name="core_competencies" id="core_competencies" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['core_competencies'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Teaching Resources Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-tools"></i> Teaching Resources
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="tlm" class="form-label">
                                        <i class="fas fa-puzzle-piece"></i> Teaching Learning Materials (TLM)
                                    </label>
                                    <textarea name="tlm" id="tlm" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['tlm'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="reference" class="form-label">
                                        <i class="fas fa-book-open"></i> Reference Materials
                                    </label>
                                    <textarea name="reference" id="reference" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['reference'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Lesson Structure Section -->
                        <div class="form-section">
                            <h5 class="section-title">
                                <i class="fas fa-project-diagram"></i> Lesson Structure
                            </h5>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="learning_objectives" class="form-label">
                                        <i class="fas fa-bullseye"></i> Learning Objectives
                                    </label>
                                    <textarea name="learning_objectives" id="learning_objectives" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['learning_objectives'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="starter" class="form-label">
                                        <i class="fas fa-play-circle"></i> Starter Activity
                                    </label>
                                    <textarea name="starter" id="starter" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['starter'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="main" class="form-label">
                                        <i class="fas fa-tasks"></i> Main Activities
                                    </label>
                                    <textarea name="main" id="main" class="form-control" rows="3" required><?php echo htmlspecialchars($_POST['main'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="plenary" class="form-label">
                                        <i class="fas fa-flag-checkered"></i> Plenary/Conclusion
                                    </label>
                                    <textarea name="plenary" id="plenary" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['plenary'] ?? ''); ?></textarea>
                                </div>
                                <div class="col-12">
                                    <label for="assessment_methods" class="form-label">
                                        <i class="fas fa-clipboard-check"></i> Assessment Methods
                                    </label>
                                    <textarea name="assessment_methods" id="assessment_methods" class="form-control" rows="2" required><?php echo htmlspecialchars($_POST['assessment_methods'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form Submission -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Submit Lesson Note
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Existing Lesson Notes Table (Initially Hidden) -->
        <div id="lessonNotesTable" class="<?php echo (isset($_POST['class']) ? '' : 'hidden'); ?>">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <i class="fas fa-list-ul"></i> Existing Lesson Notes
                    </div>
                    <div class="badge bg-primary">
                        <i class="fas fa-database"></i> <?php echo count($lesson_notes); ?> Records
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
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
                                        <td>
                                            <div class="d-flex gap-2 action-buttons">
                                                <a href='generate_pdf.php?id=<?php echo $lesson_note['id']; ?>' class='btn btn-sm btn-outline-primary'>
                                                    <i class='fas fa-file-pdf'></i> PDF
                                                </a>
                                                <a href='edit_note.php?id=<?php echo $lesson_note['id']; ?>' class='btn btn-sm btn-outline-secondary'>
                                                    <i class='fas fa-edit'></i> Edit
                                                </a>
                                                <a href='delete_note.php?id=<?php echo $lesson_note['id']; ?>' class='btn btn-sm btn-outline-danger' onclick="return confirm('Are you sure you want to delete this lesson note?')">
                                                    <i class='fas fa-trash-alt'></i> Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Floating Action Button -->
    <a href="#" class="floating-action-btn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </a>
    
    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        function showLessonNoteForm() {
            const classSelect = document.getElementById('classSelect');
            const selectedClass = classSelect.value;
            const lessonNoteForm = document.getElementById('lessonNoteForm');
            const selectedClassInput = document.getElementById('selectedClass');
            const lessonNotesTable = document.getElementById('lessonNotesTable');
            
            if (selectedClass === 'new') {
                // Prompt for new class name
                const className = prompt("Enter new class name:");
                if (className) {
                    selectedClassInput.value = className;
                    lessonNoteForm.classList.remove('hidden');
                    lessonNotesTable.classList.add('hidden');
                    
                    // Scroll to form
                    lessonNoteForm.scrollIntoView({ behavior: 'smooth' });
                } else {
                    classSelect.value = '';
                }
            } else if (selectedClass) {
                selectedClassInput.value = selectedClass;
                lessonNoteForm.classList.remove('hidden');
                lessonNotesTable.classList.remove('hidden');
                
                // Scroll to table
                lessonNotesTable.scrollIntoView({ behavior: 'smooth' });
            } else {
                lessonNoteForm.classList.add('hidden');
                lessonNotesTable.classList.add('hidden');
            }
        }
        
        // Initialize date field to today if empty
        document.addEventListener('DOMContentLoaded', function() {
            const weekEndingField = document.getElementById('week_ending');
            if (weekEndingField && !weekEndingField.value) {
                const today = new Date();
                const nextFriday = new Date(today);
                nextFriday.setDate(today.getDate() + (5 - today.getDay() + 7) % 7);
                
                const year = nextFriday.getFullYear();
                const month = String(nextFriday.getMonth() + 1).padStart(2, '0');
                const day = String(nextFriday.getDate()).padStart(2, '0');
                
                weekEndingField.value = `${year}-${month}-${day}`;
            }
            
            // Auto-resize textareas
            const textareas = document.querySelectorAll('textarea');
            textareas.forEach(textarea => {
                textarea.addEventListener('input', function() {
                    this.style.height = 'auto';
                    this.style.height = (this.scrollHeight) + 'px';
                });
                
                // Trigger initial resize
                textarea.dispatchEvent(new Event('input'));
            });
            
            // Show floating button when scrolling down
            window.addEventListener('scroll', function() {
                const fab = document.querySelector('.floating-action-btn');
                if (window.pageYOffset > 300) {
                    fab.style.opacity = '1';
                    fab.style.visibility = 'visible';
                    fab.style.transform = 'translateY(0)';
                } else {
                    fab.style.opacity = '0';
                    fab.style.visibility = 'hidden';
                    fab.style.transform = 'translateY(20px)';
                }
            });
        });
        
        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
        
        // Confirm before form reset
        document.querySelector('button[type="reset"]').addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to reset all form fields?')) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
