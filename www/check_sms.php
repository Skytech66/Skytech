<?php
// Connect to the SQLite database (school.db)
$conn = new SQLite3('C:/xampp/htdocs/MainProject/www/db/school.db');  // Adjust the path to your actual database file

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . $conn->lastErrorMsg());
}

// SQL query to fetch all data from the sms table
$sql = "SELECT * FROM sms ORDER BY sms_id DESC";

// Execute the query
$res = $conn->query($sql);

// Check if there are any rows in the result
if (!$res) {
    die("Error fetching data: " . $conn->lastErrorMsg());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check SMS Data</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>

<h1>SMS Data</h1>

<table>
    <thead>
        <tr>
            <th>Date Sent</th>
            <th>Recipient</th>
            <th>Message</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch each row from the result and display in the table
        while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['date_sent']) . "</td>";
            echo "<td>" . htmlspecialchars($row['recipient']) . "</td>";
            echo "<td>" . htmlspecialchars($row['message']) . "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
