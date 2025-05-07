<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $content = $_POST['document_content'] ?? '';

    if (!empty($content)) {
        $db = new SQLite3('students_records.db');

        $db->exec("CREATE TABLE IF NOT EXISTS documents (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            content TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        $stmt = $db->prepare("INSERT INTO documents (content) VALUES (:content)");
        $stmt->bindValue(':content', $content, SQLITE3_TEXT);
        $stmt->execute();

        echo "Document saved successfully! <a href='word_processor.php'>Go back</a>";
    } else {
        echo "Document cannot be empty! <a href='word_processor.php'>Go back</a>";
    }
} else {
    echo "Invalid request!";
}
?>
