<?php
// Include the configuration file
include 'config.php'; // Ensure this file defines DB_PATH

// Database connection
$db = new SQLite3(DB_PATH); // Use the defined constant for the database path

if (!$db) {
    die(json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $db->lastErrorMsg()]));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class = $_POST['class'];
    $year = $_POST['year'];
    $names = $_POST['valid_name'];
    $admnos = $_POST['valid_admno'];

    // Prepare the SQL statement
    $stmt = $db->prepare("INSERT INTO student (name, admno, class, year) VALUES (:name, :admno, :class, :year)");

    $success = true;

    // Start transaction
    $db->exec('BEGIN TRANSACTION');

    // Loop through each student and insert into the database
    for ($i = 0; $i < count($names); $i++) {
        $name = $names[$i];
        $admno = $admnos[$i];

        // Bind parameters and execute the statement
        $stmt->bindValue(':name', $name, SQLITE3_TEXT);
        $stmt->bindValue(':admno', $admno, SQLITE3_TEXT);
        $stmt->bindValue(':class', $class, SQLITE3_TEXT);
        $stmt->bindValue(':year', $year, SQLITE3_INTEGER);

        if (!$stmt->execute()) {
            $success = false;
            break; // Exit the loop on error
        }
    }

    // Check if all inserts were successful
    if ($success) {
        $db->exec('COMMIT');
        echo json_encode(['status' => 'success', 'message' => 'Students added successfully!']);
    } else {
        $db->exec('ROLLBACK');
        echo json_encode(['status' => 'error', 'message' => 'Error inserting data.']);
    }
}

// Close the database connection
$db->close();
?>