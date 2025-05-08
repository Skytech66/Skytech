<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $assigned_class = trim($_POST['assigned_class']);

    if (!empty($name) && !empty($email) && !empty($password) && !empty($assigned_class)) {
        try {
            // Check if email already exists
            $checkStmt = $db->prepare("SELECT id FROM teachers WHERE email = ?");
            $checkStmt->execute([$email]);

            if ($checkStmt->fetch()) {
                $error = "Email already registered!";
            } else {
                // Hash password before storing
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert new teacher
                $stmt = $db->prepare("INSERT INTO teachers (name, email, password, assigned_class) VALUES (?, ?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashed_password, $assigned_class])) {
                    header("Location: login.php?message=Teacher registered successfully! Please log in.");
                    exit();
                } else {
                    $error = "Error registering teacher.";
                }
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "All fields are required!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Teacher | Smart Attendance</title>
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
        .error { color: red; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>👨‍🏫 Add Teacher</h2>
        <form method="POST">
            <input type="text" name="name" placeholder="Enter Teacher Name" required>
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="password" name="password" placeholder="Enter Password" required>
            <input type="text" name="assigned_class" placeholder="Assigned Class (e.g., Basic 5)" required>
            <button type="submit">Add Teacher</button>
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
