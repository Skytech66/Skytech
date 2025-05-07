<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Word Processor</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/40.0.0/classic/ckeditor.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        .navbar {
            background-color: #007BFF;
            padding: 15px;
            color: white;
        }
        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }
        .container {
            max-width: 1000px; /* Increased max-width for the container */
            background: white;
            padding: 20px;
            margin: 20px auto;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            color: #333;
            margin-bottom: 20px;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            margin-top: 10px;
            background-color: #28A745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
        }
        button:hover {
            background-color: #218838;
            transform: scale(1.05);
        }
        .ck-editor__editable {
            min-height: 400px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background-color: #fafafa;
            width: 100%; /* Set width to 100% to fill the container */
            box-sizing: border-box; /* Ensure padding is included in the width */
        }
        footer {
            margin-top: 20px;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin Word Processor</h1>
    </div>
    <div class="container">
        <h2>Create or Edit Document</h2>
        <form method="post" action="save_document.php">
            <textarea id="editor" name="document_content"></textarea>
            <br>
            <button type="submit">Save Document</button>
        </form>
    </div>
    <footer>
        <p>&copy; 2023 Admin Word Processor. All rights reserved.</p>
    </footer>
    <script>
        ClassicEditor.create(document.querySelector('#editor'))
            .then(editor => {
                editor.ui.view.editable.element.style.height = "400px";
            })
            .catch(error => {
                console.error(error);
            });
    </script>
</body>
</html>