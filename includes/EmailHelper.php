<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/phpmailer/src/Exception.php';
require_once __DIR__ . '/../vendor/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/src/SMTP.php';

class EmailHelper {
    private $config;
    private $mailer;

    public function __construct() {
        $this->config = require_once __DIR__ . '/email_config.php';
        $this->mailer = new PHPMailer(true);

        try {
            // Server settings
            $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
            $this->mailer->isSMTP();
            $this->mailer->Host = $this->config['smtp_host'];
            $this->mailer->SMTPAuth = true;
            $this->mailer->Username = $this->config['smtp_username'];
            $this->mailer->Password = $this->config['smtp_password'];
            $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mailer->Port = $this->config['smtp_port'];
            
            // Set default sender
            $this->mailer->setFrom($this->config['smtp_username'], $this->config['from_name']);
        } catch (Exception $e) {
            error_log("Error initializing PHPMailer: " . $e->getMessage());
        }
    }

    public function sendEmail($to, $subject, $message) {
        try {
            // Reset recipients
            $this->mailer->clearAllRecipients();
            
            // Recipient
            $this->mailer->addAddress($to);
            
            // Content
            $this->mailer->isHTML(true);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $this->convertToHtml($message);
            $this->mailer->AltBody = strip_tags($message);

            error_log("Attempting to send email to: " . $to);
            error_log("Subject: " . $subject);
            
            // Send email
            $result = $this->mailer->send();
            error_log("Email sent successfully to: " . $to);
            return true;
        } catch (Exception $e) {
            error_log("Exception while sending email: " . $e->getMessage());
            error_log("Mailer Error: " . $this->mailer->ErrorInfo);
            return false;
        }
    }

    private function convertToHtml($plainText) {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Password Reset</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .container {
                    background-color: #f9f9f9;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    padding: 20px;
                    margin: 20px 0;
                }
                .button {
                    display: inline-block;
                    padding: 10px 20px;
                    background-color: #4CAF50;
                    color: white !important;
                    text-decoration: none;
                    border-radius: 5px;
                    margin: 20px 0;
                }
                .footer {
                    font-size: 12px;
                    color: #666;
                    margin-top: 20px;
                    padding-top: 20px;
                    border-top: 1px solid #ddd;
                }
            </style>
        </head>
        <body>
            <div class="container">';

        // Convert plain text to HTML paragraphs
        $paragraphs = explode("\n\n", $plainText);
        foreach ($paragraphs as $paragraph) {
            if (strpos($paragraph, 'http') !== false) {
                // If paragraph contains a URL, make it a button
                $html .= '<p style="text-align: center;"><a href="' . trim($paragraph) . '" class="button" style="background-color: #4CAF50; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; display: inline-block;">Reset Password</a></p>';
            } else {
                $html .= '<p>' . nl2br(trim($paragraph)) . '</p>';
            }
        }

        $html .= '
            </div>
            <div class="footer">
                This is an automated message, please do not reply.
            </div>
        </body>
        </html>';

        return $html;
    }
}
?> 