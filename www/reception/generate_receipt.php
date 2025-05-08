<?php
require 'db.php';
require 'vendor/autoload.php'; // Make sure DOMPDF is installed via Composer

use Dompdf\Dompdf;
use Dompdf\Options;

// Check if receipt_id is provided
if (!isset($_GET['receipt_id']) || empty($_GET['receipt_id'])) {
    die("Error: No valid receipt ID provided.");
}

$receipt_id = $_GET['receipt_id'];
echo "Debug: Receipt ID = " . htmlspecialchars($receipt_id) . "<br>"; // Debugging line

// Fetch payment details from database
$query = $database->prepare("SELECT * FROM payments WHERE id = ?");
$query->bindParam(1, $receipt_id, SQLITE3_INTEGER);
$result = $query->execute();

if (!$result) {
    die("Error: Query execution failed.");
}

$payment = $result->fetchArray(SQLITE3_ASSOC);

if (!$payment) {
    die("Error: No matching payment record found for ID " . htmlspecialchars($receipt_id));
}

echo "Debug: Payment record found!<br>"; // Debugging line
print_r($payment); // Debugging line to check what is fetched

// DOMPDF settings
$options = new Options();
$options->set('defaultFont', 'Arial');
$dompdf = new Dompdf($options);

// HTML content for the receipt
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        .container { width: 90%; margin: auto; padding: 20px; border: 1px solid #ddd; }
        h2 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .school-name { font-size: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="school-name">Center for Excellent Child, Madina - Accra</h2>
        <h3>Payment Receipt</h3>
        <table>
            <tr><th>Student Name</th><td>' . htmlspecialchars($payment['student_name']) . '</td></tr>
            <tr><th>Class</th><td>' . htmlspecialchars($payment['class']) . '</td></tr>
            <tr><th>Amount Paid</th><td>GHS ' . number_format($payment['amount_paid'], 2) . '</td></tr>
            <tr><th>Date Paid</th><td>' . htmlspecialchars($payment['date_paid']) . '</td></tr>
            <tr><th>Balance</th><td>GHS ' . number_format($payment['balance'], 2) . '</td></tr>
            <tr><th>Last Term Arrears</th><td>GHS ' . number_format($payment['last_term_arrears'], 2) . '</td></tr>
        </table>
        <p>Thank you for your payment!</p>
    </div>
</body>
</html>';

// Load HTML into DOMPDF
$dompdf->loadHtml($html);

// Set paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the PDF
$dompdf->render();

// Output the generated PDF
$dompdf->stream("receipt_" . $receipt_id . ".pdf", ["Attachment" => false]);
exit;
?>
