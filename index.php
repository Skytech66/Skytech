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
    <!-- Custom Styles -->
    <link rel="stylesheet" href="../include/css/style.css">

    <style>
        /* Splash Screen Styles */
        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column; /* Stack items vertically */
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 1s ease-out, transform 1s ease-out;
            background-color: black; /* Set background color for the splash screen */
        }

        #splash-screen img {
            width: 150px; /* Adjust size of the gif */
            height: auto;
            transition: transform 0.3s ease; /* Smooth transition for scaling */
        }

        /* Pulsing Animation */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2); /* Increased scale for more obvious pulsing */
            }
            100% {
                transform: scale(1);
            }
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        /* Main Content Styles */
        #main-content {
            width: 100%;
            height: 100%;
            display: none; /* Initially hidden */
            justify-content: center;
            align-items: center;
            background: url('images/back.png') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            position: relative;
            padding: 50px;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 450px;
            text-align: center;
            height: 500px;
            margin: 10px auto;
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
            margin-top: 20px;
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
            font-size: 12px;
            font-weight: bold;
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }

        /* Loading Text Styles */
                .loading-text {
            margin-top: 10px;
            font-size: 18px;
            color: #fff; /* Change color as needed */
            display: none; /* Initially hidden */
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .container {
                padding: 20px;
                height: auto;
            }

            .avatar {
                width: 80px;
                height: 80px;
            }

            .btn-login {
                padding: 12px;
                font-size: 14px;
            }

            .ai-text {
                font-size: 16px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                padding: 15px;
                height: auto;
            }

            .avatar {
                width: 70px;
                height: 70px;
            }

            .btn-login {
                padding: 10px;
                font-size: 14px;
            }

            .ai-text {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

    <!-- Splash Screen -->
    <div id="splash-screen">
        <img id="splash-image" src="fly.png" alt="Loading..."> <!-- Initial image -->
        <div id="loading-text" class="loading-text">Loading...</div> <!-- Loading text -->
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <div class="container">
            <form action="include/action.php" method="POST" aria-label="Login Form">
                <div class="imgcontainer">
                    <img src="./images/icon.png" alt="Avatar" class="avatar">
                </div>

                <label for="uname">Username</label>
                <input type="text" class="form-control" id="uname" name="uname" placeholder="Username" required aria-required="true">

                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" minlength="8" placeholder="Password" required aria-required="true">

                <button type="submit" name="submit" value="login" class="btn-login">Login</button>
                <p style="text-align: center; margin-top: 10px;">
                    <a href="admin/qq/Parent_login.php" style="color: inherit; text-decoration: none; font-weight: bold;">
                        Login as Parent
                    </a>
                </p>
            </form>

            <!-- Unauthorized Login Message -->
            <div class="unauthorized-text">
                Unauthorized login is strictly prohibited.
            </div>
        </div>

        <div class="ai-text">Powered by AI</div>
    </div>

    <script>
        // Image transition sequence
        const images = ['fly.png', 'flyy.jpg', 'hot.gif'];
        const backgrounds = ['black', 'white', 'white']; // Background colors for each image
        let currentImageIndex = 0;

        function changeImage() {
            const splashImage = document.getElementById('splash-image');
            const splashScreen = document.getElementById('splash-screen');
            const loadingText = document.getElementById('loading-text');

            // Remove previous animation class
            splashImage.classList.remove('pulse');

            splashImage.src = images[currentImageIndex];
            splashScreen.style.backgroundColor = backgrounds[currentImageIndex]; // Change background color

            // Show loading text only for the last image
            if (currentImageIndex === 2) {
                loadingText.style.display = 'block'; // Show loading text
            } else {
                loadingText.style.display = 'none'; // Hide loading text
            }

            if (currentImageIndex === 1) {
                // Add pulse animation for the second image
                splashImage.classList.add('pulse');
                setTimeout(() => {
                    currentImageIndex++;
                    changeImage(); // Move to the next image after the pulse effect
                }, 4000); // Retain the pulsing effect for 4 seconds
            } else {
                currentImageIndex++;
                if (currentImageIndex < images.length) {
                    // Delay for the first image (5 seconds) and second image (4 seconds)
                    const delay = currentImageIndex === 1 ? 5000 : 2000; 
                    setTimeout(changeImage, delay); // Change image after the specified delay
                } else {
                    // After the last image, hide the splash screen
                    setTimeout(function() {
                        splashScreen.style.opacity = '0';
                        splashScreen.style.transform = 'translateY(-100%)';
                        setTimeout(function() {
                            splashScreen.style.display = 'none';
                            document.getElementById('main-content').style.display = 'flex'; // Show main content
                        }, 1000); // Wait for the transition to complete before hiding the splash screen
                    }, 6000); // Wait for the last image to be displayed
                }
            }
        }

        // Start the image transition
        changeImage();
    </script>
</body>
</html>
