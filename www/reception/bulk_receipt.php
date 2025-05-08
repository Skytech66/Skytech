<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Receipt Generator</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        header {
            background: #007bff;
            color: #fff;
            padding: 20px 0;
            text-align: center;
        }

        h1 {
            margin-bottom: 10px;
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            padding: 20px;
        }

        h2 {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="date"],
        select {
            margin-bottom: 10px;
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            width: 100%;
        }

        .btn:hover {
            background: #0056b3;
        }

        footer {
            text-align: center;
            padding: 10px 0;
            background: #333;
            color: #fff;
            position: relative;
            bottom: 0;
            width: 100%;
        }
    </style>
    <script src="script.js" defer></script> <!-- Link to your JavaScript file -->
</head>
<body>
    <header>
        <div class="container">
            <h1>Bulk Receipt Generator</h1>
            <p>Effortlessly generate multiple receipts based on your selections.</p>
        </div>
    </header>

    <main class="container">
        <section id="input-section" class="card">
            <h2>Select Options</h2>
            <form id="options-form">
                <label for="date-select">Select Date:</label>
                <input type="date" id="date-select" required>

                <label for="class-select">Select Class:</label>
                <select id="class-select" required>
                    <option value="">--Select Class--</option>
                    <option value="class1">Basic 6</option>
                    <option value="class2">Basiic 5</option>
                    <option value="class3">Basic 2</option>
                    <!-- Add more classes as needed -->
                </select>

                <button type="submit" class="btn">Generate Receipts</button>
            </form>
        </section>

        <section id="receipt-section" class="card">
            <h2>Generated Receipts</h2>
            <div id="receipts-container">
                <!-- Generated receipts will be displayed here -->
            </div>
        </section>
    </main>

       
    </footer>
</body>
</html>