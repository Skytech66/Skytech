<?php
// Database connection
$db = new SQLite3('pickup_requests.db');

// Ensure the 'status' column exists
$columns = [];
$result = $db->query("PRAGMA table_info(pickup_requests)");
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $columns[] = $row['name'];
}

if (!in_array('status', $columns)) {
    $db->exec("ALTER TABLE pickup_requests ADD COLUMN status TEXT DEFAULT 'Pending'");
}

// Handle AJAX request for approving/rejecting
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['id'])) {
    $id = (int) $_POST['id'];
    $status = ($_POST['action'] === 'approve') ? 'Approved' : 'Rejected';

    $updateQuery = $db->prepare("UPDATE pickup_requests SET status = :status WHERE id = :id");
    $updateQuery->bindValue(':status', $status, SQLITE3_TEXT);
    $updateQuery->bindValue(':id', $id, SQLITE3_INTEGER);
    $updateQuery->execute();

    echo json_encode(['success' => true, 'new_status' => $status]);
    exit;
}

// Fetch pickup requests
$result = $db->query("SELECT * FROM pickup_requests ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pickup Requests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* General Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f5f5f5; color: #333; }
        .header { background: #007bff; color: white; padding: 15px; text-align: center; font-size: 24px; font-weight: bold; display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); }
        .header i { font-size: 26px; }
        .container { width: 95%; max-width: 1200px; margin: 20px auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow-x: auto; }
        .filters { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px; justify-content: space-between; }
        .filters input, .filters select { padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: center; }
        th { background: #007bff; color: white; text-transform: uppercase; }
        tr:nth-child(even) { background: #f8f9fa; }
        .btn { padding: 8px 12px; border: none; cursor: pointer; color: white; border-radius: 5px; transition: 0.3s ease; display: inline-flex; align-items: center; gap: 5px; }
        .approve { background: #28a745; } .approve:hover { background: #218838; }
        .reject { background: #dc3545; } .reject:hover { background: #c82333; }
        .status { padding: 6px 12px; border-radius: 5px; font-weight: bold; text-transform: uppercase; display: inline-flex; align-items: center; gap: 5px; }
        .pending { background: #ffc107; color: black; }
        .approved { background: #28a745; color: white; }
        .rejected { background: #dc3545; color: white; }
        @media (max-width: 768px) {
            th, td { padding: 8px; font-size: 14px; }
            .btn { padding: 6px 10px; font-size: 12px; }
            .filters { flex-direction: column; }
        }
    </style>
    <script>
        function updateRequest(id, action) {
            fetch("admin_pickup.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=${action}&id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    let statusElement = document.getElementById(`status-${id}`);
                    let newStatus = data.new_status.toLowerCase();
                    let icon = newStatus === "approved" ? '<i class="fas fa-check-circle"></i>' :
                               newStatus === "rejected" ? '<i class="fas fa-times-circle"></i>' :
                               '<i class="fas fa-hourglass-half"></i>';
                    statusElement.innerHTML = icon + " " + data.new_status;
                    statusElement.className = `status ${newStatus}`;
                }
            });
        }

        function filterRequests() {
            let searchValue = document.getElementById('search').value.toLowerCase();
            let statusFilter = document.getElementById('statusFilter').value.toLowerCase();
            let rows = document.querySelectorAll("tbody tr");

            rows.forEach(row => {
                let childName = row.children[0].innerText.toLowerCase();
                let pickupPerson = row.children[1].innerText.toLowerCase();
                let status = row.children[7].innerText.toLowerCase();
                let matchesSearch = childName.includes(searchValue) || pickupPerson.includes(searchValue);
                let matchesStatus = statusFilter === "all" || status.includes(statusFilter);

                row.style.display = matchesSearch && matchesStatus ? "" : "none";
            });
        }
    </script>
</head>
<body>

    <div class="header">
        <i class="fas fa-user-shield"></i> Admin Panel - Pickup Requests
    </div>

    <div class="container">
        <div class="filters">
            <input type="text" id="search" placeholder="Search by Child Name or Pickup Person" onkeyup="filterRequests()">
            <select id="statusFilter" onchange="filterRequests()">
                <option value="all">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Child Name</th>
                    <th>Pickup Person</th>
                    <th>Phone</th>
                    <th>Relation</th>
                    <th>Pickup Date</th>
                    <th>Time</th>
                    <th>OTP</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetchArray(SQLITE3_ASSOC)): ?>
                <tr>
                    <td><?= htmlspecialchars($row['child_name']) ?></td>
                    <td><?= htmlspecialchars($row['pickup_person']) ?></td>
                    <td><?= htmlspecialchars($row['phone_number']) ?></td>
                    <td><?= htmlspecialchars($row['relation']) ?></td>
                    <td><?= htmlspecialchars($row['pickup_date']) ?></td>
                    <td><?= htmlspecialchars($row['pickup_time']) ?></td>
                    <td><?= htmlspecialchars($row['otp']) ?></td>
                    <td>
                        <span id="status-<?= $row['id'] ?>" class="status <?= strtolower($row['status']) ?>">
                            <i class="fas fa-hourglass-half"></i> <?= $row['status'] ?>
                        </span>
                    </td>
                    <td>
                        <button class="btn approve" onclick="updateRequest(<?= $row['id'] ?>, 'approve')"><i class="fas fa-check"></i> Approve</button>
                        <button class="btn reject" onclick="updateRequest(<?= $row['id'] ?>, 'reject')"><i class="fas fa-times"></i> Reject</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

</body>
</html>