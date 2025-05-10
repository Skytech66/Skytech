<?php 
require_once "header.php"; 
$year = isset($_POST['year']) ? $_POST['year'] : '';
$exam = isset($_POST['exam']) ? $_POST['exam'] : '';
$class = isset($_POST['class']) ? $_POST['class'] : '';
$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
$totalStudents = 0;

// SQL query to fetch students
$sql = "SELECT * from student where `year` like '$year' and `class` like '$class'";
$res = $conn->query($sql);

if (!$res) {
    echo "Error executing query: " . $conn->lastErrorMsg();
}

$students = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $students[] = $row;
}

$totalStudents = count($students);
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="marks-container">
    <div class="marks-header">
        <div class="header-content">
            <h1 class="marks-title">
                <i class="fas fa-clipboard-list"></i> 
                <?php echo htmlspecialchars($subject); ?> Mark Sheet
            </h1>
            <div class="class-info">
                <span class="info-badge"><i class="fas fa-calendar-alt"></i> <?php echo htmlspecialchars($year); ?></span>
                <span class="info-badge"><i class="fas fa-graduation-cap"></i> <?php echo htmlspecialchars($class); ?></span>
                <span class="info-badge"><i class="fas fa-tasks"></i> <?php echo htmlspecialchars($exam); ?></span>
            </div>
        </div>
        <div class="progress-indicator">
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill" style="width: 0%"></div>
            </div>
            <span id="studentCount">0/<?php echo $totalStudents; ?> completed</span>
        </div>
    </div>

    <form id="marksForm" action="submit_scores.php" method="POST" enctype="multipart/form-data">
        <div class="marks-controls">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search students..." onkeyup="searchStudent()" />
            </div>
            
            <div class="action-buttons">
                <button type="button" id="submitMarksButton" class="btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Marks
                </button>
                <button type="button" id="analyzePositionsButton" class="btn-secondary">
                    <i class="fas fa-chart-line"></i> Analyze Positions
                </button>
            </div>
        </div>

        <input type="hidden" name="uuser" value="<?php echo $session_id; ?>" />
        <input type="hidden" name="year" value="<?php echo $year; ?>" />
        <input type="hidden" name="exam" value="<?php echo $exam; ?>" />
        <input type="hidden" name="class" value="<?php echo $class; ?>" />
        <input type="hidden" name="subject" value="<?php echo $subject; ?>" />
        
        <div class="marks-table-container">
            <table id="pager" class="marks-table">
                <thead>
                    <tr>
                        <th class="serial-number">#</th>
                        <th class="student-name">Student</th>
                        <th class="student-id">ID</th>
                        <th class="score-header">Class Score <span>(50%)</span></th>
                        <th class="score-header">Exam Score <span>(50%)</span></th>
                        <th class="position-header">Position</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($totalStudents === 0): ?>
                        <tr class="no-data">
                            <td colspan="6">
                                <i class="fas fa-user-graduate"></i>
                                <span>No students found for the selected criteria</span>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $index => $row): ?>
                        <tr class="student-row">
                            <td class="serial-number"><?php echo $index + 1; ?></td>
                            <td class="student-name">
                                <input type="hidden" name="jina[]" value="<?php echo htmlspecialchars($row['name']); ?>" />
                                <div class="student-avatar">
                                    <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                </div>
                                <span><?php echo htmlspecialchars($row['name']); ?></span>
                            </td>
                            <td class="student-id">
                                <input type="hidden" name="regno[]" value="<?php echo htmlspecialchars($row['admno']); ?>" />
                                <span><?php echo htmlspecialchars($row['admno']); ?></span>
                            </td>
                            <td class="score-input">
                                <div class="input-container">
                                    <input type="number" class="mark-input" name="midterm[]" max="50" placeholder="0-50" />
                                    <div class="input-border"></div>
                                </div>
                            </td>
                            <td class="score-input">
                                <div class="input-container">
                                    <input type="number" class="mark-input" name="endterm[]" max="50" placeholder="0-50" />
                                    <div class="input-border"></div>
                                </div>
                            </td>
                            <td class="position-display">
                                <div class="position-container">
                                    <input type="number" class="position-input" name="position[]" min="1" readonly />
                                    <span class="ordinal-suffix"></span>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>

