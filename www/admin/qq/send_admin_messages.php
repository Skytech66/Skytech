<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $db = new SQLite3('school_fees_management.db');

    $sender = $_POST['sender_id'];
    $receiver = $_POST['receiver_id'];
    $message = $_POST['message'];

    $attachment = null;
    if (!empty($_FILES["attachment"]["name"])) {
        $attachment = time() . "_" . basename($_FILES["attachment"]["name"]);
        move_uploaded_file($_FILES["attachment"]["tmp_name"], "uploads/" . $attachment);
    }

    $query = $db->prepare("INSERT INTO messages (sender_id, receiver_id, message, attachment, status) VALUES (?, ?, ?, ?, 'unread')");
    $query->bindValue(1, $sender);
    $query->bindValue(2, $receiver);
    $query->bindValue(3, $message);
    $query->bindValue(4, $attachment);

    if ($query->execute()) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error"]);
    }

    $db->close();
}
?>