<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'data.php';

// Fetch distinct classes from the payments table
$classes_query = "SELECT DISTINCT class FROM payments"; 
$classes_result = $database->query($classes_query);
$classes = [];
while ($row = $classes_result->fetchArray(SQLITE3_ASSOC)) {
    $classes[] = $row['class'];
}

// Fetch students based on selected class with payment details
$students = [];
if (isset($_POST['class'])) {
    $selected_class = $_POST['class'];
    $students_query = "SELECT DISTINCT student_name, parent_email, parent_phone, amount_paid, balance, last_term_arrears FROM payments WHERE class = :class";
    $stmt = $database->prepare($students_query);
    $stmt->bindValue(':class', $selected_class, SQLITE3_TEXT);
    $students_result = $stmt->execute();
    while ($row = $students_result->fetchArray(SQLITE3_ASSOC)) {
        $students[] = $row;
    }
}

// Predefined subjects
$subjects = [
    "Official Notice: Upcoming Tuition Payment Deadline",
    "Reminder: School Fee Payment Due by [Date]",
    "Important: Tuition Payment Deadline Approaching",
    "Final Notice: Payment Submission Required by [Date]",
    "School Billing Reminder: Payment Due Soon",
    "Urgent: Outstanding School Fees – Deadline Approaching",
    "Action Required: Tuition Payment Deadline",
    "School Account Update: Payment Due by [Date]",
    "Official Reminder: Outstanding School Dues",
    "Final Notification: School Fee Payment Deadline"
];