<?php require_once "../include/footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced mark input validation
    function validateMark(input) {
        const value = parseFloat(input.value) || 0;
        const max = parseFloat(input.max) || 50;
        const container = input.closest('.input-container');
        
        // Reset all classes
        container.classList.remove('valid', 'warning', 'error', 'empty');
        
        if (input.value === "") {
            container.classList.add('empty');
        } else if (value > max) {
            container.classList.add('warning');
        } else if (value < 0) {
            container.classList.add('error');
        } else {
            container.classList.add('valid');
        }
        
        updateProgress();
    }

    // Progress tracking
    function updateProgress() {
        const inputs = document.querySelectorAll('.mark-input');
        let filled = 0;
        
        inputs.forEach(input => {
            if (input.value !== "") filled++;
        });
        
        const total = inputs.length;
        const percentage = Math.round((filled / total) * 100);
        
        document.getElementById('progressFill').style.width = `${percentage}%`;
        document.getElementById('studentCount').textContent = `${filled}/${total} completed`;
        
        // Update progress bar color based on completion
        const progressFill = document.getElementById('progressFill');
        progressFill.classList.remove('low', 'medium', 'high');
        
        if (percentage < 30) {
            progressFill.classList.add('low');
        } else if (percentage < 70) {
            progressFill.classList.add('medium');
        } else {
            progressFill.classList.add('high');
        }
    }

    // Attach validation to all mark inputs
    document.querySelectorAll('.mark-input').forEach(input => {
        input.addEventListener('input', function() {
            validateMark(this);
        });
        
        // Initial validation
        validateMark(input);
    });

    // Enhanced search functionality
    window.searchStudent = function() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const rows = document.querySelectorAll('.student-row');
        
        rows.forEach(row => {
            const name = row.querySelector('.student-name span').textContent.toLowerCase();
            const id = row.querySelector('.student-id span').textContent.toLowerCase();
            
            if (name.includes(filter) || id.includes(filter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    };

    // Improved keyboard navigation
    document.querySelectorAll('.mark-input, .position-input').forEach(input => {
        input.addEventListener('keydown', function(e) {
            const row = this.closest('tr');
            const allInputs = Array.from(document.querySelectorAll('.mark-input, .position-input'));
            const currentIndex = allInputs.indexOf(this);
            
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (currentIndex < allInputs.length - 1) allInputs[currentIndex + 1].focus();
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    if (currentIndex > 0) allInputs[currentIndex - 1].focus();
                    break;
                case 'ArrowRight':
                    e.preventDefault();
                    const nextInRow = row.querySelectorAll('.mark-input, .position-input');
                    const rowIndex = Array.from(nextInRow).indexOf(this);
                    if (rowIndex < nextInRow.length - 1) nextInRow[rowIndex + 1].focus();
                    break;
                case 'ArrowLeft':
                    e.preventDefault();
                    const prevInRow = row.querySelectorAll('.mark-input, .position-input');
                    const prevRowIndex = Array.from(prevInRow).indexOf(this);
                    if (prevRowIndex > 0) prevInRow[prevRowIndex - 1].focus();
                    break;
            }
        });
    });

    // Submit Marks Button
    document.getElementById('submitMarksButton').addEventListener('click', function() {
        const emptyInputs = Array.from(document.querySelectorAll('.mark-input')).filter(i => i.value === "");
        
        if (emptyInputs.length > 0) {
            if (confirm(`${emptyInputs.length} marks are empty. Submit anyway?`)) {
                document.getElementById('marksForm').submit();
            }
        } else {
            document.getElementById('marksForm').submit();
        }
    });

    // Analyze Positions Button
    document.getElementById('analyzePositionsButton').addEventListener('click', function() {
        const rows = document.querySelectorAll('.student-row:not(.no-data)');
        const scores = [];
        
        rows.forEach(row => {
            const admno = row.querySelector('.student-id input').value;
            const midterm = parseFloat(row.querySelector('input[name="midterm[]"]').value) || 0;
            const endterm = parseFloat(row.querySelector('input[name="endterm[]"]').value) || 0;
            
            scores.push({ admno, total: midterm + endterm });
        });
        
        // Sort by total score descending
        scores.sort((a, b) => b.total - a.total);
        
        // Assign positions (handling ties)
        let currentPosition = 1;
        scores.forEach((score, index) => {
            if (index > 0 && score.total < scores[index - 1].total) {
                currentPosition = index + 1;
            }
            
            // Update the position in the UI
            const row = Array.from(rows).find(r => 
                r.querySelector('.student-id input').value === score.admno
            );
            
            if (row) {
                const positionInput = row.querySelector('.position-input');
                const ordinalSpan = row.querySelector('.ordinal-suffix');
                
                positionInput.value = currentPosition;
                ordinalSpan.textContent = getOrdinalSuffix(currentPosition);
            }
        });
        
        // Visual feedback
        const btn = this;
        btn.innerHTML = '<i class="fas fa-check"></i> Positions Analyzed';
        btn.classList.add('success');
        
        setTimeout(() => {
            btn.innerHTML = '<i class="fas fa-chart-line"></i> Analyze Positions';
            btn.classList.remove('success');
        }, 2000);
    });
    
    // Helper function for ordinal suffixes
    function getOrdinalSuffix(num) {
        const j = num % 10, k = num % 100;
        if (j == 1 && k != 11) return 'st';
        if (j == 2 && k != 12) return 'nd';
        if (j == 3 && k != 13) return 'rd';
        return 'th';
    }
});
</script>

