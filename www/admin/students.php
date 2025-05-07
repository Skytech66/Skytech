<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Records</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Optional: Add some basic styles for the modal */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Records</h1>
        <div class="controls">
            <button id="addStudentBtn">Add Student</button>
            <select id="classSelect">
                <option value="">Select Class</option>
                <option value="Creche 1">Creche 1</option>
                <option value="Creche 2">Creche 2</option>
                <option value="Nursery 1">Nursery 1</option>
                <option value="Nursery 2">Nursery 2</option>
                <option value="Class 1">Class 1</option>
                <option value="Class 2">Class 2</option>
                <option value="Class 3">Class 3</option>
                <option value="Class 4">Class 4</option>
                <option value="Class 5">Class 5</option>
                <option value="Class 6">Class 6</option>
                <option value="Basic 7">Basic 7</option>
                <option value="Basic 8">Basic 8</option>
                <option value="Basic 9">Basic 9</option>
            </select>
            <input type="text" id="searchBar" placeholder="Search Students...">
        </div>
        <table id="studentTable">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Student Name</th>
                    <th>Class</th>
                    <th>Age</th>
                    <th>Date of Birth</th>
                    <th>Admission Number</th>
                    <th>Parent Name</th>
                    <th>Contact</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="studentBody">
                <!-- Student records will be dynamically loaded here -->
            </tbody>
        </table>
    </div>

    <!-- Modal for Adding Student -->
    <div id="addStudentModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add Student</h2>
            <form id="addStudentForm" enctype="multipart/form-data">
                <input type="file" id="studentImage" name="studentImage" accept="image/*" required>
                <input type="text" id="studentName" name="studentName" placeholder="Student Name" required>
                <select id="studentClass" name="studentClass" required>
                    <option value="">Select Class</option>
                    <option value="Creche 1">Creche 1</option>
                    <option value="Creche 2">Creche 2</option>
                    <option value="Nursery 1">Nursery 1</option>
                    <option value="Nursery 2">Nursery 2</option>
                    <option value="Class 1">Class 1</option>
                    <option value="Class 2">Class 2</option>
                    <option value="Class 3">Class 3</option>
                    <option value="Class 4">Class 4</option>
                    <option value="Class 5">Class 5</option>
                    <option value="Class 6">Class 6</option>
                    <option value="Basic 7">Basic 7</option>
                    <option value="Basic 8">Basic 8</option>
                    <option value="Basic 9">Basic 9</option>
                </select>
                <input type="number" id="studentAge" name="studentAge" placeholder="Age" required>
                <input type="date" id="studentDOB" name="studentDOB" required>
                <input type="text" id="admissionNumber" name="admissionNumber" placeholder="Admission Number" required>
                <input type="text" id="parentName" name="parentName" placeholder="Parent Name" required>
                <input type="text" id="contact" name="contact" placeholder="Contact" required>
                <input type="email" id="email" name="email" placeholder="Email" required>
                <textarea id="address" name="address" placeholder="Address" required></textarea>
                <select id="gender" name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
                <button type="submit">Add Student</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById("addStudentBtn").onclick = function() {
            document.getElementById("addStudentModal").style.display = "block";
        };

        document.getElementsByClassName("close")[0].onclick = function() {
            document.getElementById("addStudentModal").style.display = "none";
            resetAddStudentForm();
        };

        window.onclick = function(event) {
            if (event.target == document.getElementById("addStudentModal")) {
                document.getElementById("addStudentModal").style.display = "none";
                resetAddStudentForm();
            }
        };

        function resetAddStudentForm() {
            document.getElementById("addStudentForm").reset();
        }

        function loadStudents(selectedClass = "", searchQuery = "") {
            let url = "search_students.php"; // Ensure this is the correct endpoint
            let params = [];

            if (selectedClass) {
                params.push(`class=${encodeURIComponent(selectedClass)}`);
            }
            if (searchQuery) {
                params.push(`search=${encodeURIComponent(searchQuery)}`);
            }
            if (params.length) {
                url += "?" + params.join("&");
            }

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        let studentBody = document.getElementById("studentBody");
                        studentBody.innerHTML = "";

                        data.students.forEach(student => {
                            let row = `<tr>
                                <td>
                                    <div class="image-container">
                                        <img src="${student.image}" alt="Student Image" style="width:50px;height:50px;">
                                    </div>
                                </td>
                                <td>${student.name}</td>
                                <td>${student.class}</td>
                                <td>${student.age}</td>
                                <td>${student.dob}</td>
                                <td>${student.admission_number}</td>
                                <td>${student.parent_name}</td>
                                <td>${student.contact}</td>
                                <td>${student.email}</td>
                                <td>${student.address}</td>
                                <td>${student.gender}</td>
                                <td>
                                    <button onclick="editStudent(${student.id})">Edit</button>
                                    <button onclick="deleteStudent(${student.id})" style="margin-centered: 5px; background-color:red;">Delete</button>
                                </td>
                            </tr>`;
                            studentBody.innerHTML += row;
                        });
                    } else {
                        console.error("Error loading students:", data.message);
                    }
                })
                .catch(error => console.error("Fetch error:", error));
        }

        document.addEventListener("DOMContentLoaded", () => loadStudents());

        document.getElementById("classSelect").addEventListener("change", function() {
            loadStudents(this.value, document.getElementById("searchBar").value);
        });

        document.getElementById("searchBar").addEventListener("input", function() {
            loadStudents(document.getElementById("classSelect").value, this.value);
        });

        document.getElementById("addStudentForm").onsubmit = function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch("save_student.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    document.getElementById("addStudentModal").style.display = "none";
                    loadStudents(document.getElementById("classSelect").value, document.getElementById("searchBar").value);
                }
            })
            .catch(error => console.error("Error:", error));
        };

        function deleteStudent(studentId) {
            if (confirm("Are you sure you want to delete this student?")) {
                fetch(`delete.php`, {
                    method: "DELETE",
                    body: new URLSearchParams({ id: studentId })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message);
                    if (data.success) {
                        loadStudents(document.getElementById("classSelect").value, document.getElementById("searchBar").value);
                    }
                })
                .catch(error => console.error("Error deleting student:", error));
            }
        }
    </script>
</body>
</html>