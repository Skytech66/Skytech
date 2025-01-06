<?php require_once "header.php"; ?>

<div class="container mt-5">
    <div class="panel panel-default rounded shadow-lg">
        <div class="panel-heading text-center py-4 bg-gradient-teal rounded-top">
            <h4 class="text-white font-weight-bold">Secretary Dashboard</h4>
        </div>
        <div class="panel-body">
            <div class="row mb-4">
                <!-- Total Students -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-light-gray text-dark border-0 rounded p-4 shadow-sm hover-effect">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold mb-0">Total Students</h5>
                            <i class="fas fa-user-graduate text-teal" style="font-size:40px;"></i>
                        </div>
                        <?php
                            $stmt = $conn->query("SELECT COALESCE(COUNT(name), 0) AS 'tstudents' FROM student");
                            while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
                        ?>
                        <h2 class="display-4"><?php echo $row['tstudents']; ?></h2>
                        <?php } ?>
                    </div>
                </div>

                <!-- Total Subjects -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-light-gray text-dark border-0 rounded p-4 shadow-sm hover-effect">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold mb-0">Total Subjects</h5>
                            <i class="fas fa-folder-open text-peach" style="font-size:40px;"></i>
                        </div>
                        <?php
                            $smt = $conn->query("SELECT COALESCE(COUNT(name), 0) AS 'tsubject' FROM subject");
                            while ($res = $smt->fetchArray(SQLITE3_ASSOC)) {
                        ?>
                        <h2 class="display-4"><?php echo $res['tsubject']; ?></h2>
                        <?php } ?>
                    </div>
                </div>

                <!-- Total Classes -->
                <div class="col-md-4 mb-4">
                    <div class="card bg-light-gray text-dark border-0 rounded p-4 shadow-sm hover-effect">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title font-weight-bold mb-0">Total Classes</h5>
                            <i class="fas fa-laptop-house text-teal" style="font-size:40px;"></i>
                        </div>
                        <?php
                            $stm = $conn->query("SELECT COALESCE(COUNT(examname), 0) AS 'Texam' FROM exam");
                            while ($rows = $stm->fetchArray(SQLITE3_ASSOC)) {
                        ?>
                        <h2 class="display-4"><?php echo $rows['Texam']; ?></h2>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Calendar of Events -->
            <div class="row">
                <div class="col-12">
                    <h4 class="text-dark text-center">Calendar of Events</h4>
                    <hr class="bg-teal">
                    <div id="calendar" class="rounded shadow-sm"></div>
                </div>
            </div>
            
            <div id="pageNavPosition" class="pager-nav mt-4"></div>
        </div>
    </div>
</div>

<?php require_once "../include/footer.php"; ?>

<!-- Additional CSS for Smart & Responsive Look -->
<style>
    /* Custom Color Scheme */
    .bg-gradient-teal {
        background: linear-gradient(to right, #4C8B91, #3a6f77);
    }

    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.1);
    }

    .bg-light-gray {
        background-color: #f7f7f7;
    }

    .text-dark {
        color: #333333 !important;
    }

    .text-teal {
        color: #4C8B91 !important;
    }

    .text-peach {
        color: #F2C14E !important;
    }

    .border-0 {
        border: none;
    }

    .panel {
        background: #ffffff;
        border-radius: 10px;
    }

    .panel-heading {
        background: #4C8B91;
        border-radius: 10px 10px 0 0;
        padding: 20px;
    }

    .font-weight-bold {
        font-weight: 600;
    }

    .display-4 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    h4 {
        font-weight: 500;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .panel-body {
        padding: 30px;
    }

    .row {
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .col-md-4 {
            flex: 0 0 100%;
            max-width: 100%;
        }
    }
</style>
