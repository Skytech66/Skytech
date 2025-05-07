<?php
// Database connection
$db = new SQLite3(__DIR__ . '/pickup_requests.db');

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

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = (int) $_POST['delete_id'];
    $deleteQuery = $db->prepare("DELETE FROM pickup_requests WHERE id = :id");
    $deleteQuery->bindValue(':id', $deleteId, SQLITE3_INTEGER);
    $deleteQuery->execute();

    echo json_encode(['success' => true]);
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

        /* Header Card Styles */
        :root {
            --primary: #4F46E5;
            --ai-accent: #10B981;
            --surface: #F8FAFC;
            --border: #E2E8F0;
        }
        .ai-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--ai-accent) 100%);
            color: black; /* Changed text color to black */
            border-radius: 12px;
            padding: 10px; /* Reduced padding */
            margin-bottom: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .subject-title {
            font-size: 1.8em; /* Reduced font size */
            font-weight: bold;
            margin: 0;
        }
        .container { width: 95%; max-width: 1200px; margin: 20px auto; background: white; padding: 15px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px; border: 1px solid #dee2e6; text-align: center; }
        th { background: #343a40; color: white; text-transform: uppercase; font-weight: bold; }
        tr:nth-child(even) { background: #f2f2f2; }
        tr:hover { background: #e9ecef; cursor: pointer; } /* Highlight on hover */
        .btn { padding: 10px 15px; border: none; cursor: pointer; color: white; border-radius: 8px; transition: background-color 0.3s ease, transform 0.2s ease; display: inline-flex; align-items: center; gap: 8px; font-size: 0.9em; }
        .approve { background: #4CAF50; } .approve:hover { background: #45a049; transform: scale(1.05); }
        .reject { background: #f44336; } .reject:hover { background: #e53935; transform: scale(1.05); }
        .delete { background: #f44336; } .delete:hover { background: #e53935; transform: scale(1.05); }
        .status { padding: 6px 12px; border-radius: 5px; font-weight: bold; text-transform: uppercase; display: inline-flex; align-items: center; gap: 5px; }
        .pending { background: #ffc107; color: black; }
        .approved { background: #28a745; color: white; }
        .rejected { background: #dc3545; color: white; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { margin: 0 5px; padding: 8px 12px; border: 1px solid #007bff; color: #007bff; text-decoration: none; border-radius: 5px; }
        .pagination a.active { background: #007bff; color: white; }

        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
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

        function deleteRequest(id) {
            if (confirm("Are you sure you want to delete this request?")) {
                fetch("admin_pickup.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `delete_id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // Reload the page to see the changes
                    }
                });
            }
        }

        function openModal(id) {
            fetch(`get_request_details.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data) {
                        document.getElementById('modal-body').innerHTML = `
                            <p><strong>Child Name:</strong> ${data.child_name}</p>
                            <p><strong>Pickup Person:</strong> ${data.pickup_person}</p>
                            <p><strong>Phone:</strong> ${data.phone_number}</p>
                            <p><strong>Relation:</strong> ${data.relation}</p>
                            <p><strong>Pickup Date:</strong> ${data.pickup_date}</p>
                            <p><strong>Pickup Time:</strong> ${data.pickup_time}</p>
                            <p><strong>OTP:</strong> ${data.otp}</p>
                            <p><strong>Status:</strong> ${data.status}</p>
                        `;
                        document.getElementById('myModal').style.display = "block";
                    }
                });
        }

        function closeModal() {
            document.getElementById('myModal').style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('myModal')) {
                closeModal();
            }
        }
    </script>
</head>
<body>

    <div class="ai-header">
        <h2 class="subject-title"><i class="fas fa-book"></i> Admin Panel - Pickup Requests</h2>
        <p class="mb-0"><i class="fas fa-pencil-alt"></i> Manage your pickup requests efficiently.</p>
        <a href="index.php" class="btn">Back</a> <!-- Back button -->
    </div>

    <div class="container">
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
                <tr onclick="openModal(<?= $row['id'] ?>)">
                    <td>
                        <i class="fas fa-user-graduate"></i> <?= htmlspecialchars($row['child_name']) ?>
                    </td>
                    <td>
                        <i class="fas fa-user"></i> <?= htmlspecialchars($row['pickup_person']) ?>
                    </td>
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
                        <button class="btn delete" onclick="deleteRequest(<?= $row['id'] ?>)"><i class="fas fa-trash"></i> Delete</button>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
        // Example initialization
        $itemsPerPage = 10; // Set this to your desired number of items per page
        $totalItems = 100; // This should be the total number of items from your database
        $totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages

        // Get the current page from the query string, defaulting to 1 if not set
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Ensure the page number is within the valid range
        if ($page < 1) {
            $page = 1;
        } elseif ($page > $totalPages) {
            $page = $totalPages;
        }
        ?>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">Previous</a>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Next</a>
            <?php endif; ?>
        </div>

        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <div id="modal-body"></div>
            </div>
        </div>

    </div>
</body>
</html>