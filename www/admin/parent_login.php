<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Login | EduWare</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('u.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
            flex-direction: column;
        }

        /* Header Styles */
        header {
            width: 450px; /* Matches the login frame width */
            color: #007bff;
            padding: 15px 0;
            text-align: center;
            font-size: 26px;
            font-weight: 500;
            letter-spacing: 1px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 40px; /* Moved down */
        }
        
        .container {
            width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
            margin-top: 20px; /* Moved down */
        }

        h2 {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 15px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            cursor: pointer;
            border-radius: 6px;
            font-size: 16px;
            transition: 0.3s ease-in-out;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .signup-btn {
            background: #28a745;
            margin-top: 10px;
        }

        .signup-btn:hover {
            background: #218838;
        }

        .ai-logo {
            margin-top: 20px;
            width: 90px;
            opacity: 0.8;
        }

        .ai-text {
            font-size: 14px;
            color: #555;
            text-align: center;
            margin-top: 5px;
            font-weight: bold;
        }

        @media (max-width: 480px) {
            .container, header {
                width: 90%;
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<header>EduWare</header>

<div class="container">
    <h2>Parent Login</h2>
    <?php if (isset($_SESSION['error'])) { ?>
        <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php } ?>
    <form action="parent_auth.php" method="POST">
        <input type="text" name="student_id" placeholder="Enter Student ID" required>
        <input type="password" name="password" placeholder="Enter Password" required>
        <button type="submit">Login</button>
    </form>

    <a href="register.php">
        <button type="button" class="signup-btn">Sign Up</button>
    </a>
</div>

<img src="ai.png" alt="AI Powered" class="ai-logo">
<p class="ai-text">Explore Maths and Sci Simulations</p>

</body>
</html>
