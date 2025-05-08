<?php
include "../include/functions.php"; // Include your functions file
$conn = db_conn(); // Establish the database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['images']) && isset($_POST['iduser'])) {
    $class = $_POST['class'];
    $imageFiles = $_FILES['images'];
    $userIds = $_POST['iduser'];

    // Loop through each uploaded file
    for ($i = 0; $i < count($imageFiles['name']); $i++) {
        if (!empty($imageFiles['name'][$i])) { // Check if a file was uploaded
            $userId = $userIds[$i];
            $fileName = basename($imageFiles['name'][$i]);
            $targetDir = "uploads/"; // Ensure this directory exists and is writable
            $targetFilePath = $targetDir . $fileName;

            // Check if the file is an image
            $check = getimagesize($imageFiles['tmp_name'][$i]);
            if ($check !== false) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($imageFiles['tmp_name'][$i], $targetFilePath)) {
                    // Update the database with the new image path
                    $updateQuery = "UPDATE student SET photo = :photo WHERE id = :id";
                    $stmt = $conn->prepare($updateQuery);
                    $stmt->bindValue(':photo', $targetFilePath, SQLITE3_TEXT);
                    $stmt->bindValue(':id', $userId, SQLITE3_INTEGER);
                    $stmt->execute();
                } else {
                    echo "Error uploading file for user ID: $userId<br>";
                }
            } else {
                echo "File is not a valid image for user ID: $userId<br>";
            }
        }
    }

    echo "All selected images uploaded successfully for class: " . htmlspecialchars($class);
} else {
    echo "No files uploaded or invalid request.";
}

$conn->close();
?>