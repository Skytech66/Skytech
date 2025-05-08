<?php include 'header.php'; ?>
<?php include 'config.php'; // Include the config file ?>

<?php
try {
    // Create a new PDO instance for SQLite
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Fetch distinct classes from the marks table
    $classStmt = $pdo->query("SELECT DISTINCT class FROM marks");
    $classes = $classStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch distinct subjects from the marks table
    $subjectStmt = $pdo->query("SELECT DISTINCT subject FROM marks");
    $subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Scores Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .navbar {
            background: linear-gradient(to right, #A0C8E0, #B2E0F9);
            color: #333;
            padding: 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-links {
            background: none;
            color: #333;
            padding: 10px;
        }
        .nav-links a {
            color: #333;
            margin-right: 15px;
            text-decoration: none;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        .dropdowns {
            display: flex;
            gap: 10px;
        }
        select {
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 15px;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        th {
            background-color: #343a40;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        tr:hover {
            background-color: #e2e6ea;
        }
        .editable {
            cursor: pointer;
            color: #007bff;
        }
        .editable:hover {
            text-decoration: underline;
        }
        .commit-btn {
            display: none;
            cursor: pointer;
            color: #28a745;
            margin-left: 5px;
        }
        .delete-btn {
            color: #dc3545;
            cursor: pointer;
            font-size: 20px;
            padding: 5px 10px;
            border: none;
            background: none;
            transition: background-color 0.3s;
        }
        .delete-btn:hover {
            background-color: #f8d7da;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>

<header class="navbar">
    <h1>Exam Scores Dashboard</h1>
    <div class="dropdowns">
        <select id="classSelect">
            <option value="">Select Class</option>
            <?php foreach ($classes as $class): ?>
                <option value="<?php echo htmlspecialchars($class['class']); ?>">
                    <?php echo htmlspecialchars($class['class']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select id="subjectSelect">
                        <option value="">Select Subject</option>
            <?php foreach ($subjects as $subject): ?>
                <option value="<?php echo htmlspecialchars($subject['subject']); ?>">
                    <?php echo htmlspecialchars($subject['subject']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="nav-links">
        <a href="change_password.php"><i class="fas fa-key"></i> Change Password</a>
        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <a href="help.php"><i class="fas fa-question-circle"></i> Help</a>
        <button id="exportCsv" class="btn btn-success" style="margin-left: 15px;"><i class="fas fa-file-csv"></i> Export as CSV</button>
    </div>
</header>

<main>
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Search by Student Name..." class="form-control" style="margin-bottom: 20px;">
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="scoresTable">
            <thead class="thead-light">
                <tr>
                    <th>#</th>
                    <th>Student Name</th>
                    <th>AdmNo</th>
                    <th>Class S.(50%)</th>
                    <th>Exams(50%)</th>
                    <th>Total(100%)</th>
                    <th>Remarks</th>
                    <th>Position</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dynamic content will be inserted here -->
            </tbody>
        </table>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
<script>
    document.getElementById('classSelect').addEventListener('change', updateTable);
    document.getElementById('subjectSelect').addEventListener('change', updateTable);
    document.getElementById('exportCsv').addEventListener('click', function() {
        const selectedClass = document.getElementById('classSelect').value;
        const selectedSubject = document.getElementById('subjectSelect').value;
        if (selectedClass && selectedSubject) {
            // Redirect to the export CSV script
            window.location.href = `export_csv.php?class=${selectedClass}&subject=${selectedSubject}`;

            // Show success message after a short delay
            setTimeout(() => {
                swal("Exported Successfully!", "The data has been exported as a CSV file.", "success");
            }, 1000);
        } else {
            alert("Please select both class and subject to export.");
        }
    });

    function updateTable() {
        const selectedClass = document.getElementById('classSelect').value;
        const selectedSubject = document.getElementById('subjectSelect').value;
        const tableBody = document.getElementById('scoresTable').getElementsByTagName('tbody')[0];
        
        // Clear previous rows
        tableBody.innerHTML = '';

        if (selectedClass && selectedSubject) {
            // Fetch data from the server
            fetch(`fetch_scores.php?class=${selectedClass}&subject=${selectedSubject}`)
                .then(response => response.json())
                .then(data => {
                    data.forEach((score, index) => {
                        const row = tableBody.insertRow();
                        row.insertCell(0).textContent = index + 1; // Row number
                        row.insertCell(1).innerHTML = `<strong class="editable" data-id="${score.marksid}" data-field="student">${score.student}</strong><span class="commit-btn" data-id="${score.marksid}">&#10003;</span>`;
                        row.insertCell(2).innerHTML = `<span class="editable" data-id="${score.marksid}" data-field="admno">${score.admno}</span><span class="commit-btn" data-id="${score.marksid}">&#10003;</span>`;
                                                row.insertCell(3).innerHTML = `<span class="editable" data-id="${score.marksid}" data-field="midterm">${score.midterm}</span><span class="commit-btn" data-id="${score.marksid}">&#10003;</span>`;
                        row.insertCell(4).innerHTML = `<span class="editable" data-id="${score.marksid}" data-field="endterm">${score.endterm}</span><span class="commit-btn" data-id="${score.marksid}">&#10003;</span>`;
                        row.insertCell(5).textContent = score.average;
                        row.insertCell(6).innerHTML = `<span class="editable" data-id="${score.marksid}" data-field="remarks">${score.remarks}</span><span class="commit-btn" data-id="${score.marksid}">&#10003;</span>`;
                        row.insertCell(7).innerHTML = `<strong>${formatPosition(score.position)}</strong>`;
                        row.insertCell(8).innerHTML = `<button class="delete-btn" data-id="${score.marksid}"><i class="fas fa-trash-alt"></i> Delete</button>`;
                    });

                    // Add double-click event listeners for inline editing
                    addEditListeners();
                    addDeleteListeners(); // Add delete listeners
                })
                .catch(error => console.error('Error fetching data:', error));
        }
    }

    function addEditListeners() {
        const editableElements = document.querySelectorAll('.editable');
        editableElements.forEach(element => {
            element.addEventListener('dblclick', function() {
                const currentValue = this.textContent;
                const field = this.dataset.field;
                const marksid = this.dataset.id;

                // Create an input field for editing
                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue;
                input.className = 'form-control';
                this.innerHTML = '';
                this.appendChild(input);

                // Show the commit button
                const commitBtn = document.querySelector(`.commit-btn[data-id="${marksid}"]`);
                commitBtn.style.display = 'inline';

                // Focus on the input field
                input.focus();

                // Save changes on commit button click
                commitBtn.onclick = () => saveChanges(marksid, field, input.value, this, commitBtn);

                // Cancel editing on blur
                input.addEventListener('blur', () => {
                    this.innerHTML = currentValue;
                    commitBtn.style.display = 'none';
                });
            });
        });
    }

    function saveChanges(marksid, field, value, element, commitBtn) {
        fetch('update_score.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ marksid, field, value }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                element.textContent = value;
                if (field === 'student') {
                    element.innerHTML = `<strong>${value}</strong>`;
                }
                commitBtn.style.display = 'none';
            } else {
                alert('Error updating the score. Please try again.');
            }
        })
        .catch(error => console.error('Error saving changes:', error));
    }

    function formatPosition(position) {
        if (position === 1) return position + 'st';
        if (position === 2) return position + 'nd';
        if (position === 3) return position + 'rd';
        return position + 'th';
    }

    // Add delete listeners for each row
    function addDeleteListeners() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const marksid = this.dataset.id;
                if (confirm("Are you sure you want to delete this row?")) {
                    // Remove the row from the table
                    const row = this.closest('tr');
                    row.parentNode.removeChild(row);

                    // Send a request to the server to delete the record from the database
                    fetch('delete_score.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ id: marksid }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            alert('Error deleting the score: ' + (data.error || 'Unknown error'));
                        }
                    })
                    .catch(error => console.error('Error deleting record:', error));
                }
            });
        });
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#scoresTable tbody tr');

        tableRows.forEach(row => {
            const studentName = row.cells[1].textContent.toLowerCase(); // Assuming student name is in the second cell
            if (studentName.includes(searchValue)) {
                row.style.display = ''; // Show row
            } else {
                row.style.display = 'none'; // Hide row
            }
        });
    });
</script>

</body>
</html>