<?php
// Connect to SQLite database
$db = new SQLite3('students_records.db');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];

    // Insert new bus location
    $db->exec("INSERT INTO bus_location (latitude, longitude) VALUES ($latitude, $longitude)");

    echo "Bus location updated!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Bus Location</title>
</head>
<body>
    <h2>Update Bus Location</h2>
    <form method="POST">
        <label>Latitude:</label>
        <input type="text" name="latitude" required><br><br>

        <label>Longitude:</label>
        <input type="text" name="longitude" required><br><br>

        <button type="submit">Update Location</button>
    </form>
</body>
</html>
