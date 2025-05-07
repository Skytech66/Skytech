<?php require_once "header.php"; ?>

<div class="container my-5">
    <h2 class="mb-4 d-flex justify-content-between align-items-center">
        <span>Add New Expenses</span>
        <button class="btn btn-success" type="button" data-toggle="modal" data-target="#Expensess">
            <i class="fa fa-plus-circle"></i> Add Expense
        </button>
    </h2>
    
    <div class="form-group mb-4">
        <input type="text" id="myInput" class="form-control shadow-sm" placeholder="Search expenses..." onkeyup="TableFilter()">
    </div>

    <div class="table-responsive shadow-sm rounded-lg">
        <table id="pager" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead class="thead-light">
                <tr>
                    <th>DATE</th>
                    <th>CATEGORY</th>
                    <th>DESCRIPTION</th>
                    <th>AMOUNT</th>
                    <th>REF NUMBER</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM expenses ORDER BY id DESC";
                $res = $conn->query($sql);
                while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
                ?>
                    <tr>
                        <td><?php echo $row['reg_date']; ?></td>
                        <td><?php echo $row['vendor']; ?></td>
                        <td><?php echo $row['expense_name']; ?></td>
                        <td><?php echo $row['amount']; ?></td>
                        <td><?php echo $row['refno']; ?></td>
                        <td>
                            <button class="btn btn-info btn-sm" id="<?php echo $row['id']; ?>" data-toggle="modal" data-target="#UpdateExpensess" onclick="getid(this.id)">
                                <i class="fa fa-pencil"></i> Edit
                            </button>
                            <!-- Updated delete button -->
                            <button class="btn btn-danger btn-sm" id="<?php echo $row['id']; ?>" onclick="deleteExpense(<?php echo $row['id']; ?>)">
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

<!-- Add Expense Modal -->
<div class="modal fade" id="Expensess" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title" id="exampleModalLabel">Add New Expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="action.php" method="POST">
                    <input type="hidden" name="recordedby" value="<?php echo $session_id; ?>" required />
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="vendor" class="col-form-label">Category:</label>
                                <input type="text" class="form-control" id="vendor" name="vendor" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="expense" class="col-form-label">Description:</label>
                                <input type="text" class="form-control" id="expense" name="expense" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="ref_no" class="col-form-label">Ref Number:</label>
                                <input type="text" class="form-control" id="ref_no" name="ref_no">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="amount" class="col-form-label">Amount:</label>
                                <input type="number" class="form-control" id="amount" name="amount" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" value="Expense" class="btn btn-primary">Add Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Update Expense Modal -->
<div class="modal fade" id="UpdateExpensess" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-light">
                <h5 class="modal-title" id="exampleModalLabel">Edit Expense</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="action.php" method="POST">
                    <input type="hidden" name="urecordedby" value="<?php echo $session_id; ?>" required />
                    <input type="hidden" id="uid" name="uid" value="" />
                    <input type="hidden" name="Urecordedby" value="<?php echo $session_id; ?>" />
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="Uvendor" class="col-form-label">Category:</label>
                                <input type="text" class="form-control" id="Uvendor" name="Uvendor">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="Uexpense" class="col-form-label">Description:</label>
                                <input type="text" class="form-control" id="Uexpense" name="Uexpense">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="Uref_no" class="col-form-label">Ref Number:</label>
                                <input type="text" class="form-control" id="Uref_no" name="Uref_no">
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="Uamount" class="col-form-label">Amount:</label>
                                <input type="number" class="form-control" id="Uamount" name="Uamount" min="1">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="submit" value="UpdateExpense" class="btn btn-warning">Update Expense</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once "../include/footer.php"; ?>

<script>
    function deleteExpense(id) {
        if (confirm("Are you sure you want to delete this expense?")) {
            var formData = new FormData();
            formData.append('submit', 'deleteExpense');
            formData.append('id', id);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'action.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = xhr.responseText;
                    if (response == 'Expense deleted successfully.') {
                        alert("Expense deleted successfully.");
                        location.reload(); // Reload the page to update the table
                    } else {
                        alert("Error deleting expense.");
                    }
                }
            };
            xhr.send(formData);
        }
    }
</script>
