<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Pickup Request</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: "Poppins", sans-serif;
            background: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 10px;
        }

        .container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            color: #2563eb;
            margin-bottom: 15px;
        }

        .input-group {
            display: flex;
            align-items: center;
            background: #eef2ff;
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
        }

        .input-group i {
            color: #2563eb;
            margin-right: 8px;
        }

        input, select {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            padding: 8px;
            font-size: 14px;
        }

        .date-time-group {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .date-time-group .input-group {
            width: 48%;
        }

        button {
            width: 100%;
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px;
            margin-top: 15px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #1e4bb8;
        }

        .confirmation, .receipt {
            display: none;
            margin-top: 15px;
            padding: 12px;
            background: #d1e7dd;
            color: #155724;
            border-radius: 8px;
        }

        .download-btn {
            background: #28a745;
            color: white;
            padding: 10px;
            margin-top: 10px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            width: 100%;
            transition: 0.3s;
        }

        .download-btn:hover {
            background: #218838;
        }

        @media (max-width: 400px) {
            .date-time-group {
                flex-direction: column;
            }

            .date-time-group .input-group {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <h2><i class="fas fa-user-shield"></i> Secure Pickup Request</h2>

    <div class="input-group">
        <i class="fas fa-child"></i>
        <input type="text" id="childName" placeholder="Child's Name" required>
    </div>

    <div class="input-group">
        <i class="fas fa-user"></i>
        <input type="text" id="pickupPerson" placeholder="Pickup Person's Name" required>
    </div>

    <div class="input-group">
        <i class="fas fa-phone"></i>
        <input type="tel" id="phoneNumber" placeholder="Pickup Person's Phone Number" required>
    </div>

    <div class="input-group">
        <i class="fas fa-user-friends"></i>
        <select id="relation">
            <option value="" disabled selected>Select Relation</option>
            <option value="Uncle">Uncle</option>
            <option value="Aunt">Aunt</option>
            <option value="Grandparent">Grandparent</option>
            <option value="Family Friend">Family Friend</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="date-time-group">
        <div class="input-group">
            <i class="fas fa-calendar-alt"></i>
            <input type="date" id="pickupDate" required>
        </div>

        <div class="input-group">
            <i class="fas fa-clock"></i>
            <input type="time" id="pickupTime" required>
        </div>
    </div>

    <button onclick="generatePickupRequest()">Send Pickup Request</button>

    <div class="confirmation" id="confirmationMessage">
        <b>Pickup request sent successfully!</b> <br>
        <b>OTP:</b> <span id="otpDisplay"></span>
    </div>

    <div class="receipt" id="receipt">
        <h3>Pickup Receipt</h3>
        <p><b>Child's Name:</b> <span id="childNameDisplay"></span></p>
        <p><b>Pickup Person:</b> <span id="pickupNameDisplay"></span></p>
        <p><b>Phone Number:</b> <span id="phoneNumberDisplay"></span></p>
        <p><b>Relation:</b> <span id="relationDisplay"></span></p>
        <p><b>Pickup Date:</b> <span id="pickupDateDisplay"></span></p>
        <p><b>Estimated Pickup Time:</b> <span id="pickupTimeDisplay"></span></p>
        <p><b>OTP Code:</b> <span id="otpReceiptDisplay"></span></p>
        <button class="download-btn" onclick="downloadReceipt()">Download Receipt</button>
    </div>
</div>

<script>
    function generateOTP() {
        return Math.floor(100000 + Math.random() * 900000);
    }

    function generatePickupRequest() {
        let childName = document.getElementById("childName").value;
        let pickupPerson = document.getElementById("pickupPerson").value;
        let phoneNumber = document.getElementById("phoneNumber").value;
        let relation = document.getElementById("relation").value;
        let pickupDate = document.getElementById("pickupDate").value;
        let pickupTime = document.getElementById("pickupTime").value;

        if (!childName || !pickupPerson || !phoneNumber || !relation || !pickupDate || !pickupTime) {
            alert("Please fill in all details.");
            return;
        }

        let otp = generateOTP();

        fetch("process_pickup.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `child_name=${childName}&pickup_person=${pickupPerson}&phone_number=${phoneNumber}&relation=${relation}&pickup_date=${pickupDate}&pickup_time=${pickupTime}&otp=${otp}`
        })
        .then(response => response.text())
        .then(data => {
            console.log(data);
            document.getElementById("otpDisplay").innerText = otp;
            document.getElementById("confirmationMessage").style.display = "block";
            document.getElementById("receipt").style.display = "block";
            document.getElementById("childNameDisplay").innerText = childName;
            document.getElementById("pickupNameDisplay").innerText = pickupPerson;
            document.getElementById("phoneNumberDisplay").innerText = phoneNumber;
            document.getElementById("relationDisplay").innerText = relation;
            document.getElementById("pickupDateDisplay").innerText = pickupDate;
            document.getElementById("pickupTimeDisplay").innerText = pickupTime;
            document.getElementById("otpReceiptDisplay").innerText = otp;
        })
        .catch(error => console.error("Error:", error));
    }
</script>

</body>
</html>