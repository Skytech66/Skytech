<?php
session_start();
include '../config.php'; // Database connection file

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit();
}

// Handle adding timetable entries
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_timetable'])) {
    $class = $_POST['class'];
    $day = $_POST['day'];
    $time_slot = $_POST['time_slot'];
    $subject = $_POST['subject'];
    $teacher = $_POST['teacher'];

    // Check for conflicts
    $stmt = $db->prepare("SELECT * FROM timetable WHERE class = ? AND day = ? AND time_slot = ?");
    $stmt->execute([$class, $day, $time_slot]);
    if ($stmt->fetch()) {
        $error = "Schedule conflict detected!";
    } else {
        $stmt = $db->prepare("INSERT INTO timetable (class, day, time_slot, subject, teacher) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$class, $day, $time_slot, $subject, $teacher]);
        $success = "Timetable entry added successfully!";
    }
}

// Fetch timetable entries
$timetable = $db->query("SELECT * FROM timetable ORDER BY class, day, time_slot")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Timetable Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Timetable Management</h2>
    
    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p class='success'>$success</p>"; ?>
    
    <form method="post">
        <label>Class:</label>
        <select name="class" required>
            <?php for ($i = 1; $i <= 9; $i++) echo "<option>Basic $i</option>"; ?>
        </select>

        <label>Day:</label>
        <select name="day" required>
            <option>Monday</option><option>Tuesday</option><option>Wednesday</option>
            <option>Thursday</option><option>Friday</option>
        </select>

        <label>Time Slot:</label>
        <input type="text" name="time_slot" required placeholder="E.g. 8:00 AM - 9:00 AM">

        <label>Subject:</label>
        <input type="text" name="subject" required>

        <label>Teacher:</label>
        <input type="text" name="teacher" required>

        <button type="submit" name="add_timetable">Add Timetable Entry</button>
    </form>

    <h3>Existing Timetable</h3>
    <table border="1">
        <tr>
            <th>Class</th><th>Day</th><th>Time Slot</th><th>Subject</th><th>Teacher</th>
        </tr>
        <?php foreach ($timetable as $entry): ?>
            <tr>
                <td><?= $entry['class'] ?></td>
                <td><?= $entry['day'] ?></td>
                <td><?= $entry['time_slot'] ?></td>
                <td><?= $entry['subject'] ?></td>
                <td><?= $entry['teacher'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
