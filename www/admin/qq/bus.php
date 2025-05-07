<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Tracking System</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }
        .header {
            width: 100%;
            background: #003366;
            color: white;
            padding: 15px 0;
            font-size: 22px;
            font-weight: bold;
            text-align: center;
            position: fixed;
            top: 0;
            z-index: 1000;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.3);
        }
        .next-stop {
            font-size: 18px;
            font-weight: normal;
            color: #ffcc00;
            margin-top: 5px;
            display: block;
        }
        .map-container {
            position: fixed;
            top: 70px;
            left: 0;
            width: 100%;
            height: calc(100vh - 70px);
            overflow: hidden;
            background: url('tr.png') center/cover no-repeat;
        }
        .marker {
            position: absolute;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 40px;
            font-size: 20px;
            font-weight: bold;
            color: white;
            border-radius: 50%;
            cursor: pointer;
        }
        .bus-marker {
            background: red;
            transition: transform 0.1s linear;
        }
        .student-marker {
            background: #ffcc00;
        }
        .picked-up {
            background: green !important;
        }
        .not-riding {
            background: red !important;
        }
        .student-name {
            position: absolute;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.9);
            padding: 3px 8px;
            border-radius: 5px;
            font-weight: bold;
        }
        .modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
            border-radius: 8px;
            width: 320px;
            text-align: center;
            z-index: 2000;
        }
        .modal h3 {
            margin-bottom: 10px;
            color: #003366;
        }
        .modal-buttons {
            margin-top: 15px;
        }
        .modal button {
            background: #003366;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
            margin: 5px;
        }
        .pickup {
            background: green;
        }
        .not-riding-btn {
            background: red;
        }
    </style>
</head>
<body>

    <div class="header">
        🚍 Hill View School - Bus Tracking System
        <span class="next-stop" id="nextStop">Next Stop: Waiting...</span>
    </div>
    
    <div class="map-container">
        <div class="marker bus-marker" id="bus" style="top: 50px; left: 50px;">🚌</div>
        <div id="studentMarkers"></div>
    </div>

    <!-- ETA Modal -->
    <div class="modal" id="etaModal">
        <h3>Set ETA</h3>
        <p id="etaStudent"></p>
        <input type="number" id="etaInput" placeholder="Enter minutes">
        <div class="modal-buttons">
            <button onclick="setETA()">Confirm</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </div>

    <!-- Arrived Modal -->
    <div class="modal" id="arrivedModal">
        <h3>Bus Has Arrived</h3>
        <p>The bus has arrived at <span id="arrivedStudent"></span>'s location.</p>
        <div class="modal-buttons">
            <button class="pickup" onclick="markPickedUp()">Pickup</button>
            <button class="not-riding-btn" onclick="markNotRiding()">Not Riding</button>
        </div>
    </div>

    <script>
        let selectedStudent = "";
        let currentPickupCount = 0;
        let totalStops = 0;

        function loadStudents() {
            fetch("fetch_students.php")
                .then(response => response.json())
                .then(students => {
                    let container = document.getElementById("studentMarkers");
                    container.innerHTML = ""; // Clear previous markers
                    
                    students.forEach((student, index) => {
                        let studentDiv = document.createElement("div");
                        studentDiv.className = "marker student-marker";
                        studentDiv.dataset.name = student.name;
                        studentDiv.style.top = `${150 + index * 80}px`;
                        studentDiv.style.left = `${100 + (index % 2) * 100}px`;
                        studentDiv.innerHTML = "🎒";
                        studentDiv.setAttribute("onclick", `showETAModal('${student.name}', ${150 + index * 80}, ${100 + (index % 2) * 100})`);

                        let nameDiv = document.createElement("div");
                        nameDiv.className = "student-name";
                        nameDiv.style.top = `${150 + index * 80 + 30}px`;
                        nameDiv.style.left = `${100 + (index % 2) * 100 - 10}px`;
                        nameDiv.innerText = student.name;

                        container.appendChild(studentDiv);
                        container.appendChild(nameDiv);
                    });

                    totalStops = students.length;
                })
                .catch(error => console.error("Error fetching students:", error));
        }

        function showETAModal(studentName, studentY, studentX) {
            selectedStudent = { name: studentName, x: studentX, y: studentY };
            document.getElementById("etaStudent").innerText = "Pickup for " + studentName;
            document.getElementById("etaModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("etaModal").style.display = "none";
        }

        function setETA() {
            let etaValue = parseInt(document.getElementById("etaInput").value);
            if (etaValue > 0) {
                closeModal();
                
                document.getElementById("nextStop").innerText = `Next Stop: ${selectedStudent.name} (ETA: ${etaValue} min)`;

                moveBusToStudent(selectedStudent.x, selectedStudent.y, etaValue);
                
                setTimeout(() => {
                    document.getElementById("arrivedStudent").innerText = selectedStudent.name;
                    document.getElementById("arrivedModal").style.display = "block";
                }, etaValue * 60000);
            }
        }

        function moveBusToStudent(x, y, etaMinutes) {
            let bus = document.getElementById("bus");
            bus.style.transition = `top ${etaMinutes * 60}s linear, left ${etaMinutes * 60}s linear`;
            bus.style.left = x + "px";
            bus.style.top = y + "px";
        }

        function markPickedUp() {
            document.querySelector(`.student-marker[data-name="${selectedStudent.name}"]`).classList.add("picked-up");
            closeArrivedModal();
            
            currentPickupCount++;
            document.getElementById("nextStop").innerText += ` [${currentPickupCount}/${totalStops}]`;
        }

        function markNotRiding() {
            document.querySelector(`.student-marker[data-name="${selectedStudent.name}"]`).classList.add("not-riding");
            closeArrivedModal();
        }

        function closeArrivedModal() {
            document.getElementById("arrivedModal").style.display = "none";
        }

        window.onload = loadStudents;
    </script>

</body>
</html>