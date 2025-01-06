<?php 
require_once "header.php";
?>

<!-- Items Display -->
<div class="container mt-5">
    <h2 class="mb-4 d-flex justify-content-between align-items-center">
        <a href="index.php?reg_students" class="btn btn-primary btn-lg" role="button">New Student</a>
        <input type="text" id="myInput" class="form-control w-50" placeholder="Search by name..." onkeyup="TableFilter()">
    </h2>

    <div class="table-responsive">
        <table id="pager" class="table table-bordered table-hover table-striped table-sm text-center">
            <thead class="thead-dark">
                <tr>
                    <th>Student Name</th>
                    <th>Adm-No</th>
                    <th>Gender</th>
                    <th>Class</th>
                    <th>Dormitory</th>
                    <th>Parent</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = "SELECT * from student ORDER BY class, admno DESC";
                    $res = $conn->query($sql);
                    $previousClass = null;

                    while($row = $res->fetchArray(SQLITE3_ASSOC)){
                        if ($row['class'] !== $previousClass) {
                            if ($previousClass !== null) {
                                echo "</tbody>";
                            }
                            echo "<tr><td colspan='9' class='table-warning text-center font-weight-bold py-2'><h5>Class: " . $row['class'] . "</h5></td></tr>";
                            echo "<tbody>";
                            $previousClass = $row['class'];
                        }
                ?>
                <tr class="tr-sm">
                    <td class="td-sm"><?php echo $row['name']; ?></td>
                    <td class="td-sm"><?php echo $row['admno']; ?></td>
                    <td class="td-sm"><?php echo $row['gender']; ?></td>
                    <td class="td-sm"><?php echo $row['class']; ?></td>
                    <td class="td-sm"><?php echo $row['dorm']; ?></td>
                    <td class="td-sm"><?php echo $row['parent']; ?></td>
                    <td class="td-sm"><?php echo $row['contact']; ?></td>
                    <td class="td-sm"><?php echo $row['email']; ?></td>
                    <td class="text-center">
                        <!-- Reduced button size and professional appearance -->
                        <button class="btn btn-outline-primary btn-sm" id="<?php echo $row['admno']; ?>" data-toggle="modal" data-target="#profile" onclick="getid(this.id)">
                            <i class="fa fa-eye"></i> View
                        </button>
                        <a href="#Update_User" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#Update_User">
                            <i class="fa fa-pencil"></i> Edit
                        </a>
                        <button class="btn btn-outline-danger btn-sm" id="<?php echo $row['admno']; ?>" data-toggle="modal" data-target="#deleteModal" onclick="setDeleteId(this.id)">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <div id="pageNavPosition" class="pager-nav"></div>
</div>

<!-- Modal for Student Profile -->
<div class="modal fade" id="profile" tabindex="-1" role="dialog" aria-labelledby="profileLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="profileLabel">Student Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <img src="/images/img_avatar2.png" class="rounded-circle" width="150px" height="150px" alt="Profile Picture">
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Name:</strong></div>
                            <div class="col-sm-8" id="profile-name">Eric Mokaya</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>ID No:</strong></div>
                            <div class="col-sm-8" id="profile-id">12345678</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Phone:</strong></div>
                            <div class="col-sm-8" id="profile-phone">+254700711233</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Email:</strong></div>
                            <div class="col-sm-8" id="profile-email">xxx@gmail.com</div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>County:</strong></div>
                    <div class="col-sm-8" id="profile-county">Nairobi</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Constituency:</strong></div>
                    <div class="col-sm-8" id="profile-constituency">Langata</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Ward:</strong></div>
                    <div class="col-sm-8" id="profile-ward">Kibera</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Occupation:</strong></div>
                    <div class="col-sm-8" id="profile-occupation">Information Technology</div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Status:</strong></div>
                    <div class="col-sm-8" id="profile-status">Active</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Delete Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-light">
                <h5 class="modal-title" id="deleteModalLabel">Delete Student</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this student? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <a href="" id="confirmDeleteBtn" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Function to update student profile modal content when eye button is clicked
    function getid(admno) {
        // Populate student profile (can use AJAX or fetch)
        document.getElementById("profile-name").innerText = "John Doe"; // Example
        document.getElementById("profile-id").innerText = "123456"; // Example
        document.getElementById("profile-phone").innerText = "+254701234567"; // Example
        document.getElementById("profile-email").innerText = "john@example.com"; // Example
        document.getElementById("profile-county").innerText = "Nairobi"; // Example
        document.getElementById("profile-constituency").innerText = "Langata"; // Example
        document.getElementById("profile-ward").innerText = "Kibera"; // Example
        document.getElementById("profile-occupation").innerText = "Engineer"; // Example
        document.getElementById("profile-status").innerText = "Active"; // Example
    }

    // Function to set the student ID in the delete modal
    function setDeleteId(admno) {
        const deleteUrl = 'delete_student.php?admno=' + admno;
        document.getElementById("confirmDeleteBtn").setAttribute("href", deleteUrl);
    }

    // Table Filter Function
    function TableFilter() {
        var input = document.getElementById("myInput");
        var filter = input.value.toUpperCase();
        var table = document.getElementById("pager");
        var tr = table.getElementsByTagName("tr");

        for (var i = 0; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName("td")[0]; // Look in the first column (name)
            if (td) {
                var txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }       
        }
    }
</script>

<?php require_once "../include/footer.php"; ?>
