<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS - School Management</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 10px; }
        textarea { resize: none; }
        #charCount { font-size: 14px; }
        .btn-success { font-weight: bold; }
    </style>
</head>
<body>

<!-- Header -->
<div class="d-flex justify-content-between align-items-center p-3 bg-primary text-white">
    <h4>📩 Send SMS to Parents</h4>
    <a href="sent-messages.html" class="btn btn-outline-light">📜 View Sent Messages</a>
</div>

<div class="container mt-4">
    <!-- Recipient Selection -->
    <div class="card p-4 shadow-sm">
        <label>Select Recipients</label>
        <select class="form-control">
            <option>All Parents</option>
            <option>Creche</option>
            <option>Basic 1</option>
            <option>Basic 2</option>
        </select>
        <input type="text" class="form-control mt-2" placeholder="Search student (optional)">
    </div>

    <!-- Message Composition -->
    <div class="card p-4 mt-3 shadow-sm">
        <label>Message</label>
        <textarea id="messageBody" class="form-control" rows="4" maxlength="320" oninput="updateCounter()"></textarea>
        <div class="d-flex justify-content-between text-muted mt-2">
            <small id="charCount">0/160 (1 SMS)</small>
            <select class="form-control-sm">
                <option>-- Select Template --</option>
                <option>Fee Reminder</option>
                <option>Attendance Alert</option>
            </select>
        </div>
    </div>

    <!-- Send & Schedule -->
    <div class="card p-4 mt-3 shadow-sm">
        <label>Schedule Message (Optional)</label>
        <input type="datetime-local" class="form-control">
        <div class="d-flex justify-content-between mt-3">
            <button class="btn btn-secondary">Preview</button>
            <button class="btn btn-success" onclick="sendSMS()">📤 Send SMS</button>
        </div>
    </div>

    <!-- Sent Messages Log -->
    <div class="card p-4 mt-3 shadow-sm">
        <h5>📊 SMS Delivery Reports</h5>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Recipient</th>
                    <th>Phone</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Time Sent</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="smsLog">
                <!-- Messages will be added dynamically -->
            </tbody>
        </table>
    </div>
</div>

<script>
    function updateCounter() {
        let text = document.getElementById("messageBody").value;
        let count = text.length;
        let smsParts = Math.ceil(count / 160);
        document.getElementById("charCount").innerText = `${count}/160 (${smsParts} SMS)`;
    }

    function sendSMS() {
        let message = document.getElementById("messageBody").value;
        if (message.trim() === "") {
            alert("Message cannot be empty!");
            return;
        }

        let logTable = document.getElementById("smsLog");
        let newRow = logTable.insertRow();
        let rowIndex = logTable.rows.length - 1;

        newRow.innerHTML = `
            <td>Selected Recipients</td>
            <td>+233 XXXXXX</td>
            <td>${message}</td>
            <td><span class="badge bg-warning">Pending</span></td>
            <td>${new Date().toLocaleString()}</td>
            <td>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteMessage(${rowIndex})">🗑 Delete</button>
            </td>
        `;

        alert("SMS Sent Successfully!");
    }

    function deleteMessage(index) {
        document.getElementById("smsLog").deleteRow(index);
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>