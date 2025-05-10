<?php
session_start();
require 'db_connect.php'; // Ensure this file correctly connects to SQLite

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $stmt = $db->prepare("SELECT id, name, assigned_class, password FROM teacher WHERE email = ?");
        $stmt->execute([$email]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($teacher && password_verify($password, $teacher['password'])) {
            $_SESSION['teacher_id'] = $teacher['id'];
            $_SESSION['teacher_name'] = $teacher['name'];
            $_SESSION['assigned_class'] = $teacher['assigned_class'];

            // Redirect to the dashboard
            header("Location: reg_dashboard.php");
            exit;
        } else {
            $error = "Invalid email or password.";
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Login | EduTrack</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #121212;
            color: #fff;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 400px;
            margin: 100px auto;
            background: #1e1e1e;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0px 0px 10px rgba(0, 255, 255, 0.5);
        }
        h2 { color: #00ffff; }
        input, button {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 6px;
        }
        input { background: #333; color: #fff; }
        button {
            background: #00ffff;
            color: #000;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background: #00cccc; }
        .register-button {
            background: #00ffff;
            color: #000;
            font-weight: bold;
        }
        .register-button:hover { background: #00cc00; }
    </style>
</head>
<body>
    <div class="container">
        <h2>👨‍🏫 Teacher Login</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <button type="submit">Login</button>
        </form>
        <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
        <a href="add_teacher.php">
            <button class="register-button">Register as Teacher</button>
        </a>
    </div>
</body>
</html>
