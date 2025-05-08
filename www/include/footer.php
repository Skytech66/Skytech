<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduPro</title>
    <link rel="stylesheet" href="../include/css/bootstrap.min.css">
    <link rel="stylesheet" href="../include/fullcalendar/fullcalendar.min.css">
    <style>
        .chat-box {
            position: fixed;
            bottom: 20px;
            right: 5%; /* Use percentage for responsiveness */
            width: 90%; /* Use percentage for responsiveness */
            max-width: 300px; /* Set a max width */
            background-color: #f8f9fa;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: none; /* Initially hidden */
            z-index: 1000;
        }

        .chat-header {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
            display: flex;
            align-items: center;
        }

        .chat-header img {
            width: 30px; /* Adjust the size of the icon */
            height: 30px; /* Adjust the size of the icon */
            margin-right: 10px; /* Space between icon and text */
        }

        .chat-body {
            padding: 10px;
        }

        .ai-icon {
            position: fixed;
            bottom: 30px; /* Adjusted to move higher */
            right: 30px; /* Adjusted to move closer to the edge */
            display: block; /* Ensure it's displayed */
            z-index: 1000;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1); /* Scale up to 110% */
            }
            100% {
                transform: scale(1);
            }
        }

        .ai-icon img {
            width: 80px; /* Increased size of the AI icon */
            height: 80px; /* Increased size of the AI icon */
            cursor: pointer; /* Change cursor to pointer on hover */
            animation: pulse 1.5s infinite; /* Apply the pulsing animation */
        }

        /* Loading animation */
        .loading {
            display: none; /* Initially hidden */
            width: 50px; /* Width of the loading animation */
            height: 50px; /* Height of the loading animation */
            border: 5px solid #007bff; /* Border color */
            border-top: 5px solid transparent; /* Top border transparent for spinning effect */
            border-radius: 50%; /* Make it circular */
            animation: spin 1s linear infinite; /* Spin animation */
            margin: 0 auto; /* Center the loading animation */
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Sound wave animation */
        .sound-wave {
            display: none; /* Initially hidden */
            width: 100%;
            height: 20px;
            position: relative;
            overflow: hidden;
        }

        .wave {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(0, 123, 255, 0.5) 25%, rgba(0, 123, 255, 0) 50%);
            animation: wave-animation 1.5s infinite;
        }

        @keyframes wave-animation {
            0% {
                transform: translateX(-100%);
            }
            100% {
                transform: translateX(100%);
            }
        }

        /* Media Queries for Mobile Responsiveness */
        @media (max-width: 600px) {
            .chat-box {
                width: 95%; /* Make chat box wider on small screens */
                right: 2%; /* Adjust right position */
            }

            .ai-icon img {
                width: 60px; /* Smaller AI icon on mobile */
                height: 60px; /* Smaller AI icon on mobile */
            }
        }
    </style>
</head>
<body>

<!-- Your existing modals here -->

<!-- Chat Box -->
<div id="chatBox" class="chat-box">
    <div class="chat-header">
        <img src="op.jpeg" alt="AI Icon"> <!-- Add your AI icon image here -->
        <h5>Hi, Its Eva from Swipeware</h5>
    </div>
    <div class="chat-body">
        <div id="loading" class="loading"></div> <!-- Loading animation -->
        <p id="chatMessage">Your Ai assistant. Please let me know if you need help</p>
        <div id="soundWave" class="sound-wave">
            <div class="wave"></div>
        </div>
    </div>
</div>

<!-- Include the footer with the AI Icon -->
<div id="aiIcon" class="ai-icon">
    <a href="javascript:void(0);" onclick="openChat()">
        <img src="siri.png" alt="AI Assistant Icon"> <!-- Replace with your AI icon image -->
    </a>
</div>

<script src="../include/js/jquery.min.js"></script>
<script src="../include/js/popper.js"></script>
<script src="../include/js/bootstrap.min.js"></script>
<script src="../include/fullcalendar/lib/moment.min.js"></script>
<script src="../include/fullcalendar/fullcalendar.min.js"></script>

