<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>EduPro</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.css" rel="stylesheet" />
    <!-- MDB JS -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.10.2/mdb.min.js"></script>
    
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../include/css/style.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Prevent scrolling during splash screen */
        }

        /* Splash Screen Styles */
        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #000; /* Set the background color to black */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transform: translateY(0);
            transition: transform 1s ease-in-out;
        }

        #splash-screen.hidden {
            transform: translateY(-100%); /* Slide the splash screen out */
            pointer-events: none; /* Prevent interaction when hidden */
        }

        #splash-screen .splash-image {
            background: url('SkyTech.png') no-repeat center center;
            background-size: contain;
            width: 100%;
            height: 100%;
        }

        #splash-screen .splash-text {
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            position: absolute;
        }

        /* Main Content Styles */
        #main-content {
            display: none; /* Hide main content until splash screen disappears */
        }

        #main-content.active {
            display: block;
            height: 100vh;
            background: url('images/') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            position: relative;
            padding: 50px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
            height: 450px; /* Increased the height */
        }

        .avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 20px;
        }

        .btn-login {
            background-color: #007bff;
            color: white;
            padding: 14px;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            border: none;
            transition: background-color 0.3s ease;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }

        .ai-text {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 18px;
            font-weight: bold;
            color: #fff;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
            z-index: 2;
            letter-spacing: 2px;
        }

        .unauthorized-text {
            color: #d9534f;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- Splash Screen -->
    <div id="splash-screen">
        <div class="splash-image"></div>
        <div class="splash-text">Skytech</div>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <div class="container">
            <form action="include/action.php" method="POST">
                <div class="imgcontainer">
                    <img src="./images/img_avatar2.png" alt="Avatar" class="avatar">
                </div>

                <label for="uname">Username</label>
                <input type="text" class="form-control" id="uname" name="uname" placeholder="Username" required>

                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" placeholder="Password" required>

                <button type="submit" name="submit" value="login" class="btn-login">Login</button>
            </form>

            <!-- Unauthorized Login Message -->
            <div class="unauthorized-text">
                Unauthorized login is strictly prohibited.
            </div>
        </div>

        <div class="ai-text">Powered by AI</div>
    </div>

    <script>
        // Hide the splash screen and show the main content after 7 seconds
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('splash-screen').classList.add('hidden'); // Apply slide-out transition to splash screen
                document.getElementById('main-content').classList.add('active');
            }, 7000); // 7 seconds delay
        });
    </script>

</body>
</html>
