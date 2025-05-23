<?php
require __DIR__ . '/../../vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;

if (!isset($_GET['path'])) {
    die('Missing file path.');
}

$relativePath = urldecode($_GET['path']);
$filePath = realpath(__DIR__ . '/../../' . $relativePath);

if (!$filePath || !file_exists($filePath)) {
    die('File not found.');
}

$phpWord = IOFactory::load($filePath);
$htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

ob_start();
$htmlWriter->save('php://output');
$content = ob_get_clean();
echo $content;
?>
