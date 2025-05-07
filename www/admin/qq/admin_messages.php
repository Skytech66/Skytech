<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messaging Page</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
        }

        .sidebar h2 {
            margin-bottom: 20px;
        }

        .sidebar nav ul {
            list-style: none;
        }

        .sidebar nav ul li {
            margin: 15px 0;
        }

        .sidebar nav ul li a {
            color: white;
            text-decoration: none;
        }

        .sidebar nav ul li a:hover {
            text-decoration: underline;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: white;
        }

        header {
            margin-bottom: 20px;
        }

        .inbox, .send-message {
            margin-bottom: 40px;
        }

        .message-list {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            max-height: 400px;
            overflow-y: auto;
        }

        .message-item {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .message-item:last-child {
            border-bottom: none;
        }

        .timestamp {
            display: block;
            font-size: 0.8em;
            color: #999;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        form label {
            margin-bottom: 5px;
        }

        form input, form textarea {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        form button {
            padding: 10px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        form button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <h2>Admin Panel</h2>
            <nav>
                <ul>
                    <li><a href="#inbox">Inbox</a></li>
                    <li><a href="#send-message">Send Message</a></li>
                    <li><a href="#settings">Settings</a></li>
                </ul>
            </nav>
        </aside>
        <main class="main-content">
            <header>
                <h1>Messages</h1>
            </header>
            <section id="inbox" class="inbox">
                <h2>Inbox</h2>
                <div class="message-list" id="message-list">
                    <!-- Messages will be dynamically added here -->
                </div>
            </section>
            <section id="send-message" class="send-message">
                <h2>Send Message</h2>
                <form id="message-form">
                    <label for="parent-name">Parent Name:</label>
                    <input type="text" id="parent-name" required>
                    
                    <label for="message-content">Message:</label>
                    <textarea id="message-content" rows="4" required></textarea>
                    
                    <button type="submit">Send Message</button>
                </form>
            </section>
        </main>
    </div>
    <script>
        document.getElementById('message-form').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const parentName = document.getElementById('parent-name').value;
            const messageContent = document.getElementById('message-content').value;
            const timestamp = new Date().toLocaleString();

            const messageList = document.getElementById('message-list');
            const messageItem = document.createElement('div');
            messageItem.classList.add('message-item');
            messageItem.innerHTML =