<?php
session_start();
include '../../includes/db.php';
include '../../models/usersModel.php';
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $userModel = new UserModel($conn);
    $user = $userModel->getUserByEmailIgnoreStatus($email); 
    if ($user) {
        if ($user['status'] !== 'approved') {
            $_SESSION['error'] = "Your account is not yet approved.";
        } elseif (!password_verify($password, $user['password'])) {
            $_SESSION['error'] = "Incorrect password.";
        } else {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['email'] = $user['email'];

            header('Location: ' . BASE_URL . 'middleware/role_redirect.php');
            exit;
        }
    } else {
        $_SESSION['error'] = "Email not found.";
    }

    header('Location: ' . BASE_URL . 'views/login.php');
    exit;
} else {
    $_SESSION['error'] = "Invalid request.";
    header('Location: ' . BASE_URL . 'views/login.php');
    exit;
}
?>
