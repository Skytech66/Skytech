<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Screen</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100vw;
            height: 100vh;
            background: black;
            overflow: hidden;
        }
        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            min-width: 100vw;
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <video autoplay muted playsinline id="splashVideo">
        <source src="image.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <script>
        setTimeout(function() {
            window.location.href = 'parent_login.php'; // Change to your login page filename
        }, 4000); // 4 seconds
    </script>
</body>
</html>