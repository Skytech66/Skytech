<?php
header("Content-Type: application/json"); 
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'db.php'; // Include database connection

// Ensure it's a POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Validate input fields
$requiredFields = ['studentName', 'studentClass', 'studentAge', 'studentDOB', 'admissionNumber', 'parentName', 'contact', 'email', 'address', 'gender'];

foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        echo json_encode(["success" => false, "message" => "Please fill all required fields"]);
        exit;
    }
}

// Sanitize inputs
$studentName = htmlspecialchars($_POST["studentName"]);
$studentClass = htmlspecialchars($_POST["studentClass"]);
$studentAge = intval($_POST["studentAge"]);
$studentDOB = $_POST["studentDOB"];
$admissionNumber = htmlspecialchars($_POST["admissionNumber"]);
$parentName = htmlspecialchars($_POST["parentName"]);
$contact = htmlspecialchars($_POST["contact"]);
$email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
$address = htmlspecialchars($_POST["address"]);
$gender = htmlspecialchars($_POST["gender"]);

// Handle image upload
$imagePath = "uploads/default.jpg"; // Default image
if (!empty($_FILES["studentImage"]["name"])) {
    $targetDir = "uploads/";
    $imagePath = $targetDir . basename($_FILES["studentImage"]["name"]);

    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }

    if (!move_uploaded_file($_FILES["studentImage"]["tmp_name"], $imagePath)) {
        echo json_encode(["success" => false, "message" => "Image upload failed"]);
        exit;
    }
}

// Insert into database
try {
    $stmt = $pdo->prepare("INSERT INTO students (image, name, class, age, dob, admission_number, parent_name, contact, email, address, gender) 
                           VALUES (:image, :name, :class, :age, :dob, :admission_number, :parent_name, :contact, :email, :address, :gender)");

    $stmt->execute([
        ":image" => $imagePath,
        ":name" => $studentName,
        ":class" => $studentClass,
        ":age" => $studentAge,
        ":dob" => $studentDOB,
        ":admission_number" => $admissionNumber,
        ":parent_name" => $parentName,
        ":contact" => $contact,
        ":email" => $email,
        ":address" => $address,
        ":gender" => $gender
    ]);

    echo json_encode(["success" => true, "message" => "Student added successfully"]);
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
