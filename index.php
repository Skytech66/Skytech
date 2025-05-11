<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>EduPro | Learning Management System</title>
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.css" rel="stylesheet" />
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --text-dark: #2b2d42;
            --text-light: #8d99ae;
            --bg-light: #f8f9fa;
            --border-radius: 12px;
            --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-light);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        /* Splash Screen Styles */
        #splash-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 1;
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
        }

        #splash-screen img {
            width: 120px;
            height: auto;
            transition: transform 0.4s ease;
            filter: drop-shadow(0 0 15px rgba(67, 97, 238, 0.5));
        }

        .splash-logo-text {
            color: white;
            font-size: 24px;
            font-weight: 600;
            margin-top: 20px;
            letter-spacing: 1px;
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 30px;
        }

        .loading-bar {
            width: 200px;
            height: 4px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .loading-progress {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            transition: width 0.5s ease;
            border-radius: 2px;
        }

        .loading-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        /* Main Content Styles */
        #main-content {
            width: 100%;
            min-height: 100vh;
            display: none;
            justify-content: center;
            align-items: center;
            background: url('images/back.png') no-repeat center center;
            background-size: cover;
            position: relative;
        }

        #main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(2px);
            z-index: 0;
        }

        .login-container {
            position: relative;
            padding: 50px 40px;
            background-color: rgba(255, 255, 255, 0.96);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 420px;
            text-align: center;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(8px);
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: var(--transition);
            margin: 20px;
        }

        .login-container:hover {
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .logo-container {
            margin-bottom: 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin-bottom: 15px;
            filter: drop-shadow(0 4px 8px rgba(67, 97, 238, 0.2));
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .logo-subtext {
            font-size: 14px;
            color: var(--text-light);
            font-weight: 400;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            font-size: 15px;
            border: 1px solid #e0e0e0;
            border-radius: var(--border-radius);
            transition: var(--transition);
            background-color: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
            outline: none;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 14px;
            border-radius: var(--border-radius);
            cursor: pointer;
            width: 100%;
            font-size: 15px;
            font-weight: 600;
            border: none;
            transition: var(--transition);
            margin-top: 10px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 12px rgba(67, 97, 238, 0.2);
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, #3730a3 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(67, 97, 238, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .parent-login-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: var(--text-light);
            transition: color 0.2s ease;
            text-decoration: none;
        }

        .parent-login-link:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }

        .unauthorized-text {
            color: #e63946;
            font-size: 12px;
            font-weight: 500;
            margin-top: 30px;
            padding: 10px;
            border-top: 1px solid #f0f0f0;
            font-style: italic;
        }

        .ai-text {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            z-index: 2;
            letter-spacing: 0.5px;
            background: rgba(0, 0, 0, 0.3);
            padding: 6px 12px;
            border-radius: 20px;
            backdrop-filter: blur(5px);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 16px;
        }

        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .login-container {
                padding: 40px 30px;
                margin: 15px;
            }

            .logo {
                width: 70px;
                height: 70px;
            }

            .logo-text {
                font-size: 22px;
            }
        }

        @media screen and (max-width: 480px) {
            .login-container {
                padding: 30px 20px;
                margin: 10px;
            }

            .logo {
                width: 60px;
                height: 60px;
            }

            .logo-text {
                font-size: 20px;
            }

            .btn-login {
                padding: 12px;
            }
        }
    </style>
</head>
<body>

    <!-- Splash Screen -->
    <div id="splash-screen" class="animate__animated animate__fadeIn">
        <img id="splash-image" src="fly.png" alt="EduPro Loading" class="animate__animated animate__pulse">
        <div class="splash-logo-text">EduPro</div>
        <div class="loading-container">
            <div class="loading-bar">
                <div class="loading-progress" id="loading-progress"></div>
            </div>
            <div class="loading-text" id="loading-text">Initializing system...</div>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <div class="login-container animate__animated animate__fadeInUp">
            <div class="logo-container">
                <img src="./images/icon.png" alt="EduPro Logo" class="logo">
                <div class="logo-text">EduPro</div>
                <div class="logo-subtext">Learning Management System</div>
            </div>

            <form action="www/include/action.php" method="POST" aria-label="Login Form">
                <div class="form-group">
                    <label for="uname" class="form-label">Username</label>
                    <div class="input-icon">
                        <input type="text" class="form-control" id="uname" name="uname" placeholder="Enter your username" required aria-required="true">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-icon">
                        <input type="password" class="form-control" id="password" name="password" minlength="8" placeholder="Enter your password" required aria-required="true">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" name="submit" value="login" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
                
                <a href="admin/qq/Parent_login.php" class="parent-login-link">
                    <i class="fas fa-user-friends"></i> Login as Parent
                </a>
            </form>

            <div class="unauthorized-text">
                Unauthorized access is strictly prohibited. All activities are monitored.
            </div>
        </div>

        <div class="ai-text">Powered by AI Technology</div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.2/mdb.min.js"></script>
    <script>
        // Enhanced splash screen with progress bar
        const images = ['fly.png', 'flyy.jpg', 'hot.gif'];
        const loadingTexts = ['Initializing system...', 'Loading modules...', 'Almost ready...'];
        let currentImageIndex = 0;
        let progress = 0;
        const progressIncrement = 100 / (images.length * 2); // Adjust progress increment

        const splashImage = document.getElementById('splash-image');
        const loadingText = document.getElementById('loading-text');
        const loadingProgress = document.getElementById('loading-progress');
        const splashScreen = document.getElementById('splash-screen');

        function updateProgress() {
            progress += progressIncrement;
            if (progress > 100) progress = 100;
            loadingProgress.style.width = `${progress}%`;
            
            // Update loading text based on progress
            if (progress < 30) {
                loadingText.textContent = loadingTexts[0];
            } else if (progress < 70) {
                loadingText.textContent = loadingTexts[1];
            } else {
                loadingText.textContent = loadingTexts[2];
            }
        }

        function changeImage() {
            splashImage.src = images[currentImageIndex];
            splashImage.classList.add('animate__pulse');
            
            // Update progress at each image change
            updateProgress();
            
            // Remove animation class after it completes
            setTimeout(() => {
                splashImage.classList.remove('animate__pulse');
            }, 1000);

            currentImageIndex++;
            
            if (currentImageIndex < images.length) {
                setTimeout(changeImage, 2000); // Change image every 2 seconds
            } else {
                // Complete the progress bar
                const progressInterval = setInterval(() => {
                    progress += 5;
                    loadingProgress.style.width = `${progress}%`;
                    if (progress >= 100) {
                        clearInterval(progressInterval);
                        // Hide splash screen after completion
                        setTimeout(() => {
                            splashScreen.style.opacity = '0';
                            setTimeout(() => {
                                splashScreen.style.display = 'none';
                                document.getElementById('main-content').style.display = 'flex';
                            }, 800);
                        }, 500);
                    }
                }, 100);
            }
        }

        // Start the splash screen sequence
        setTimeout(changeImage, 1500);

        // Add animation to form elements when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.form-control, .btn-login');
            formElements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 100 + 500);
            });
        });
    </script>
</body>
</html>
