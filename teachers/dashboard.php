<?php require_once "header.php"; ?>
<div class="container mt-4">
    <div class="panel panel-default">
        <div class="panel-heading bg-light rounded">
            <h4 class="mb-4 text-dark">
                <i class="fas fa-chalkboard-teacher"></i> Teachers Dashboard
            </h4>
        </div>
        <div class="panel-body bg-light rounded shadow-sm">
            <div class="row mb-5">
                <!-- Total Students -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="dash-box bg-gradient-primary rounded shadow-lg p-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column text-center">
                            <i class="fas fa-user-graduate text-white" style="font-size: 40px;" data-toggle="tooltip" title="Total Students"></i>
                            <h5 class="text-white mt-2">Total Students</h5>
                            <?php
                                $stmt = $conn->query("SELECT coalesce(COUNT(name),0) as 'tstudents' FROM student");
                                while($row = $stmt->fetchArray(SQLITE3_ASSOC)){
                            ?>
                            <h3 class="text-white"><?php echo $row['tstudents']; ?></h3>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Total Subjects -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <div class="dash-box bg-gradient-info rounded shadow-lg p-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column text-center">
                            <i class="fas fa-book-open text-white" style="font-size: 40px;" data-toggle="tooltip" title="Total Subjects"></i>
                            <h5 class="text-white mt-2">Total Subjects</h5>
                            <?php
                                $smt = $conn->query("SELECT coalesce(COUNT(name),0) as 'tsubject' FROM subject");
                                while($res = $smt->fetchArray(SQLITE3_ASSOC)){
                            ?>
                            <h3 class="text-white"><?php echo $res['tsubject']; ?></h3>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <!-- Total Classes -->
                <div class="col-md-4">
                    <div class="dash-box bg-gradient-success rounded shadow-lg p-4 d-flex justify-content-between align-items-center">
                        <div class="d-flex flex-column text-center">
                            <i class="fas fa-school text-white" style="font-size: 40px;" data-toggle="tooltip" title="Total Classes/Exams"></i>
                            <h5 class="text-white mt-2">Total Classes</h5>
                            <?php
                                $stm = $conn->query("SELECT coalesce(COUNT(examname),0) as 'Texam' FROM exam");
                                while($rows = $stm->fetchArray(SQLITE3_ASSOC)){
                            ?>
                            <h3 class="text-white"><?php echo $rows['Texam']; ?></h3>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <h4 class="mb-4 text-dark text-center">Calendar of Events</h4>
                    <hr>
                    <div class="response"></div>
                    <div id="calendar"></div>
                </div>
            </div>

           
<?php require_once "../include/footer.php"; ?>

<!-- Add these scripts for better interactivity -->
<script>
    $(document).ready(function () {
        // Enable tooltips
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

<style>
    /* Color Schemes */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #0069d9);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8, #138496);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #218838);
    }

    .dash-box {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        color: white;
    }

    .dash-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .panel-heading {
        background-color: #f9f9f9;
        border-bottom: 1px solid #ddd;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
    }

    .panel-body {
        background-color: #f1f3f5;
        padding: 30px;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .text-dark {
        font-size: 1.3em;
        font-weight: 600;
    }

    .container {
        padding-top: 30px;
    }

    .row {
        margin-bottom: 30px;
    }

    .text-light {
        font-weight: bold;
        font-size: 1.5em;
    }

    .shadow-lg {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .dash-box h5 {
        font-size: 1.2em;
        font-weight: bold;
    }

    .dash-box h3 {
        font-size: 2.5em;
        font-weight: bold;
    }

    .panel-body .row {
        margin-bottom: 20px;
    }
</style>
