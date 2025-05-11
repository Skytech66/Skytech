<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>EduPro | Exclusive Learning Portal</title>
    
    <!-- Font Awesome Pro -->
    <link href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600&display=swap" rel="stylesheet">
    <!-- MDB Pro -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        :root {
            --primary-color: #4e54c8;
            --primary-dark: #4349a8;
            --secondary-color: #6a3093;
            --accent-color: #8f94fb;
            --gold-accent: #d4af37;
            --platinum: #e5e4e2;
            --text-dark: #1a1a1a;
            --text-light: #7a7a7a;
            --bg-gradient: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            --border-radius: 16px;
            --box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            --transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            --glass-bg: rgba(255, 255, 255, 0.18);
            --glass-border: 1px solid rgba(255, 255, 255, 0.3);
        }

        body {
            font-family: 'Montserrat', sans-serif;
            margin: 0;
            padding: 0;
            background: var(--bg-gradient);
            color: var(--text-dark);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
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
            background: linear-gradient(135deg, #0f0c29 0%, #302b63 50%, #24243e 100%);
        }

        .splash-logo-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 40px;
        }

        #splash-image {
            width: 120px;
            height: auto;
            transition: transform 0.4s ease;
            filter: drop-shadow(0 0 20px rgba(143, 148, 251, 0.6));
        }

        .splash-logo-text {
            font-family: 'Playfair Display', serif;
            color: white;
            font-size: 32px;
            font-weight: 600;
            margin-top: 25px;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(143, 148, 251, 0.5);
        }

        .splash-tagline {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 300;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        .loading-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 40px;
            width: 300px;
        }

        .loading-bar {
            width: 100%;
            height: 3px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .loading-progress {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--accent-color), var(--gold-accent));
            transition: width 0.6s ease;
            border-radius: 3px;
        }

        .loading-text {
            color: rgba(255, 255, 255, 0.8);
            font-size: 14px;
            font-weight: 400;
            letter-spacing: 1px;
        }

        /* Main Content Styles */
        #main-content {
            width: 100%;
            min-height: 100vh;
            display: none;
            justify-content: center;
            align-items: center;
            background: url('https://images.unsplash.com/photo-1635070041078-e363dbe005cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80') no-repeat center center;
            background-size: cover;
            position: relative;
            overflow: hidden;
        }

        #main-content::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 12, 41, 0.85) 0%, rgba(48, 43, 99, 0.85) 50%, rgba(36, 36, 62, 0.85) 100%);
            z-index: 0;
        }

        .login-container {
            position: relative;
            padding: 60px 50px;
            background: var(--glass-bg);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            width: 100%;
            max-width: 480px;
            text-align: center;
            z-index: 1;
            border: var(--glass-border);
            backdrop-filter: blur(12px);
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: var(--transition);
            margin: 20px;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, rgba(78, 84, 200, 0.1) 100%);
            z-index: -1;
            animation: rotate 15s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .logo-container {
            margin-bottom: 40px;
            position: relative;
        }

        .logo {
            width: 90px;
            height: 90px;
            object-fit: contain;
            margin-bottom: 20px;
            filter: drop-shadow(0 4px 15px rgba(143, 148, 251, 0.4));
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 600;
            color: white;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        .logo-subtext {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 300;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            letter-spacing: 0.5px;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px;
            font-size: 15px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.1);
            color: white;
            letter-spacing: 0.5px;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .form-control:focus {
            border-color: var(--gold-accent);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2);
            outline: none;
            background: rgba(255, 255, 255, 0.15);
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.6);
            font-size: 16px;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 18px;
            border-radius: var(--border-radius);
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            border: none;
            transition: var(--transition);
            margin-top: 15px;
            letter-spacing: 1px;
            box-shadow: 0 8px 20px rgba(78, 84, 200, 0.3);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-dark) 100%);
            z-index: -1;
            opacity: 0;
            transition: var(--transition);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(78, 84, 200, 0.4);
        }

        .btn-login:hover::before {
            opacity: 1;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            margin-right: 10px;
        }

        .parent-login-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            transition: color 0.3s ease;
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .parent-login-link:hover {
            color: var(--gold-accent);
            text-decoration: none;
        }

        .parent-login-link i {
            margin-right: 8px;
        }

        .unauthorized-text {
            color: rgba(230, 57, 70, 0.8);
            font-size: 12px;
            font-weight: 500;
            margin-top: 35px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            font-style: italic;
            letter-spacing: 0.5px;
        }

        .ai-text {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 14px;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.8);
            z-index: 2;
            letter-spacing: 1px;
            background: rgba(0, 0, 0, 0.3);
            padding: 8px 20px;
            border-radius: 30px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ai-text i {
            color: var(--gold-accent);
            margin-right: 8px;
        }

        .decoration-element {
            position: absolute;
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, rgba(78, 84, 200, 0) 70%);
            z-index: -1;
        }

        .decoration-element:nth-child(1) {
            top: -50px;
            left: -50px;
            width: 300px;
            height: 300px;
        }

        .decoration-element:nth-child(2) {
            bottom: -80px;
            right: -80px;
            width: 400px;
            height: 400px;
        }

        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }

        /* Responsive Styles */
        @media screen and (max-width: 768px) {
            .login-container {
                padding: 50px 40px;
                margin: 20px;
            }

            .logo {
                width: 80px;
                height: 80px;
            }

            .logo-text {
                font-size: 28px;
            }
        }

        @media screen and (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
                margin: 15px;
            }

            .logo {
                width: 70px;
                height: 70px;
            }

            .logo-text {
                font-size: 24px;
            }

            .btn-login {
                padding: 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Splash Screen -->
    <div id="splash-screen" class="animate__animated animate__fadeIn">
        <div class="splash-logo-container">
            <img id="splash-image" src="fly.png" alt="EduPro Loading" class="animate__animated animate__pulse">
            <div class="splash-logo-text">EduPro</div>
            <div class="splash-tagline">EXCLUSIVE LEARNING PORTAL</div>
        </div>
        
        <div class="loading-container">
            <div class="loading-bar">
                <div class="loading-progress" id="loading-progress"></div>
            </div>
            <div class="loading-text" id="loading-text">Initializing secure connection...</div>
        </div>
        
        <lottie-player 
            src="https://assets4.lottiefiles.com/packages/lf20_pojzucga.json" 
            background="transparent" 
            speed="1" 
            style="position: absolute; bottom: 20px; right: 20px; width: 120px; height: 120px;" 
            loop autoplay>
        </lottie-player>
    </div>

    <!-- Main Content -->
    <div id="main-content">
        <div class="decoration-element"></div>
        <div class="decoration-element"></div>
        
        <div class="login-container animate__animated animate__fadeInUp">
            <div class="logo-container">
                <img src="./images/icon.png" alt="EduPro Logo" class="logo animate__animated animate__fadeIn">
                <div class="logo-text">EduPro</div>
                <div class="logo-subtext">PREMIUM EDUCATION PORTAL</div>
            </div>

            <form action="www/include/action.php" method="POST" aria-label="Login Form">
                <div class="form-group animate__animated animate__fadeIn animate__delay-1s">
                    <label for="uname" class="form-label">USERNAME</label>
                    <div class="input-icon">
                        <input type="text" class="form-control" id="uname" name="uname" placeholder="Enter your username" required aria-required="true">
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="form-group animate__animated animate__fadeIn animate__delay-2s">
                    <label for="password" class="form-label">PASSWORD</label>
                    <div class="input-icon">
                        <input type="password" class="form-control" id="password" name="password" minlength="8" placeholder="Enter your password" required aria-required="true">
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <button type="submit" name="submit" value="login" class="btn-login animate__animated animate__fadeIn animate__delay-3s">
                    <i class="fas fa-sign-in-alt"></i> ACCESS PORTAL
                </button>
                
                <a href="admin/qq/Parent_login.php" class="parent-login-link animate__animated animate__fadeIn animate__delay-4s">
                    <i class="fas fa-user-friends"></i> PARENT ACCESS
                </a>
            </form>

            <div class="unauthorized-text animate__animated animate__fadeIn animate__delay-5s">
                <i class="fas fa-shield-alt"></i> Unauthorized access will be prosecuted
            </div>
        </div>

        <div class="ai-text">
            <i class="fas fa-brain"></i> AI-POWERED LEARNING SYSTEM
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.js"></script>
    <script>
        // Premium splash screen with enhanced loading sequence
        const images = ['fly.png', 'flyy.jpg', 'hot.gif'];
        const loadingTexts = [
            'Initializing secure connection...', 
            'Loading premium modules...', 
            'Authenticating credentials...',
            'Finalizing setup...'
        ];
        let currentImageIndex = 0;
        let progress = 0;
        const progressIncrement = 100 / (images.length * 3); // More granular progress

        const splashImage = document.getElementById('splash-image');
        const loadingText = document.getElementById('loading-text');
        const loadingProgress = document.getElementById('loading-progress');
        const splashScreen = document.getElementById('splash-screen');

        function updateProgress() {
            progress += progressIncrement;
            if (progress > 100) progress = 100;
            loadingProgress.style.width = `${progress}%`;
            
            // Update loading text based on progress
            if (progress < 25) {
                loadingText.textContent = loadingTexts[0];
            } else if (progress < 50) {
                loadingText.textContent = loadingTexts[1];
            } else if (progress < 75) {
                loadingText.textContent = loadingTexts[2];
            } else {
                loadingText.textContent = loadingTexts[3];
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
                // Complete the progress bar smoothly
                const progressInterval = setInterval(() => {
                    progress += 2;
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
                }, 50);
            }
        }

        // Start the premium loading sequence
        setTimeout(() => {
            loadingText.textContent = "Establishing secure connection...";
            setTimeout(changeImage, 1500);
        }, 500);

        // Add animation to form elements when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const formElements = document.querySelectorAll('.form-control, .btn-login, .parent-login-link');
            formElements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, index * 150 + 500);
            });
        });
    </script>
</body>
</html>
