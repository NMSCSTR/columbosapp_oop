<?php
require_once 'includes/EmailHelper.php';

$emailHelper = new EmailHelper();
$to = "inesphilip72@gmail.com"; // Using the same email for testing
$subject = "Test Email";
$message = "Hello,\n\nThis is a test email to verify the password reset functionality is working.\n\nBest regards,\nTest System";

$result = $emailHelper->sendEmail($to, $subject, $message);

if ($result) {
    echo "Test email sent successfully!\n";
} else {
    echo "Failed to send test email. Please check the error logs.\n";
}
?> 