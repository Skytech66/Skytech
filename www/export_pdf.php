<?php
require_once 'vendor/autoload.php'; // Adjust the path if necessary

use Dompdf\Dompdf;
use Dompdf\Options;

// Ensure the content is coming from POST
if (isset($_POST['content'])) {
    $content = $_POST['content'];

    // Create a new instance of DOMPDF
    $dompdf = new Dompdf();

    // Set up DOMPDF options if necessary (e.g., to enable certain features like font embedding)
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true); // Enable PHP if needed (e.g., for some custom code in HTML)
    $dompdf->setOptions($options);

    // Load the content to be converted into PDF
    $dompdf->loadHtml($content);

    // (Optional) Set paper size and orientation if needed
    $dompdf->setPaper('A4', 'portrait'); // You can adjust paper size and orientation here

    // Render PDF (first pass, with options for better performance)
    $dompdf->render();

    // Stream the generated PDF
    $dompdf->stream('school_fees_management.pdf', array('Attachment' => 0)); // 'Attachment' => 0 will open in the browser, '1' will download
}
?>
