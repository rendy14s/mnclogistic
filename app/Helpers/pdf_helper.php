<?php
use Dompdf\Dompdf;

function generate_pdf($html, $filename = 'invoice.pdf', $stream = true)
{
    // Clear output buffer just in case
    ob_clean();
    flush();

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    if ($stream) {
        // Send PDF to browser
        $dompdf->stream($filename, ['Attachment' => 0]);
        exit(); // Ensure nothing else is sent
    } else {
        return $dompdf->output();
    }
}