<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/db.php';

if (isset($_POST['submit'])) {
    $token = $_POST['token'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate passwords match
    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: ../../views/reset-password.php?token=" . $token);
        exit();
    }

    // Validate password strength
    if (strlen($password) < 8) {
        $_SESSION['error'] = "Password must be at least 8 characters long";
        header("Location: ../../views/reset-password.php?token=" . $token);
        exit();
    }

    // Check if token exists and is valid
    $stmt = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expires_at > NOW() AND used = 0");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Invalid or expired reset token";
        header("Location: ../../views/login.php");
        exit();
    }

    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Hash new password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Update user's password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
    $stmt->bind_param("ss", $hashed_password, $email);
    
    if ($stmt->execute()) {
        // Mark token as used
        $stmt = $conn->prepare("UPDATE password_resets SET used = 1 WHERE token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();

        $_SESSION['success'] = "Password has been reset successfully. Please login with your new password";
        header("Location: ../../views/login.php");
        exit();
    } else {
        $_SESSION['error'] = "Failed to reset password. Please try again";
        header("Location: ../../views/reset-password.php?token=" . $token);
        exit();
    }
} else {
    header("Location: ../../views/login.php");
    exit();
}
?> 