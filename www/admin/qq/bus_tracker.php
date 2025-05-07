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
            height: 100vh;
            background: black; /* Fallback background */
            position: relative;
        }
        img {
            display: block;
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            position: absolute;
            top: 45%; /* Moves the image slightly up */
            transform: translateY(-50%); /* Keeps it centered while shifting it upward */
        }
    </style>
    <script>
        setTimeout(function () {
            window.location.href = "bus.php"; // Redirect after 8 seconds
        }, 8000);
    </script>
</head>
<body>
    <img src="map.png" alt="Loading...">
</body>
</html>