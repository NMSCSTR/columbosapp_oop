<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../includes/EmailHelper.php';

if (isset($_POST['submit'])) {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: ../../views/forgot-password.php");
        exit();
    }

    // Check if email exists in database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "No account found with this email address";
        header("Location: ../../views/forgot-password.php");
        exit();
    }

    try {
        // Generate reset token
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Store token in database
        $stmt = $conn->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $email, $token, $expires);
        
        if ($stmt->execute()) {
            // Send reset email using EmailHelper
            $emailHelper = new EmailHelper();
            $reset_link = BASE_URL . "views/reset-password.php?token=" . $token;
            
            $subject = "Password Reset Request";
            $message = "Hello,\n\n";
            $message .= "You have requested to reset your password. Click the button below to reset your password:\n\n";
            $message .= $reset_link . "\n\n";
            $message .= "This link will expire in 1 hour.\n\n";
            $message .= "If you did not request this password reset, please ignore this email.\n\n";
            $message .= "Best regards,\nColumbos App Team";

            error_log("Attempting to send password reset email to: " . $email);
            error_log("Reset link generated: " . $reset_link);

            $emailSent = $emailHelper->sendEmail($email, $subject, $message);
            
            if ($emailSent) {
                error_log("Password reset email sent successfully to: " . $email);
                $_SESSION['success'] = "Password reset instructions have been sent to your email";
                header("Location: ../../views/login.php");
                exit();
            } else {
                error_log("Failed to send password reset email to: " . $email);
                // If email fails, delete the token from database
                $stmt = $conn->prepare("DELETE FROM password_resets WHERE token = ?");
                $stmt->bind_param("s", $token);
                $stmt->execute();
                
                $_SESSION['error'] = "Failed to send reset email. Please check your email settings or try again later";
                header("Location: ../../views/forgot-password.php");
                exit();
            }
        } else {
            error_log("Failed to store reset token in database for email: " . $email);
            $_SESSION['error'] = "Something went wrong. Please try again later";
            header("Location: ../../views/forgot-password.php");
            exit();
        }
    } catch (Exception $e) {
        error_log("Exception occurred during password reset process: " . $e->getMessage());
        $_SESSION['error'] = "An unexpected error occurred. Please try again later";
        header("Location: ../../views/forgot-password.php");
        exit();
    }
} else {
    header("Location: ../../views/login.php");
    exit();
}
?> 