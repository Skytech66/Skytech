<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Entry</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #0056b3;
            --accent: #17a2b8;
            --background: #f8f9fa;
            --container-bg: #ffffff;
            --border: #ced4da;
            --error-bg: #f8d7da;
            --error-color: #dc3545;
        }
        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--background);
            margin: 0;
            padding: 40px;
        }
        .ai-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            text-align: center;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 2em;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: var(--container-bg);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid var(--border);
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        label {
            margin-top: 10px;
        }
        select, input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
            border-radius: 5px;
            font-size: 1em;
            transition: border-color 0.3s;
        }
        select:focus, input[type="text"]:focus {
            border-color: var(--primary);
            outline: none;
        }
        button {
            padding: 14px 20px;
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: all 0.3s ease;
        }
        button:hover {
            background-color: var(--accent);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .error-message {
            color: var(--error-color);
            font-size: 0.9em;
            margin-top: 5px;
            background-color: var(--error-bg);
            padding: 5px;
            border-radius: 5px;
        }
        .row-actions {
            display: flex;
            justify-content: center;
        }
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            h1 {
                font-size: 1.5em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="ai-header">
            <h3><i class="fas fa-user-graduate"></i> Student Entry</h3>
            <p class="mb-0"><i class="fas fa-pencil-alt"></i> Enter student details below</p>
        </div>

        <button class="btn btn-danger mb-3" onclick="window.location.href='index.php?exams'">← Back</button>
        
        <form action="submit.php" method="post" id="studentForm">
            <div class="mb-3">
                                <label for="class">Class:</label>
                <select id="class" name="class" required>
                    <option value="">Select Class</option>
                    <option value="Basic Six A">Basic Six A</option>
                    <option value="Form Three">Form Three</option>
                    <option value="Form Two">Form Two</option>
                    <option value="Form One">Form One</option>
                    <option value="Basic Six B">Basic Six B</option>
                    <option value="Basic Five">Basic Five</option>
                    <option value="Basic Four">Basic Four</option>
                    <option value="Basic Three B">Basic Three B</option>
                    <option value="Basic Three A">Basic Three A</option>
                    <option value="Basic Two">Basic Two</option>
                    <option value="Basic One">Basic One</option>
                    <option value="KG2">KG2</option>
                    <option value="Creche">Creche</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="year">Year:</label>
                <select id="year" name="year" required>
                    <option value="">Select Year</option>
                    <option value="2025">2025</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="rowCount">Add Rows:</label>
                <select id="rowCount" name="rowCount">
                    <option value="1">+1</option>
                    <option value="10">+10</option>
                    <option value="20">+20</option>
                    <option value="30">+30</option>
                </select>
            </div>

            <button type="button" class="btn btn-primary mb-3" onclick="addRow()">Add Row</button>

            <table id="studentTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Admission Number</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <input type="text" name="name[]" onblur="assignAdmNo(this)" tabindex="1" autocomplete="off">
                            <div class="error-message" id="nameError0"></div>
                        </td>
                        <td><input type="text" name="admno[]" readonly></td>
                        <td class="row-actions">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let rowCount = 1; // To keep track of the number of rows
        let currentRowIndex = 0; // To keep track of the current row index

        function assignAdmNo(input) {
            const row = input.closest('tr');
            const admNoInput = row.querySelector('input[name="admno[]"]');
            const nameValue = input.value.trim();

            if (nameValue) {
                const admNo = 'ADM' + Math.floor(Math.random() * 10000);
                admNoInput.value = admNo;
            } else {
                admNoInput.value = '';
            }
        }

        function addRow() {
            const tableBody = document.querySelector('#studentTable tbody');
            const rowCountValue = parseInt(document.getElementById('rowCount').value);
            
            for (let i = 0; i < rowCountValue; i++) {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
                    <td>${rowCount + 1}</td>
                    <td>
                        <input type="text" name="name[]" onblur="assignAdmNo(this)" tabindex="${rowCount + 2}" autocomplete="off">
                        <div class="error-message" id="nameError${rowCount + 1}"></div>
                    </td>
                    <td><input type="text" name="admno[]" readonly></td>
                    <td class="row-actions">
                        <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button>
                    </td>
                `;
                tableBody.appendChild(newRow);
                rowCount++;
            }
            updateRowNumbers();
        }

        function removeRow(button) {
                        const row = button.closest('tr');
            row.remove();
            updateRowNumbers();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#studentTable tbody tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1; // Update row number
                const input = row.querySelector('input[name="name[]"]');
                input.setAttribute('tabindex', index + 2); // Update tabindex
            });
        }

        document.getElementById('studentForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const rows = document.querySelectorAll('#studentTable tbody tr');
            const validRows = [];
            let hasError = false;

            rows.forEach((row, index) => {
                const nameInput = row.querySelector('input[name="name[]"]');
                const admNoInput = row.querySelector('input[name="admno[]"]');
                const nameError = row.querySelector(`#nameError${index}`);

                if (nameInput.value.trim() && admNoInput.value.trim()) {
                    validRows.push({
                        name: nameInput.value,
                        admno: admNoInput.value
                    });
                    nameError.textContent = ''; // Clear any previous error
                } else {
                    hasError = true;
                    nameError.textContent = 'Please enter a valid name.';
                }
            });

            if (hasError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please fix the errors in the form.',
                });
                return;
            }

            if (validRows.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter at least one valid student.',
                });
                return;
            }

            // Create a FormData object to send the form data
            const formData = new FormData(this);
            validRows.forEach((row, index) => {
                formData.append(`valid_name[${index}]`, row.name);
                formData.append(`valid_admno[${index}]`, row.admno);
            });

            // Attempt to send the form data using fetch
            fetch('submit.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                    }).then(() => {
                        window.location.href = 'form.php'; // Redirect to form.php
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message,
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong! Saving data locally.',
                });
                saveToLocalStorage(validRows);
            });
        });

        function saveToLocalStorage(validRows) {
            localStorage.setItem('studentData', JSON.stringify(validRows));
            Swal.fire({
                icon: 'info',
                title: 'Data Saved',
                text: 'Your data has been saved locally. It will be submitted when the connection is restored.',
            });
        }

        window.addEventListener('load', function() {
            const savedData = localStorage.getItem('studentData');
            if (savedData) {
                const validRows = JSON.parse(savedData);
                validRows.forEach(row => {
                    const newRow = document.createElement('tr');
                    newRow.innerHTML = `
                        <td>${rowCount + 1}</td>
                        <td>
                            <input type="text" name="name[]" value="${row.name}" onblur="assignAdmNo(this)" tabindex="${rowCount + 2}" autocomplete="off">
                            <div class="error-message" id="nameError${rowCount}"></div>
                        </td>
                        <td><input type="text" name="admno[]" value="${row.admno}" readonly></td>
                        <td class="row-actions">
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Remove</button>
                        </td>
                    `;
                    document.querySelector('#studentTable tbody').appendChild(newRow);
                    rowCount++;
                });
                updateRowNumbers();
                localStorage.removeItem('studentData'); // Clear saved data after loading
            }
        });
    </script>
</body>
</html>