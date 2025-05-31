<?php
// URL of PHPMailer zip file
$url = 'https://github.com/PHPMailer/PHPMailer/archive/refs/tags/v6.8.1.zip';
$zipFile = 'phpmailer.zip';
$extractPath = 'vendor/';

// Create vendor directory if it doesn't exist
if (!file_exists($extractPath)) {
    mkdir($extractPath, 0777, true);
}

// Download the file
if (file_put_contents($zipFile, file_get_contents($url))) {
    $zip = new ZipArchive;
    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
        
        // Rename the extracted directory
        rename($extractPath . 'PHPMailer-6.8.1', $extractPath . 'phpmailer');
        
        // Delete the zip file
        unlink($zipFile);
        
        echo "PHPMailer has been downloaded and extracted successfully.\n";
    } else {
        echo "Failed to extract the zip file.\n";
    }
} else {
    echo "Failed to download PHPMailer.\n";
}
?> 