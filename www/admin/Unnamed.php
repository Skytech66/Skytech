<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Check-In System</title>
    <style>
        :root {
            --primary: #4F46E5; /* Updated primary color */
            --secondary: #10B981; /* Updated secondary color */
        }

        body {
            font-family: 'Segoe UI', system-ui;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
        }

        .ai-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            padding: 1.5rem; /* Increased padding for a sleeker look */
            border-radius: 12px;
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Added shadow for depth */
        }

        .scanner-container {
            max-width: 800px;
            margin: 2rem auto;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 2rem;
            text-align: center;
        }

        #qr-scanner {
            width: 100%;
            height: 300px;
            border: 2px dashed var(--secondary);
            border-radius: 12px;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            color: #888;
            font-weight: bold;
            position: relative;
        }

        .warning {
            color: red;
            font-weight: bold;
            margin-top: 10px;
            display: none;
        }

        .checkin-details {
            background: #F4F6F8;
            padding: 1.5rem;
            border-radius: 12px;
            margin-top: 1rem;
            text-align: left;
        }

        .button {
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
            cursor: pointer;
            margin-top: 10px;
        }

        #retry-btn {
            background: red;
            display: none;
        }

        /* Success Popup Styling */
        .success-popup {
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #28a745;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
            text-align: center;
            z-index: 1000;
        }

        .success-popup button {
            padding: 10px 15px;
            border: none;
            background: white;
            color: #28a745;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="ai-header">
        <h1>🦾 Faculty Check-In System</h1>
        <p>AI-Powered Attendance Management</p>
    </div>

    <div class="scanner-container">
        <h2>Scan Your Faculty ID</h2>
        <div id="qr-scanner"></div>
        <p id="warning-message" class="warning">No QR code detected. Please try again.</p>
        <button class="button" id="open-camera">Open Camera</button>
        <button class="button" id="retry-btn">Retry</button>

        <div class="checkin-details" id="checkin-info">
            <h3>Check-In Details</h3>
            <p><strong>Name:</strong> <span id="teacher-name">-</span></p>
            <p><strong>ID:</strong> <span id="teacher-id">-</span></p>
            <p><strong>Class:</strong> <span id="class">-</span></p>
            <p><strong>Time:</strong> <span id="checkin-time">-</span></p>
                        <p><strong>Date:</strong> <span id="checkin-date">-</span></p>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let scanner;
        let scanTimeout;
        let failedAttempts = 0;

        document.getElementById('open-camera').addEventListener('click', startScanner);
        document.getElementById('retry-btn').addEventListener('click', startScanner);

        function startScanner() {
            if (!scanner) {
                scanner = new Html5Qrcode("qr-scanner");
            }

            document.getElementById('warning-message').style.display = "none";
            document.getElementById('retry-btn').style.display = "none";
            document.getElementById('qr-scanner').innerHTML = "";

            scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    clearTimeout(scanTimeout);
                    failedAttempts = 0;
                    processQRCode(decodedText);
                    scanner.stop();
                },
                (error) => {
                    console.error("QR Code Scan Error:", error);
                }
            ).catch(err => console.error("Camera start error:", err));

            scanTimeout = setTimeout(() => {
                document.getElementById('warning-message').style.display = "block";
                scanner.stop();
                document.getElementById('retry-btn').style.display = "inline-block";

                failedAttempts++;

                if (failedAttempts >= 3) {
                    manuallyEnterID();
                    failedAttempts = 0;
                }
            }, 6000);
        }

        function processQRCode(data) {
            const [name, id, className] = data.split('|');
            const now = new Date();

            document.getElementById('teacher-name').textContent = name;
            document.getElementById('teacher-id').textContent = id;
            document.getElementById('class').textContent = className;
            document.getElementById('checkin-time').textContent = now.toLocaleTimeString();
            document.getElementById('checkin-date').textContent = now.toLocaleDateString();

            sendToDatabase(name, id, className, now.toLocaleTimeString(), now.toLocaleDateString());
            showSuccessMessage(name, id, className);
        }

        function sendToDatabase(name, id, className, time, date) {
            fetch('save_checkin.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `teacher_id=${id}&name=${name}&class=${className}&checkin_time=${time}&checkin_date=${date}`
            })
            .then(response => response.json())
            .then(data => console.log(data.message))
            .catch(error => console.error("Error:", error));
        }

        function manuallyEnterID() {
            let id = prompt("QR scan failed 3 times. Please enter your Faculty ID:");
            if (id) {
                let name = "Unknown Faculty";
                let className = "Unknown";
                let now = new Date();

                document.getElementById('teacher-name').textContent = name;
                document.getElementById('teacher-id').textContent = id;
                document.getElementById('class').textContent = className;
                document.getElementById('checkin-time').textContent = now.toLocaleTimeString();
                document.getElementById('checkin-date').textContent = now.toLocaleDateString();

                sendToDatabase(name, id, className, now.toLocaleTimeString(), now.toLocaleDateString());
                showSuccessMessage(name, id, className);
            }
        }

        function showSuccessMessage(name, id, className) {
            let successPopup = document.createElement("div");
            successPopup.className = "success-popup";
            successPopup.innerHTML = `<h2>✅ Check-In Successful!</h2>
            <p><strong>Name:</strong> ${name}</p><p><strong>ID:</strong> ${id}</p>
            <button onclick="this.parentElement.remove()">✅ Done</button>`;
            document.body.appendChild(successPopup);
            setTimeout(() => successPopup.remove(), 5000);
        }
    </script>
</body>
</html>