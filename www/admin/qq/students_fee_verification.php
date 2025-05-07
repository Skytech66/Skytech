<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Fee Verification</title>
    <style>
        :root {
            --primary: #2A5C82;
            --secondary: #5BA4E6;
            --bg: #f8f9fa;
            --text-dark: #2C2C2C;
            --text-light: #6C757D;
        }

        body {
            font-family: 'Segoe UI', system-ui;
            background: var(--bg);
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .header {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            border-radius: 0 0 20px 20px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            color: var(--primary);
        }

        .container {
            max-width: 90%;
            margin: auto;
            margin-top: 20px;
            padding: 1.5rem;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .balance-card {
            background: var(--primary);
            color: white;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 1.5rem;
            text-align: left;
        }

        .amount {
            font-size: 28px;
            font-weight: bold;
        }

        .currency {
            font-size: 16px;
            opacity: 0.8;
        }

        .qr-box {
            width: 100%;
            height: 200px;
            border: 2px dashed var(--secondary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            color: #888;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .button {
            background: var(--secondary);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }

        .input-box {
            width: 90%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            text-align: center;
            font-size: 16px;
            margin-top: 10px;
        }

        .details {
            background: #F4F6F8;
            padding: 1.5rem;
            border-radius: 12px;
            text-align: left;
            margin-top: 1rem;
            display: none;
        }

        .error {
            color: red;
            font-weight: bold;
            margin-top: 10px;
            display: none;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-light);
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="avatar">📚</div>
        <div>
            <h2>Hi, Student</h2>
            <p>Check your fee details below</p>
        </div>
    </div>

    <div class="container">
        <div class="balance-card">
            <h3>My Fees Summary</h3>
            <p><span class="amount">GHS <span id="fees-paid">0.00</span></span></p>
            <p class="currency">Paid so far</p>
        </div>

        <h3>Scan Student QR Code</h3>
        <div class="qr-box" id="qr-scanner">📷 QR Scanner Here</div>
        <button class="button" id="open-camera">Open Camera</button>

        <h3>Or Enter Student Name</h3>
        <input type="text" class="input-box" id="manual-name" placeholder="Enter Student Name">
        <button class="button" onclick="fetchStudentData()">Search</button>

        <p class="error" id="error-message">Student Not Found!</p>

        <div class="details" id="student-info">
            <h3>Student Details</h3>
            <p><strong>Name:</strong> <span id="student-name">-</span></p>
            <p><strong>Class:</strong> <span id="student-class">-</span></p>
            <p><strong>Date Paid:</strong> <span id="date-paid">-</span></p>
            <p><strong>Balance:</strong> <span id="balance">-</span></p>
            <p><strong>Last Term's Arrears:</strong> <span id="last-arrears">-</span></p>
        </div>
    </div>

    <p class="footer">Powered by [Your School Name]</p>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let scanner;

        document.getElementById('open-camera').addEventListener('click', () => {
            scanner = new Html5Qrcode("qr-scanner");
            scanner.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                (decodedText) => {
                    scanner.stop();
                    fetchStudentData(decodedText);
                },
                (error) => console.error("QR Code Scan Error:", error)
            ).catch(err => console.error("Camera start error:", err));
        });

        function fetchStudentData(name = null) {
            let studentName = name || document.getElementById('manual-name').value.trim();
            if (!studentName) return;

            fetch("fetch_student_fees.php?student_name=" + encodeURIComponent(studentName))
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById('error-message').style.display = 'block';
                        document.getElementById('student-info').style.display = 'none';
                    } else {
                        document.getElementById('student-info').style.display = 'block';
                        document.getElementById('error-message').style.display = 'none';

                        document.getElementById('student-name').textContent = data.student_name;
                        document.getElementById('student-class').textContent = data.class;
                        document.getElementById('fees-paid').textContent = data.amount_paid;
                        document.getElementById('date-paid').textContent = data.date_paid;
                        document.getElementById('balance').textContent = data.balance;
                        document.getElementById('last-arrears').textContent = data.last_term_arrears;
                    }
                })
                .catch(error => console.error("Error fetching student data:", error));
        }
    </script>

</body>
</html>