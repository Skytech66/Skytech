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

<!-- Include Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="container mt-5">
    <h2 class="mb-4 text-center text-dark font-weight-bold">Mark Sheet (<?php echo htmlspecialchars($subject); ?>)</h2> 
    
    <h4 class="text-center text-dark font-weight-bold mb-4">Class: <?php echo htmlspecialchars($class); ?></h4> 

    <form id="marksForm" action="submit_scores.php" method="POST" enctype="multipart/form-data">
        <div class="mb-4 text-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Student Name or ADMNO" onkeyup="searchStudent()" />
        </div>

        <div class="d-flex justify-content-center align-items-center mb-4">
            <button class="btn" style="background-color: #2ECC71; color: white; margin-right: 10px;" type="button" id="submitMarksButton">Submit Marks</button>
            <button class="btn btn-success" type="button" id="analyzePositionsButton">Analyze Positions</button>
            <p id="studentCount" class="ml-3">Filled: 0 of <?php echo $totalStudents; ?> students</p>
        </div>

        <input type="hidden" name="uuser" value="<?php echo $session_id; ?>" />
        <input type="hidden" name="year" value="<?php echo $year; ?>" />
        <input type="hidden" name="exam" value="<?php echo $exam; ?>" />
        <input type="hidden" name="class" value="<?php echo $class; ?>" />
        <input type="hidden" name="subject" value="<?php echo $subject; ?>" />
        
        <div class="table-responsive">
            <table id="pager" class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">#</th> <!-- Row Number Header -->
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">Student Name</th>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">ADMNO</th>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">Class Score (50%)</th>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">Exam Score (50%)</th>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">Position</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        if ($totalStudents === 0) {
                            // Display a message if no students are found
                            echo "<tr><td colspan='6' class='text-center'>No students found for the selected year and class.</td></tr>";
                        } else {
                            $rowNumber = 1; // Initialize row number
                            foreach ($students as $row) {
                    ?>
                        <tr class="student-row">
                            <td class="text-center student-number">
                                <?php echo $rowNumber++; // Display row number and increment ?>
                            </td>
                                                        <td class="text-center student-name">
                                <input type="hidden" class="form-control" name="jina[]" value="<?php echo htmlspecialchars($row['name']); ?>" />
                                <strong><?php echo htmlspecialchars($row['name']); ?></strong>
                            </td>
                            <td class="text-center student-id">
                                <input type="hidden" class="form-control" name="regno[]" value="<?php echo htmlspecialchars($row['admno']); ?>" />
                                <span><?php echo htmlspecialchars($row['admno']); ?></span>
                            </td>
                            <td class="text-center score-column">
                                <input type="number" class="form-control mark-input" name="midterm[]" max="50" />
                            </td>
                            <td class="text-center score-column">
                                <input type="number" class="form-control mark-input" name="endterm[]" max="50" />
                            </td>
                            <td class="text-center position-column">
                                <input type="number" class="form-control position-input" name="position[]" min="1" readonly />
                                <span class="ordinal-suffix"></span>
                            </td>
                        </tr>
                    <?php 
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="pageNavPosition" class="pager-nav"></div>
    </form>
</div>

<?php require_once "../include/footer.php"; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to check marks and update styles
    function checkMarks(input) {
        var value = input.value;

        // Remove all previous color classes
        input.classList.remove('empty', 'valid', 'invalid', 'exceed');

        // Apply colors based on conditions
        if (value === "") {
            input.classList.add('empty'); // Empty input (light blue)
            input.closest('tr').classList.add('empty-row'); // Add class to the row
        } else {
            input.closest('tr').classList.remove('empty-row'); // Remove class from the row
        }

        if (value > 50) {
            input.classList.add('exceed'); // Exceeds 50 (light yellow)
        } else if (value >= 0 && value <= 50) {
            input.classList.add('valid'); // Valid input (light green)
        } else {
            input.classList.add('invalid'); // Invalid input (light red)
        }

        // Update the filled count
        updateFilledCount();
    }

    // Attach the checkMarks function to input elements
    document.querySelectorAll('.mark-input').forEach(function(input) {
        input.addEventListener('input', function() {
            checkMarks(this);
        });
    });

    // Function to update filled count
    function updateFilledCount() {
        const rows = document.querySelectorAll('.student-row');
        let filledCount = 0;

        rows.forEach(row => {
            const midtermInput = row.querySelector('input[name="midterm[]"]');
            const endtermInput = row.querySelector('input[name="endterm[]"]');
            
            // Check if both inputs are filled
            if (midtermInput.value !== "" && endtermInput.value !== "") {
                filledCount++;
            }
        });

        const totalStudents = <?php echo $totalStudents; ?>; // Get total students from PHP
        document.getElementById('studentCount').textContent = `Filled: ${filledCount} of ${totalStudents} students`;
    }

    // Search functionality
    window.searchStudent = function() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById('searchInput');
        filter = input.value.toLowerCase();
        table = document.getElementById('pager');
        tr = table.getElementsByTagName('tr');

        for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
            td = tr[i].getElementsByTagName('td');
            if (td) {
                var studentName = td[1].textContent || td[1].innerText; // Adjusted index for name
                var studentAdmno = td[2].textContent || td[2].innerText; // Adjusted index for ADMNO
                txtValue = studentName + " " + studentAdmno;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    // Arrow key navigation
    const markInputs = document.querySelectorAll('.mark-input, .position-input');

        markInputs.forEach(input => {
        input.addEventListener('keydown', function(event) {
            const currentRow = this.closest('tr');
            const currentIndex = Array.from(currentRow.children).indexOf(this.parentElement);
            const totalColumns = currentRow.children.length;

            switch (event.key) {
                case 'ArrowDown':
                    const nextRow = currentRow.nextElementSibling;
                    if (nextRow) {
                        const nextInput = nextRow.children[currentIndex].querySelector('input');
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                    event.preventDefault();
                    break;

                case 'ArrowUp':
                    const prevRow = currentRow.previousElementSibling;
                    if (prevRow) {
                        const prevInput = prevRow.children[currentIndex].querySelector('input');
                        if (prevInput) {
                            prevInput.focus();
                        }
                    }
                    event.preventDefault();
                    break;

                case 'ArrowRight':
                    if (currentIndex < totalColumns - 1) {
                        const nextInput = currentRow.children[currentIndex + 1].querySelector('input');
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                    event.preventDefault();
                    break;

                case 'ArrowLeft':
                    if (currentIndex > 0) {
                        const prevInput = currentRow.children[currentIndex - 1].querySelector('input');
                        if (prevInput) {
                            prevInput.focus();
                        }
                    }
                    event.preventDefault();
                    break;
            }
        });
    });

    // Submit Marks Button Functionality
    document.getElementById('submitMarksButton').addEventListener('click', function() {
        const markInputs = document.querySelectorAll('.mark-input');
        let someEmpty = false;

        markInputs.forEach(input => {
            if (input.value === "") {
                someEmpty = true; // At least one input is empty
            }
        });

        if (someEmpty) {
            // Prompt the user if they want to proceed with empty fields
            if (confirm('Some rows have been left empty. Would you like to proceed?')) {
                submitForm();
            }
        } else {
            // If no empty fields, submit directly
            submitForm();
        }
    });

    // Function to handle form submission
    function submitForm() {
        const formData = new FormData(document.getElementById('marksForm'));

        fetch('submit_scores.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (response.ok) {
                alert('Marks submitted successfully!');
                window.location.reload(); // Reload the page
            } else {
                alert('There was an error submitting the marks.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting the marks.');
        });
    }

    // Analyze Positions Button Functionality
    document.getElementById('analyzePositionsButton').addEventListener('click', function() {
        const rows = document.querySelectorAll('.student-row');
        const scores = [];
        
        rows.forEach(row => {
            const admno = row.querySelector('.student-id input').value;
            const midtermScore = row.querySelector('input[name="midterm[]"]').value;
            const endtermScore = row.querySelector('input[name="endterm[]"]').value;
            
            const totalScore = (parseFloat(midtermScore) || 0) + (parseFloat(endtermScore) || 0);
            
            scores.push({ admno, totalScore });
        });

        fetch('analyze_positions.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(scores)
        })
        .then(response => response.json())
        .then(data => {
            data.positions.forEach(position => {
                const row = Array.from(rows).find(r => r.querySelector('.student-id input').value === position.admno);
                if (row) {
                    // Set the numeric position in the input field
                    row.querySelector('.position-column input').value = position.position;

                    // Display the ordinal position next to the input field
                    const ordinalSuffix = row.querySelector('.ordinal-suffix');
                    ordinalSuffix.textContent = position.ordinal; // e.g., "1st", "2nd"
                }
            });
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>

<style>
/* General Styles */
body {
    font-family: 'Roboto', sans-serif;
    background-color: #fafafa;
    color: #333;
}

.container {
    max-width: 1100px;
    margin-top: 30px;
}

/* Search Bar Styling */
#searchInput {
    width: 100%;
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 6px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

#searchInput:focus {
    outline: none;
    border-color: #007bff;
}

/* Table Styling */
table {
    margin-top: 20px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border-collapse: collapse; 
}

table th {
    background-color: #3b4c6b; /* Darker Blue */
    color: white;
    font-size: 1.1em;
    font-weight: bold;
    padding: 12px 10px;
}

table td {
    text-align: center;
    vertical-align: middle;
    padding: 12px 10px;
}

table tr:nth-child(even) {
    background-color: #f9f9f9;
}

table tr:hover {
    background-color: #d9f1ff;
    cursor: pointer;
}

table tr td {
    font-size: 1em;
}

/* Empty Row Styling */
.empty-row {
    background-color: #e0f7fa; /* Light blue for empty rows */
}

/* Input Styling */
input[type="number"].form-control {
    width: 80%;
    margin: 10px auto;
    padding: 12px;
    font-size: 1.2rem; /* Increased font size for better readability */
    border-radius: 6px;
    border: 1px solid #ccc;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    color: #333; /* Set text color for better contrast */
    text-align: center; /* Center text horizontally */
    height: 50px; /* Set height to make the input taller */
    line-height: 30px; /* Adjust vertical centering */
}

/* Centering the text within the input */
input[type="number"].mark-input {
    max-width: 140px;
    font-weight: bold; /* Bold text for better readability */
}

/* Score Column Styling */
.score-column input {
    font-size: 1.1em; /* Slightly larger font size for visibility */
    font-weight: bold;
    background-color: #f0f8ff;
    border: 2px solid #007bff;
    padding: 12px; /* Added padding for better text placement */
}

/* Position Column Styling */
.position-column input {
    font-size: 1.1em; /* Slightly larger font size for visibility */
    font-weight: bold;
    background-color: #f0f8ff;
    border: 2px solid #007bff;
    padding: 12px; /* Added padding for better text placement */
}

/* Table Column Styling */
.student-name,
.student-id {
    font-weight: 500;
    font-size: 1.1em;
    color: #343a40;
}

.student-name {
    color: #5a5a5a;
}

.student-id {
    color: #007bff;
}

/* Color Classes */
input.empty {
    background-color: #ffe6e6; /* Light red */
    color: #333; /* Ensure text is readable */
}

input.valid {
    background-color: #e1f7e1; /* Light green */
    color: #333; /* Ensure text is readable */
}

input.exceed {
    background-color: #fff9c4; /* Light yellow */
    color: #333; /* Ensure text is readable */
}

input.invalid {
    background-color: #f8d7da; /* Light red */
    color: #333; /* Ensure text is readable */
}

/* Submit Button Styling */
button {
    font-size: 1.1em;
    padding: 12px;
    background: linear-gradient(135deg, #007bff, #00c6ff);
    color: white;
    border: none;
    border-radius: 6px;
    transition: background-color 0.3s ease, transform 0.2s ease-in-out;
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.2);
}

button:hover {
    background: linear-gradient(135deg,    #00c6ff, #007bff);
    transform: translateY(-3px);
}

button:focus {
    outline: none;
}

/* Heading */
h2 {
    font-size: 2.5em; /* Increased font size for a more professional look */
    color: #333;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 40px;
    letter-spacing: 1px;
}

/* Mobile Adjustments */
@media (max-width: 768px) {
    .container {
        width: 100%;
        padding: 15px;
    }

    #searchInput {
        width: 100%;
    }

    table th, table td {
        font-size: 0.9em; /* Reduce font size for smaller screens */
        padding: 10px; /* Adjust padding for better fitting */
    }

    .mark-input, .position-input {
        width: 100%; /* Make input fields more flexible */
    }

    h2 {
        font-size: 1.8em; /* Adjust heading size for smaller screens */
    }

    button {
        font-size: 1em; /* Adjust button size */
    }

    #studentCount {
        font-size: 0.9em; /* Adjust font size for mobile */
    }
}
</style>