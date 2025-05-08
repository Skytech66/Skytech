<?php
// Include database connection
include 'data.php';

// Fetch distinct classes for filtering
$classes = $database->query("SELECT DISTINCT class FROM payments ORDER BY class ASC");
?>

<!-- Set Deadline Reminder Modal -->
<div class="modal fade" id="setDeadlineModal" tabindex="-1" aria-labelledby="setDeadlineModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clock"></i> Set Deadline Reminder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="setDeadlineForm">
                    <div class="mb-3">
                        <label for="classSelect" class="form-label">Select Class</label>
                        <select id="classSelect" class="form-select" required>
                            <option value="">Choose a class</option>
                            <?php while ($row = $classes->fetchArray(SQLITE3_ASSOC)) { ?>
                                <option value="<?= htmlspecialchars($row['class']) ?>"><?= htmlspecialchars($row['class']) ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="mb-3" id="studentListContainer" style="display: none;">
                        <label for="studentSelect" class="form-label">Select Students</label>
                        <select id="studentSelect" class="form-select" multiple>
                            <!-- Student options will be populated here -->
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="deadlineDate" class="form-label">Deadline Date</label>
                        <input type="date" id="deadlineDate" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="messageSelect" class="form-label">Select Deadline Message</label>
                        <select id="messageSelect" class="form-select">
                            <option value="Please be reminded that the deadline is approaching.">Deadline Reminder</option>
                            <option value="This is a friendly reminder about the upcoming deadline.">Friendly Reminder</option>
                            <option value="Don't forget to meet the deadline!">Final Reminder</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="modeSelect" class="form-label">Mode of Sending</label>
                        <select id="modeSelect" class="form-select">
                            <option value="email">Email</option>
                            <option value="sms">SMS</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Send Reminder</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle class selection to populate students
    $('#classSelect').on('change', function() {
        const selectedClass = $(this).val();
        if (selectedClass) {
            // Fetch students for the selected class
            $.ajax({
                url: 'get_student.php', // Your PHP script to fetch students based on class
                type: 'GET',
                data: { class: selectedClass },
                success: function(response) {
                    const students = JSON.parse(response);
                    $('#studentSelect').empty(); // Clear previous options
                    students.forEach(student => {
                        $('#studentSelect').append(`<option value="${student.student_name}" data-email="${student.parent_email}" data-phone="${student.parent_phone}">${student.student_name} (${student.class})</option>`);
                    });
                    $('#studentListContainer').show(); // Show the student list container
                },
                error: function() {
                    alert('Error fetching students. Please try again.');
                }
            });
        } else {
            $('#studentListContainer').hide(); // Hide the student list if no class is selected
        }
    });

    // Handle form submission for setting deadline reminder
    $('#setDeadlineForm').on('submit', function(e) {
        e.preventDefault();

        const selectedStudents = $('#studentSelect').val();
        const deadlineDate = $('#deadlineDate').val();
        const message = $('#messageSelect').val();
        const mode = $('#modeSelect').val();

        if (!selectedStudents || selectedStudents.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'Please select at least one student.',
            });
            return;
        }

        // Show loading indicator
        Swal.fire({
            title: 'Sending...',
            text: 'Please wait while we send the reminders.',
            allowOutsideClick: false,
            onBeforeOpen: () => {
                Swal.showLoading();
            }
        });

        // Simulate sending reminders (replace with actual AJAX call)
        setTimeout(function() {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Reminders sent successfully!',
                showCloseButton: true,
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
            $('#setDeadlineModal').modal('hide');
            $('#setDeadlineForm')[0].reset();
        }, 2000);
    });
});
</script>