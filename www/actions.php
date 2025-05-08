<?php
// Connect to the SQLite database (school.db)
$conn = new SQLite3('C:/xampp/htdocs/MainProject/www/db/school.db');  // Adjust the path to your actual database file

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . $conn->lastErrorMsg());
}

// Check if form is submitted
if (isset($_POST['submit'])) {
    // Get form data
    $to = $_POST['to'];  // Recipient (could be "all", "active", or a specific recipient ID)
    $message = $_POST['message'];  // SMS message
    $recordedby = $_POST['recordedby'];  // The ID of the user submitting the SMS (could be session-based)

    // Get the current date and time for `date_sent`
    $date_sent = date("Y-m-d H:i:s");  // Format: YYYY-MM-DD HH:MM:SS

    // SQL query to insert data into sms table
    $sql = "INSERT INTO sms (recipient, message, date_sent, recorded_by) 
            VALUES (:recipient, :message, :date_sent, :recordedby)";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':recipient', $to, SQLITE3_TEXT);
    $stmt->bindValue(':message', $message, SQLITE3_TEXT);
    $stmt->bindValue(':date_sent', $date_sent, SQLITE3_TEXT);
    $stmt->bindValue(':recordedby', $recordedby, SQLITE3_INTEGER);  // assuming recorded_by is an integer

    // Execute the query
    if ($stmt->execute()) {
        // Redirect or confirm success
        header("Location: sms.php?status=success");  // Redirect back to the SMS page with a success message
        exit();
    } else {
        // Handle errors (optional)
        echo "Error: " . $conn->lastErrorMsg();
    }
}

// Close the database connection
$conn->close();
?>
