<?php
include 'config.php'; // Include the config file

try {
    // Create a new PDO instance for SQLite
    $pdo = new PDO("sqlite:" . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $class = $_GET['class'] ?? ''; // Use null coalescing operator to avoid undefined index
    $subject = $_GET['subject'] ?? '';

    // Check if subject is provided
    if (empty($subject)) {
        echo json_encode(['error' => 'Subject is required']);
        exit;
    }

    // Prepare the SQL query to fetch scores based on subject and class
    $sql = "SELECT student, admno, midterm, endterm, average, remarks, position 
            FROM marks 
            WHERE subject = :subject"; // Start with filtering by subject

    // Add class filtering if a class is provided
    if (!empty($class)) {
        $sql .= " AND class = :class"; // Use 'class' as the column name
    }

    // Add sorting by position
    $sql .= " ORDER BY position ASC"; // Sort by position in ascending order

    $stmt = $pdo->prepare($sql);
    $params = ['subject' => $subject];
    
    // Bind the class parameter if it is provided
    if (!empty($class)) {
        $params['class'] = $class;
    }

    $stmt->execute($params);

    $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the results as JSON
    header('Content-Type: application/json');
    echo json_encode($scores);
} catch (PDOException $e) {
    // Handle any errors
    echo json_encode(['error' => $e->getMessage()]);
}
?>