<script>
    // Function to show the chat box
    function showChatBox() {
        var chatBox = document.getElementById('chatBox');
        var chatMessage = "Hi, Its Eva from Swipeware. Your AI assistant, Please let me know if you need anything"; // Full message
        chatBox.style.display = 'block'; // Show the chat box

        // Show sound wave animation for the first message
        var soundWave = document.getElementById('soundWave');
        soundWave.style.display = 'block';

        // Speak the chat message using the Web Speech API
        var speech = new SpeechSynthesisUtterance(chatMessage);
        speech.lang = 'en-US'; // Set the language
        speech.volume = 1; // Volume level (0 to 1)
        speech.rate = 1; // Speed of speech (0.1 to 10)
        speech.pitch = 1; // Pitch of the voice (0 to 2)

        // Select a female voice if available
        var voices = window.speechSynthesis.getVoices();
        var femaleVoice = voices.find(voice => voice.name.toLowerCase().includes('female')) || voices[0]; // Fallback to the first voice if no female voice is found
        speech.voice = femaleVoice;

        // Speak the message
        window.speechSynthesis.speak(speech);

        // Set a timer to hide the chat box after a specified duration (e.g., 10 seconds)
        setTimeout(function() {
            chatBox.style.display = 'none'; // Hide the chat box
            soundWave.style.display = 'none'; // Hide sound wave animation
            document.getElementById('aiIcon').style.display = 'block'; // Show the AI icon
        }, 10000); // Duration in milliseconds (10 seconds)
    }

    // Function to open the chat when the AI icon is clicked
    function openChat() {
        var chatBox = document.getElementById('chatBox');
        var loadingAnimation = document.getElementById('loading');
        var errorMessage = "Sorry, I am unable to assist at the moment due to network issues. Please contact us at swipewaretechpro24@gmail.com"; // Error message

        chatBox.style.display = 'block'; // Show the chat box
        document.getElementById('aiIcon').style.display = 'none'; // Hide the AI icon

        // Show loading animation
        loadingAnimation.style.display = 'block';
        document.getElementById('chatMessage').innerText = ""; // Clear the chat message

        // Simulate a delay for the loading animation (e.g., 2 seconds)
        setTimeout(function() {
            // Hide loading animation
            loadingAnimation.style.display = 'none';

            // Update the chat message
            document.getElementById('chatMessage').innerText = errorMessage;

            // Show sound wave animation
            var soundWave = document.getElementById('soundWave');
            soundWave.style.display = 'block';

            // Speak the error message using the Web Speech API
            var speech = new SpeechSynthesisUtterance(errorMessage);
            speech.lang = 'en-US'; // Set the language
            speech.volume = 1; // Volume level (0 to 1)
            speech.rate = 1; // Speed of speech (0.1 to 10)
            speech.pitch = 1; // Pitch of the voice (0 to 2)

            // Select a female voice if available
            var voices = window.speechSynthesis.getVoices();
            var femaleVoice = voices.find(voice => voice.name.toLowerCase().includes('female')) || voices[0]; // Fallback to the first voice if no female voice is found
            speech.voice = femaleVoice;

            // Speak the error message
            window.speechSynthesis.speak(speech);

            // Hide the chat box after the speech has finished
            speech.onend = function() {
                chatBox.style.display = 'none'; // Hide the chat box
                soundWave.style.display = 'none'; // Hide sound wave animation
                document.getElementById('aiIcon').style.display = 'block'; // Show the AI icon
            };
        }, 2000); // Duration of the loading animation (2 seconds)
    }

    // Call the function to show the chat box when the page loads
    window.onload = function() {
        // Check if the current page is the dashboard
        if (window.location.href.includes("dashboard")) {
            // Wait for voices to be loaded
            window.speechSynthesis.onvoiceschanged = function() {
                showChatBox();
            };
        }
    };
</script>
<script src="../include/js/main.js"></script>
<script src="../include/js/popper.js"></script>
<script src="../include/js/bootstrap.min.js"></script>

<script src="../include/fullcalendar/lib/moment.min.js"></script>
<script src="../include/fullcalendar/fullcalendar.min.js"></script>
	
  </body>
</html>
</body>
</html>