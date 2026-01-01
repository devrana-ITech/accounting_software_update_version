<?php
$file = 'a1_pdf_dli.pdf';

if (file_exists($file)) {
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="' . basename($file) . '"');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
    echo "File not found.";
}
?>