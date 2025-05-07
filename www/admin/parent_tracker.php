<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parents - Bus Tracking System</title>
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
    </style>
</head>
<body>

    <div class="header">
        🚍 Hill View School - Parent's Bus Tracking
    </div>
    
    <div class="map-container">
        <div class="marker bus-marker" id="bus" style="top: 50px; left: 50px;">🚌</div>
        <div id="studentMarkers"></div>
    </div>

    <script>
        function loadStudents() {
            fetch("fetch_students.php")
                .then(response => response.json())
                .then(students => {
                    let container = document.getElementById("studentMarkers");
                    container.innerHTML = "";

                    students.forEach((student, index) => {
                        let studentDiv = document.createElement("div");
                        studentDiv.className = "marker student-marker";
                        studentDiv.dataset.name = student.name;
                        studentDiv.style.top = `${150 + index * 80}px`;
                        studentDiv.style.left = `${100 + (index % 2) * 100}px`;
                        studentDiv.innerHTML = "🎒";

                        let nameDiv = document.createElement("div");
                        nameDiv.className = "student-name";
                        nameDiv.style.top = `${150 + index * 80 + 30}px`;
                        nameDiv.style.left = `${100 + (index % 2) * 100 - 10}px`;
                        nameDiv.innerText = student.name;

                        container.appendChild(studentDiv);
                        container.appendChild(nameDiv);
                    });
                })
                .catch(error => console.error("Error fetching students:", error));
        }

        window.onload = loadStudents;
    </script>

</body>
</html>