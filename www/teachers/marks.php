<?php 
require_once "header.php"; 
$year = isset($_POST['year']) ? $_POST['year'] : '';
$exam = isset($_POST['exam']) ? $_POST['exam'] : '';
$class = isset($_POST['class']) ? $_POST['class'] : '';
$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
$totalStudents = 0; // Initialize the variable

// SQL query to fetch students
$sql = "SELECT * from student where `year` like '$year' and `class` like '$class'";
$res = $conn->query($sql);

// Check if the query executed successfully
if (!$res) {
    echo "Error executing query: " . $conn->lastErrorMsg();
}

// Fetch all results into an array
$students = [];
while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
    $students[] = $row; // Add each row to the array
}

// Get the total number of students
$totalStudents = count($students);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mark Sheet - <?php echo htmlspecialchars($subject); ?></title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --success-color: #4cc9f0;
            --danger-color: #f72585;
            --warning-color: #f8961e;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --border-radius: 8px;
            --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fb;
            color: var(--dark-color);
            line-height: 1.6;
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .header h4 {
            font-size: 1.2rem;
            font-weight: 500;
            color: #6c757d;
        }

        .search-container {
            margin-bottom: 1.5rem;
            position: relative;
        }

        .search-container i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        #searchInput {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            font-size: 1rem;
            border: 1px solid #e9ecef;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background-color: #f8f9fa;
        }

        #searchInput:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: white;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .actions-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            border-radius: var(--border-radius);
            border: none;
            cursor: pointer;
            transition: var(--transition);
            gap: 0.5rem;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.25);
        }

        .btn-success {
            background-color: #2ecc71;
            color: white;
        }

        .btn-success:hover {
            background-color: #27ae60;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(46, 204, 113, 0.25);
        }

        .student-count {
            font-size: 0.9rem;
            color: #6c757d;
            background-color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            font-weight: 500;
        }

        .table-container {
            overflow-x: auto;
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 800px;
        }

        thead th {
            position: sticky;
            top: 0;
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            padding: 1rem;
            text-align: center;
        }

        tbody tr {
            transition: var(--transition);
        }

        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        tbody tr:hover {
            background-color: #e9f5ff;
        }

        td {
            padding: 1rem;
            text-align: center;
            border-bottom: 1px solid #e9ecef;
        }

        .student-name {
            font-weight: 500;
            color: var(--dark-color);
        }

        .student-id {
            font-weight: 500;
            color: #6c757d;
        }

        .mark-input, .position-input {
            width: 100%;
            max-width: 100px;
            padding: 0.75rem;
            font-size: 1rem;
            font-weight: 500;
            text-align: center;
            border: 1px solid #e9ecef;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background-color: white;
        }

        .mark-input:focus, .position-input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }

        .empty {
            background-color: #f8f9fa;
        }

        .valid {
            background-color: #e6f7ee;
            border-color: #2ecc71;
        }

        .exceed {
            background-color: #fff3e0;
            border-color: #ff9800;
        }

        .invalid {
            background-color: #ffebee;
            border-color: #f44336;
        }

        .position-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.25rem;
        }

        .ordinal-suffix {
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0 0.5rem;
            }
            
            .card {
                padding: 1.5rem 1rem;
            }
            
            .header h2 {
                font-size: 1.5rem;
            }
            
            .header h4 {
                font-size: 1rem;
            }
            
            .actions-container {
                flex-direction: column;
                align-items: stretch;
            }
            
            .btn {
                width: 100%;
            }
            
            .student-count {
                text-align: center;
                width: 100%;
            }
            
            td, th {
                padding: 0.75rem 0.5rem;
            }
            
            .mark-input, .position-input {
                max-width: 80px;
                padding: 0.5rem;
            }
        }

        /* Loading spinner */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="header">
                <h2>Mark Sheet - <?php echo htmlspecialchars($subject); ?></h2>
                <h4>Class: <?php echo htmlspecialchars($class); ?> • <?php echo htmlspecialchars($exam); ?> Exam • <?php echo htmlspecialchars($year); ?></h4>
            </div>

            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by student name or admission number..." onkeyup="searchStudent()" />
            </div>

            <form id="marksForm" action="submit_scores.php" method="POST" enctype="multipart/form-data">
                <div class="actions-container">
                    <button class="btn btn-primary" type="button" id="submitMarksButton">
                        <i class="fas fa-paper-plane"></i>
                        <span>Submit Marks</span>
                        <div class="spinner" id="submitSpinner"></div>
                    </button>
                    <button class="btn btn-success" type="button" id="analyzePositionsButton">
                        <i class="fas fa-calculator"></i>
                        <span>Analyze Positions</span>
                        <div class="spinner" id="analyzeSpinner"></div>
                    </button>
                    <div class="student-count" id="studentCount">Filled: 0 of <?php echo $totalStudents; ?> students</div>
                </div>

                <input type="hidden" name="uuser" value="<?php echo $session_id; ?>" />
                <input type="hidden" name="year" value="<?php echo $year; ?>" />
                <input type="hidden" name="exam" value="<?php echo $exam; ?>" />
                <input type="hidden" name="class" value="<?php echo $class; ?>" />
                <input type="hidden" name="subject" value="<?php echo $subject; ?>" />
                
                <div class="table-container">
                    <table id="pager">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Admission No.</th>
                                <th>Class Score (50%)</th>
                                <th>Exam Score (50%)</th>
                                <th>Position</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($totalStudents === 0): ?>
                                <tr>
                                    <td colspan="6" class="text-center">No students found for the selected criteria.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($students as $index => $row): ?>
                                    <tr class="student-row">
                                        <td><?php echo $index + 1; ?></td>
                                        <td class="student-name">
                                            <input type="hidden" name="jina[]" value="<?php echo htmlspecialchars($row['name']); ?>" />
                                            <?php echo htmlspecialchars($row['name']); ?>
                                        </td>
                                        <td class="student-id">
                                            <input type="hidden" name="regno[]" value="<?php echo htmlspecialchars($row['admno']); ?>" />
                                            <?php echo htmlspecialchars($row['admno']); ?>
                                        </td>
                                        <td>
                                            <input type="number" class="mark-input" name="midterm[]" max="50" min="0" step="0.1" placeholder="0-50" />
                                        </td>
                                        <td>
                                            <input type="number" class="mark-input" name="endterm[]" max="50" min="0" step="0.1" placeholder="0-50" />
                                        </td>
                                        <td>
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
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Color coding for input fields
        function updateInputStyle(input) {
            const value = parseFloat(input.value) || 0;
            const max = parseFloat(input.max) || 50;
            
            // Remove all classes
            input.classList.remove('empty', 'valid', 'exceed', 'invalid');
            
            if (input.value === '') {
                input.classList.add('empty');
            } else if (value < 0) {
                input.classList.add('invalid');
            } else if (value > max) {
                input.classList.add('exceed');
            } else {
                input.classList.add('valid');
            }
            
            updateFilledCount();
        }

        // Update filled count
        function updateFilledCount() {
            const filled = document.querySelectorAll('.mark-input:not(.empty)').length / 2;
            document.getElementById('studentCount').textContent = `Filled: ${filled} of ${<?php echo $totalStudents; ?>} students`;
        }

        // Initialize input styles and event listeners
        document.querySelectorAll('.mark-input').forEach(input => {
            updateInputStyle(input);
            input.addEventListener('input', () => updateInputStyle(input));
        });

        // Search functionality
        window.searchStudent = function() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.student-row').forEach(row => {
                const name = row.querySelector('.student-name').textContent.toLowerCase();
                const admno = row.querySelector('.student-id').textContent.toLowerCase();
                row.style.display = (name.includes(searchTerm) || admno.includes(searchTerm) ? '' : 'none';
            });
        };

        // Keyboard navigation
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('keydown', function(e) {
                const tr = this.closest('tr');
                const inputs = Array.from(tr.querySelectorAll('input[type="number"]'));
                const currentIndex = inputs.indexOf(this);
                
                switch(e.key) {
                    case 'ArrowUp':
                        e.preventDefault();
                        if (tr.previousElementSibling) {
                            const prevInputs = tr.previousElementSibling.querySelectorAll('input[type="number"]');
                            if (prevInputs[currentIndex]) prevInputs[currentIndex].focus();
                        }
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        if (tr.nextElementSibling) {
                            const nextInputs = tr.nextElementSibling.querySelectorAll('input[type="number"]');
                            if (nextInputs[currentIndex]) nextInputs[currentIndex].focus();
                        }
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        if (currentIndex > 0) inputs[currentIndex - 1].focus();
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        if (currentIndex < inputs.length - 1) inputs[currentIndex + 1].focus();
                        break;
                }
            });
        });

        // Submit marks
        document.getElementById('submitMarksButton').addEventListener('click', function() {
            const emptyInputs = document.querySelectorAll('.mark-input.empty');
            const btn = this;
            const spinner = btn.querySelector('.spinner');
            
            if (emptyInputs.length > 0 && !confirm('Some marks are empty. Proceed anyway?')) {
                return;
            }
            
            btn.disabled = true;
            spinner.style.display = 'block';
            
            fetch('submit_scores.php', {
                method: 'POST',
                body: new FormData(document.getElementById('marksForm'))
            })
            .then(response => {
                if (response.ok) {
                    return response.text();
                }
                throw new Error('Network response was not ok.');
            })
            .then(data => {
                showToast('Marks submitted successfully!', 'success');
            })
            .catch(error => {
                showToast('Error submitting marks: ' + error.message, 'error');
            })
            .finally(() => {
                btn.disabled = false;
                spinner.style.display = 'none';
            });
        });

        // Analyze positions
        document.getElementById('analyzePositionsButton').addEventListener('click', function() {
            const btn = this;
            const spinner = btn.querySelector('.spinner');
            const rows = document.querySelectorAll('.student-row');
            const data = Array.from(rows).map(row => {
                return {
                    admno: row.querySelector('input[name="regno[]"]').value,
                    midterm: parseFloat(row.querySelector('input[name="midterm[]"]').value) || 0,
                    endterm: parseFloat(row.querySelector('input[name="endterm[]"]').value) || 0
                };
            });
            
            btn.disabled = true;
            spinner.style.display = 'block';
            
            fetch('analyze_positions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                data.positions.forEach(pos => {
                    const row = Array.from(rows).find(r => 
                        r.querySelector('input[name="regno[]"]').value === pos.admno
                    );
                    if (row) {
                        const positionInput = row.querySelector('.position-input');
                        const ordinalSpan = row.querySelector('.ordinal-suffix');
                        positionInput.value = pos.position;
                        ordinalSpan.textContent = pos.ordinal;
                    }
                });
                showToast('Positions analyzed successfully!', 'success');
            })
            .catch(error => {
                showToast('Error analyzing positions: ' + error.message, 'error');
            })
            .finally(() => {
                btn.disabled = false;
                spinner.style.display = 'none';
            });
        });

        // Toast notification
        function showToast(message, type) {
            const toast = document.createElement('div');
            toast.style.position = 'fixed';
            toast.style.bottom = '20px';
            toast.style.right = '20px';
            toast.style.padding = '12px 24px';
            toast.style.borderRadius = '4px';
            toast.style.color = 'white';
            toast.style.fontWeight = '500';
            toast.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.15)';
            toast.style.zIndex = '1000';
            toast.style.transition = 'all 0.3s ease';
            toast.style.transform = 'translateY(20px)';
            toast.style.opacity = '0';
            
            if (type === 'success') {
                toast.style.backgroundColor = '#2ecc71';
            } else {
                toast.style.backgroundColor = '#e74c3c';
            }
            
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.transform = 'translateY(0)';
                toast.style.opacity = '1';
            }, 10);
            
            setTimeout(() => {
                toast.style.transform = 'translateY(20px)';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    });
    </script>
</body>
</html>

<?php require_once "../include/footer.php"; ?>