// Predefined messages with placeholders for payment details
$messages = [
    "Dear Parent/Guardian, this is a reminder that the tuition payment deadline for [Term/Month] is on [Date]. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Kindly ensure payment is made to avoid any inconvenience. Thank you.",
    "Dear Parent/Guardian, please be reminded that school fees for [Term/Month] are due by [Date]. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Kindly settle the payment to maintain uninterrupted access to school services.",
    "Dear Parent/Guardian, the deadline for tuition payment is fast approaching. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Please ensure payment is made by [Date] to avoid any late fees or disruptions.",
    "Final Reminder: The deadline for school fee payment is [Date]. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Please complete the payment to ensure continued access to academic activities.",
    "Dear Parent/Guardian, this is a friendly reminder that your child’s school fees for [Term/Month] are due soon. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Kindly make payment before [Date] to avoid any penalties.",
    "Dear Parent/Guardian, our records indicate that your child’s school fees remain unpaid. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Please settle the outstanding amount by [Date] to prevent any service interruptions.",
    "Dear Parent/Guardian, immediate action is required as the tuition payment deadline is on [Date]. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Please ensure payment is made on time to avoid any inconvenience.",
    "Dear Parent/Guardian, your child’s school account has an outstanding balance due by [Date]. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Kindly make payment at your earliest convenience. Thank you.",
    "Dear Parent/Guardian, we kindly remind you of the outstanding school dues for [Student Name]. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Please ensure payment is made before [Date] to avoid any academic disruptions.",
    "Final Notification: The deadline for school fee payment is [Date]. Your child has paid [Amount Paid], with a balance of [Balance] and last term arrears of [Last Term Arrears]. Please complete the payment promptly to ensure your child’s continued access to school programs."
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Deadline Notification</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <style>
        /* General Styles */
        body {
            background-color: #FFFFFF; /* Pure White */
            color: #333333; /* Charcoal Black */
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Navigation Bar */
        .navbar {
            background: #1A1A2E; /* Midnight Blue */
            padding: 15px;
            text-align: center;
        }

        .navbar a {
            color: #007BFF; /* Royal Blue */
            text-decoration: none;
            font-size: 18px;
            padding: 10px 20px;
            transition: color 0.3s ease;
        }

        .navbar a:hover {
            color: #0056D2; /* Electric Blue */
        }

        /* Form Container */
        .form-container {
            background: #FFFFFF; /* Pure White */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Subtle shadow for depth */
            margin: 20px;
        }

        /* Buttons */
        .btn-custom {
            background: #007BFF; /* Royal Blue */
            color: #FFFFFF; /* White */
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn-custom:hover {
            background: #0056D2; /* Electric Blue */
            transform: scale(1.05);
        }

        /* Form Labels */
        .form-label {
            font-weight: bold;
        }

        /* Select Elements */
        .form-select {
            background-color: #F8F9FA; /* Light Gray for select */
            color: #333333; /* Charcoal Black */
        }

        .form-select:focus {
            border-color: #007BFF; /* Royal Blue */
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Footer */
        .footer {
            background: #1A1A2E; /* Midnight Blue */
            padding: 20px;
            text-align: center;
            color: #B0B0B0; /* Light Gray */
            font-size: 14px;
        }

        /* Icon Styles */
        .icon {
            color: #007BFF; /* Royal Blue */
            margin-right: 8px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="header text-center">
        <h2><i class="fas fa-bell icon"></i> Fee Deadline Notification</h2>
        <p>Send fee deadline reminders to parents via SMS and Email</p>
    </div>

    <div class="form-container">
        <form id="notificationForm" method="POST">
            <div class="mb-3 select-class">
                <label for="class" class="form-label"><i class="fas fa-chalkboard-teacher icon"></i>Select Class:</label>
                <select class="form-select" id="class" name="class" required onchange="this.form.submit()">
                    <option value="">Choose a class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?= htmlspecialchars($class) ?>" <?= (isset($selected_class) && $selected_class == $class) ? 'selected' : '' ?>><?= htmlspecialchars($class) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="deadlineDate" class="form-label"><i class="fas fa-calendar-alt icon"></i>Select Deadline Date:</label>
                <input type="date" class="form-control" id="deadlineDate" name="deadlineDate" required>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label"><i class="fas fa-bell icon"></i>Select Subject:</label>
                <select class="form-select" id="subject" name="subject" required>
                    <option value="">Choose a subject</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?= htmlspecialchars($subject) ?>"><?= htmlspecialchars($subject) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label"><i class="fas fa-comment-dots icon"></i>Select Deadline Message:</label>
                <select class="form-select" id="message" name="message" required>
                    <option value="">Choose a message</option>
                    <?php foreach ($messages as $msg): ?>
                        <option value="<?= htmlspecialchars($msg) ?>"><?= htmlspecialchars($msg) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="studentSelect" class="form-label"><i class="fas fa-user-graduate icon"></i>Select Students:</label>
                <select class="form-select" id="studentSelect" name="students[]" multiple required>
                    <?php foreach ($students as $student): ?>
                        <option value="<?= htmlspecialchars($student['student_name']) ?>" data-amount-paid="<?= htmlspecialchars($student['amount_paid']) ?>" data-balance="<?= htmlspecialchars($student['balance']) ?>" data-last-term-arrears="<?= htmlspecialchars($student['last_term_arrears']) ?>">
                            <?= htmlspecialchars($student['student_name']) ?> (<?= htmlspecialchars($student['parent_email']) ?>, <?= htmlspecialchars($student['parent_phone']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <small class="form-text text-muted">Hold down the Ctrl (Windows) or Command (Mac) button to select multiple students.</small>
            </div>

            <div class="mb-3">
                <label for="parentContact" class="form-label"><i class="fas fa-phone icon"></i>Add Parent Contact (optional):</label>
                <input type="text" class="form-control" id="parentContact" name="parentContact" placeholder="Enter parent contact">
            </div>

            <button type="submit" class="btn btn-custom"><i class="fas fa-paper-plane"></i> Send Notification</button>
        </form>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 for the student selection
    $('#studentSelect').select2({
        placeholder: "Select students",
        allowClear: true
    });

    $('#notificationForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission

        // Show loading indicator
        Swal.fire({
            title: 'Sending...',
            text: 'Please wait while we send the notifications.',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Prepare messages with payment details
        const selectedStudents = $('#studentSelect').val();
        const messages = [];
        selectedStudents.forEach(function(studentName) {
            const studentOption = $('#studentSelect option[value="' + studentName + '"]');
            const amountPaid = studentOption.data('amount-paid');
            const balance = studentOption.data('balance');
            const lastTermArrears = studentOption.data('last-term-arrears');

            let messageTemplate = $('#message').val();
            messageTemplate = messageTemplate
                .replace('[Amount Paid]', amountPaid)
                .replace('[Balance]', balance)
                .replace('[Last Term Arrears]', lastTermArrears)
                .replace('[Term/Month]', $('#deadlineDate').val()) // Assuming you want to use the selected date as the term/month
                .replace('[Date]', $('#deadlineDate').val());

            messages.push({
                name: studentName,
                message: messageTemplate
            });
        });

        // Send notifications for each student
        $.ajax({
            type: 'POST',
            url: 'send_notification.php', // URL to your PHP script that handles sending notifications
            data: { messages: messages }, // Send the messages array
            success: function(response) {
                const res = JSON.parse(response);
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: 'Notifications sent successfully!',
                        showCloseButton: true,
                        confirmButtonText: 'OK',
                        timer: 3000,
                        timerProgressBar: true
                    });
                    $('#notificationForm')[0].reset(); // Reset the form
                    $('#studentSelect').val(null).trigger('change'); // Reset Select2
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Failed!',
                        text: res.message,
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed!',
                    text: 'Failed to send notifications.',
                });
            }
        });
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<div class="footer">
    <p>&copy; <?= date("Y") ?> EduPro GT7.All rights reserved.</p>
</div>

</body>
</html>