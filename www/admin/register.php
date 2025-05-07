<?php
// SQLite database file
$db_file = 'school_fees_management.db';

try {
    $conn = new PDO('sqlite:' . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get form data
        $parent_name = $_POST['parent_name'];
        $id = $_POST['id']; // Changed from student_id to id
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Prepare and execute the SQL query to insert data into the parent_accounts table
        $stmt = $conn->prepare("INSERT INTO parent_accounts (parent_name, id, password, email, phone) 
                               VALUES (:parent_name, :id, :password, :email, :phone)");

        $stmt->bindParam(':parent_name', $parent_name);
        $stmt->bindParam(':id', $id); // Changed from student_id to id
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Registration successful! You can now log in.";
        } else {
            echo "Error: Could not register the account.";
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .registration-form {
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 400px;
        }
        .registration-form h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .registration-form input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .registration-form button {
            width: 100%;
            padding: 10px;
            background-color: #1e3a5f;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .registration-form button:hover {
            background-color: #C9A227;
        }
    </style>
</head>
<body>

    <div class="registration-form">
        <h2>Parent Registration</h2>
        <form method="POST" action="">
            <input type="text" name="parent_name" placeholder="Parent's Full Name" required>
            <input type="text" name="id" placeholder="ID" required> <!-- Changed from student_id to id -->
            <input type="password" name="password" placeholder="Password" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <button type="submit">Register</button>
        </form>
    </div>

</body>
</html>