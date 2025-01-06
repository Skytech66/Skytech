<?php 
require_once "header.php"; 
$year = isset($_POST['year']) ? $_POST['year'] : '';
$exam = isset($_POST['exam']) ? $_POST['exam'] : '';
$class = isset($_POST['class']) ? $_POST['class'] : '';
$subject = isset($_POST['subject']) ? $_POST['subject'] : '';
?>

<div class="container mt-5">
    <form action="action.php" method="POST" enctype="multipart/form-data">
        <h2 class="mb-4 text-center text-dark font-weight-bold">Mark Sheet</h2>

        <!-- Search Bar -->
        <div class="mb-4 text-center">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by Student Name or ADMNO" onkeyup="searchStudent()" />
        </div>

        <!-- Submit Button (smaller) -->
        <button class="btn btn-primary btn-sm btn-block mb-4" type="submit" name="submit" value="submit_marks">Submit Marks</button>

        <input type="hidden" name="uuser" value="<?php echo $session_id; ?>" />
        <input type="hidden" name="year" value="<?php echo $year; ?>" />
        <input type="hidden" name="exam" value="<?php echo $exam; ?>" />
        <input type="hidden" name="class" value="<?php echo $class; ?>" />
        <input type="hidden" name="subject" value="<?php echo $subject; ?>" />
        
        <div class="table-responsive">
            <table id="pager" class="table table-bordered table-sm text-nowrap">
                <thead>
                    <tr>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">Student Name</th>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">ADMNO</th>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">Class Score (50%)</th>
                        <th class="text-center" style="background-color: #3b4c6b; color: white;">Exam Score (50%)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Ensure correct year and class filtering from POST data
                        if ($year && $class) {
                            $sql = "SELECT * FROM student WHERE `year` = '$year' AND `class` = '$class'";
                            $res = $conn->query($sql);
                            while($row = $res->fetchArray(SQLITE3_ASSOC)){
                    ?>
                        <tr class="student-row">
                            <td class="text-center student-name">
                                <input type="hidden" class="form-control" name="jina[]" value="<?php echo $row['name']; ?>" />
                                <strong><?php echo $row['name']; ?></strong>
                            </td>
                            <td class="text-center student-id">
                                <input type="hidden" class="form-control" name="regno[]" value="<?php echo $row['admno']; ?>" />
                                <span><?php echo $row['admno']; ?></span>
                            </td>
                            <td class="text-center score-column">
                                <input type="number" class="form-control mark-input" name="midterm[]" max="50" oninput="checkMarks(this)" />
                            </td>
                            <td class="text-center score-column">
                                <input type="number" class="form-control mark-input" name="endterm[]" max="50" oninput="checkMarks(this)" />
                            </td>   
                        </tr>
                    <?php 
                            }
                        } else {
                            echo "<tr><td colspan='4' class='text-center'>No students found for this class/year.</td></tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="pageNavPosition" class="pager-nav"></div>
    </form>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- JavaScript to manage colors based on input and search function -->
<script>
function checkMarks(input) {
    var value = input.value;
    
    // Remove all previous color classes
    input.classList.remove('empty', 'valid', 'invalid', 'exceed');

    // Apply colors based on conditions
    if (value === "") {
        input.classList.add('empty'); // Empty input (light red)
    } else if (value > 50) {
        input.classList.add('exceed'); // Exceeds 50 (light yellow)
    } else if (value >= 0 && value <= 50) {
        input.classList.add('valid'); // Valid input (light green)
    } else {
        input.classList.add('invalid'); // Invalid input (light red)
    }
}

// Initialize input colors on page load
document.querySelectorAll('.mark-input').forEach(function(input) {
    checkMarks(input);
});

// Search functionality
function searchStudent() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById('searchInput');
    filter = input.value.toLowerCase();
    table = document.getElementById('pager');
    tr = table.getElementsByTagName('tr');

    for (i = 1; i < tr.length; i++) { // Start from 1 to skip the header row
        td = tr[i].getElementsByTagName('td');
        if (td) {
            var studentName = td[0].textContent || td[0].innerText;
            var studentAdmno = td[1].textContent || td[1].innerText;
            txtValue = studentName + " " + studentAdmno;
            if (txtValue.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}
</script>

<!-- CSS to style the input fields, table, and search bar -->
<style>
/* Add the existing styles here */
</style>
