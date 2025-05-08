<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer's autoloader if using Composer

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messages = $_POST['messages']; // Get the messages array from the form submission

    $mail = new PHPMailer(true); // Create a new PHPMailer instance

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dadzieernestbizz@gmail.com'; // Your actual Gmail address
        $mail->Password = 'Ernestbizz..123'; // Your actual Gmail password or App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Enable debugging
        $mail->SMTPDebug = 2; // Set to 0 for no debug output, 1 for client messages, 2 for client and server messages

        // Set the sender
        $mail->setFrom('dadzieernestbizz@gmail.com', 'Your School Name');

        foreach ($messages as $msg) {
            // Set recipient
            $mail->addAddress($msg['email']); // Add a recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = $msg['subject'];
            $mail->Body    = $msg['message'];

            // Send the email
            if ($mail->send()) {
                echo "Email sent to: " . $msg['email'] . "<br>";
            } else {
                echo "Failed to send email to: " . $msg['email'] . ". Error: " . $mail->ErrorInfo . "<br>";
            }

            $mail->clearAddresses(); // Clear all addresses for the next iteration
        }

        echo json_encode(['success' => true, 'message' => 'Notifications sent successfully!']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo]);
    }
}
?>