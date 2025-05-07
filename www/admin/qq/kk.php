<?php
// Database file path
$db_file = 'pickup_requests.db';

try {
    // Connect to SQLite database
    $db = new SQLite3($db_file);
    $db->busyTimeout(5000); // Prevent locking issues

    // Query to fetch all pickup requests
    $query = "SELECT * FROM pickup_requests ORDER BY id DESC";
    $results = $db->query($query);

    echo "<h2>Pickup Requests</h2>";
    echo "<table border='1' cellpadding='10' cellspacing='0'>";
    echo "<tr>
            <th>ID</th>
            <th>Child Name</th>
            <th>Pickup Person</th>
            <th>Phone Number</th>
            <th>Relation</th>
            <th>Pickup Date</th>
            <th>Pickup Time</th>
            <th>OTP</th>
        </tr>";

    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['child_name']}</td>
                <td>{$row['pickup_person']}</td>
                <td>{$row['phone_number']}</td>
                <td>{$row['relation']}</td>
                <td>{$row['pickup_date']}</td>
                <td>{$row['pickup_time']}</td>
                <td>{$row['otp']}</td>
            </tr>";
    }

    echo "</table>";

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>