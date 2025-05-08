<?php
include 'config.php'; // Include the config file

// Get the JSON input
$data = json_decode(file_get_contents('php://input'), true);
$marksid = $data['id']; // Use 'marksid' instead of 'id'

if (empty($marksid)) {
    echo json_encode(['success' => false, 'error' => 'No marksid provided']);
    exit;
}

try {
    // Create a new PDO instance for SQLite
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Prepare and execute the delete statement
    $stmt = $pdo->prepare("DELETE FROM marks WHERE marksid = :marksid"); // Use 'marksid' here
    $stmt->bindParam(':marksid', $marksid, PDO::PARAM_INT);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete the record']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>