<style>
:root {
    --primary-color: #4361ee;
    --primary-light: #e0e7ff;
    --secondary-color: #3f37c9;
    --success-color: #4cc9f0;
    --warning-color: #f8961e;
    --danger-color: #f94144;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --gray-color: #6c757d;
    --border-radius: 8px;
    --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', sans-serif;
    background-color: #f5f7fb;
    color: var(--dark-color);
    line-height: 1.6;
}

.marks-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.marks-header {
    background: white;
    border-radius: var(--border-radius);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: var(--box-shadow);
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}

.marks-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-color);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.marks-title i {
    font-size: 1.75rem;
}

.class-info {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.info-badge {
    background: var(--primary-light);
    color: var(--primary-color);
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.progress-indicator {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.progress-bar {
    flex-grow: 1;
    height: 8px;
    background: #e9ecef;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 0.5s ease;
}

.progress-fill.low { background: var(--danger-color); }
.progress-fill.medium { background: var(--warning-color); }
.progress-fill.high { background: var(--success-color); }

.progress-indicator span {
    font-size: 0.9rem;
    color: var(--gray-color);
    font-weight: 500;
    min-width: 100px;
    text-align: right;
}

.marks-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-container {
    position: relative;
    flex-grow: 1;
    max-width: 400px;
}

.search-container i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-color);
}

#searchInput {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #dee2e6;
    border-radius: var(--border-radius);
    font-size: 0.95rem;
    transition: var(--transition);
    background: white;
}

#searchInput:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
}

.action-buttons {
    display: flex;
    gap: 0.75rem;
}

.btn-primary, .btn-secondary {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: var(--border-radius);
    font-weight: 500;
    font-size: 0.95rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--secondary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
}

.btn-secondary {
    background: white;
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
}

.btn-secondary:hover {
    background: var(--primary-light);
    transform: translateY(-2px);
}

.btn-primary.success {
    background: var(--success-color);
}

.marks-table-container {
    background: white;
    border-radius: var(--border-radius);
    overflow: hidden;
    box-shadow: var(--box-shadow);
}

.marks-table {
    width: 100%;
    border-collapse: collapse;
}

.marks-table thead {
    background: var(--primary-color);
    color: white;
}

.marks-table th {
    padding: 1rem;
    font-weight: 500;
    text-align: left;
}

.marks-table th span {
    font-weight: 400;
    opacity: 0.8;
    font-size: 0.85rem;
}

.marks-table td {
    padding: 1rem;
    border-bottom: 1px solid #f1f3f9;
}

.student-row:hover {
    background: rgba(67, 97, 238, 0.03);
}

.serial-number {
    width: 50px;
    text-align: center;
    color: var(--gray-color);
    font-weight: 500;
}

.student-name {
    display: flex;
    align-items: center;
    gap: 1rem;
    font-weight: 500;
}

.student-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.student-id {
    color: var(--gray-color);
    font-size: 0.9rem;
    font-family: monospace;
}

.score-header, .position-header {
    text-align: center;
}

.score-input, .position-display {
    padding: 0.5rem 1rem;
}

.input-container {
    position: relative;
    max-width: 120px;
    margin: 0 auto;
}

.mark-input, .position-input {
    width: 100%;
    padding: 0.75rem;
    border: none;
    border-radius: var(--border-radius);
    font-size: 0.95rem;
    text-align: center;
    background: #f8f9fa;
    transition: var(--transition);
}

.mark-input:focus {
    outline: none;
    background: white;
    box-shadow: 0 0 0 2px var(--primary-light);
}

.input-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: #dee2e6;
    transition: var(--transition);
}

.mark-input:focus ~ .input-border {
    height: 2px;
    background: var(--primary-color);
}

.input-container.valid .input-border {
    background: var(--success-color);
}

.input-container.warning .input-border {
    background: var(--warning-color);
}

.input-container.error .input-border {
    background: var(--danger-color);
}

.input-container.empty .input-border {
    background: #dee2e6;
}

.position-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
}

.position-input {
    max-width: 50px;
    font-weight: 600;
    color: var(--primary-color);
}

.ordinal-suffix {
    font-size: 0.8rem;
    color: var(--gray-color);
}

.no-data td {
    padding: 3rem;
    text-align: center;
    color: var(--gray-color);
}

.no-data i {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: #dee2e6;
}

.no-data span {
    display: block;
    margin-top: 0.5rem;
}

@media (max-width: 768px) {
    .marks-header {
        flex-direction: column;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .marks-controls {
        flex-direction: column;
    }
    
    .search-container {
        max-width: 100%;
    }
    
    .action-buttons {
        width: 100%;
    }
    
    .btn-primary, .btn-secondary {
        flex-grow: 1;
        justify-content: center;
    }
    
    .marks-table {
        display: block;
        overflow-x: auto;
    }
    
    .student-name {
        min-width: 200px;
    }
}
</style>
