
<?php 
require_once "header.php";
?>

<!-- Items Display -->
<div class="container">

<h2 class="mb-4 text-secondary">Employee Registration Form</h2>
<hr>
<form action="action.php" method="POST" enctype="multipart/form-data">
    <!-- CSRF Token (Optional, for added security) -->
    <input type="hidden" class="form-control" id="csrf_token" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
    <input type="hidden" class="form-control" id="served_by" name="served_by" value="<?php echo $session_id; ?>" readonly>
    
    <!-- Personal Details -->
    <div class="row mb-2">
        <div class="col-sm">
            <label for="fname" class="col-sm-form-label">Full Name:</label>
            <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter full name" required>
        </div>
        <div class="col-sm">
            <label for="idno" class="col-sm-form-label">ID Number:</label>
            <input type="number" class="form-control" id="idno" name="idno" placeholder="Enter ID number" required>
        </div>
        <div class="col-sm">
            <label for="gender" class="col-sm-form-label">Gender:</label>
            <select class="form-select" id="gender" name="gender" required>
                <option selected value="">Select gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Others">Others</option>
            </select>
        </div>
    </div>

    <!-- Contact Information -->
    <div class="row mb-2">
        <div class="col-sm">
            <label for="phone" class="col-sm-form-label">Phone Number:</label>
            <input type="text" class="form-control" id="phone" name="phone" placeholder="e.g., +123456789" required>
        </div>
        <div class="col-sm">
            <label for="mail" class="col-sm-form-label">Email:</label>
            <input type="email" class="form-control" id="mail" name="mail" placeholder="Enter email address" required>
        </div>
        <div class="col-sm">
            <label for="dob" class="col-sm-form-label">Date of Birth:</label>
            <input type="date" class="form-control" id="dob" name="dob" required>
        </div>
    </div>

    <!-- Employment Details -->
    <div class="row mb-2">
        <div class="col-sm">
            <label for="username" class="col-sm-form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
        </div>
        <div class="col-sm">
            <label for="job" class="col-sm-form-label">Field:</label>
            <input type="text" class="form-control" id="job" name="job" placeholder="e.g., Teaching" required>
        </div>
        <div class="col-sm">
            <label for="ac_no" class="col-sm-form-label">Account Number:</label>
            <input type="number" class="form-control" id="ac_no" name="ac_no" placeholder="Enter account number" required>
        </div>
    </div>

    <!-- Address and Kin Details -->
    <div class="row mb-2">
        <div class="col-sm">
            <label for="county" class="col-sm-form-label">County:</label>
            <input type="text" class="form-control" id="county" name="county" placeholder="Enter county" required>
        </div>
        <div class="col-sm">
            <label for="ward" class="col-sm-form-label">Ward:</label>
            <input type="text" class="form-control" id="ward" name="ward" placeholder="Enter ward" required>
        </div>
        <div class="col-sm">
            <label for="areacode" class="col-sm-form-label">Physical Address:</label>
            <input type="text" class="form-control" id="areacode" name="areacode" placeholder="Enter area code" required>
        </div>
    </div>

    <!-- Next of Kin Information -->
    <div class="row mb-2">
        <div class="col-sm">
            <label for="kin" class="col-sm-form-label">Next of Kin:</label>
            <input type="text" class="form-control" id="kin" name="kin" placeholder="Enter next of kin's name" required>
        </div>
        <div class="col-sm">
            <label for="kinphone" class="col-sm-form-label">Next of Kin Phone Number:</label>
            <input type="text" class="form-control" id="kinphone" name="kinphone" placeholder="e.g., +123456789" required>
        </div>
        <div class="col-sm">
            <label for="kinrship" class="col-sm-form-label">Relationship to Next of Kin:</label>
            <input type="text" class="form-control" id="kinrship" name="kinrship" placeholder="e.g., Parent" required>
        </div>
    </div>

    <!-- File Upload and Position -->
    <div class="row mb-2">
        <div class="col-sm">
            <label for="photo" class="col-sm-form-label">Upload Passport Photo:</label>
            <input type="file" class="form-control" id="photo" name="photo" accept="image/jpeg,image/png" required>
        </div>
        <div class="col-sm">
            <label for="position" class="col-sm-form-label">Position:</label>
            <select class="form-select" id="position" name="position" required>
                <option selected value="">Select position</option>
                <option value="Receptionist">Receptionist</option>
                <option value="Teacher">Teacher</option>
                <option value="Administrator">Administrator</option>
            </select>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="row">
        <div class="col-md">
            <button type="submit" name="submit" value="addemp" class="btn btn-primary">Register</button>
        </div>
    </div>
</form>

</div>

<?php require_once "../include/footer.php"; ?>
