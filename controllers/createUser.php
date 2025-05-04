<?php
session_start();
include '../includes/db.php';
include '../includes/config.php';
include '../models/usersModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname             = $_POST['firstname'] ?? '';
    $lastname              = $_POST['lastname'] ?? '';
    $email                 = $_POST['email'] ?? '';
    $kcfapicode            = $_POST['kcfapicode'] ?? '';
    $phone_number          = $_POST['phone_number'] ?? ''; 
    $password              = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';
    $role                  = $_POST['role'] ?? '';

    if (empty($firstname) || empty($lastname) || empty($email) || empty($kcfapicode) || empty($password) || empty($password_confirmation) || empty($role)) {
        $_SESSION['error'] = 'All fields are required.';
        header('Location: ' . BASE_URL . 'views/register.php');
        exit();
    }

    if ($password !== $password_confirmation) {
        $_SESSION['error'] = 'Passwords do not match.';
        header('Location: ' . BASE_URL . 'views/register.php');
        exit();
    }

    $userModel = new UserModel($conn);

    $existingUser = $userModel->getUserByEmail($email);

    if ($existingUser) {
        $_SESSION['error'] = 'Email is already registered.';
        header('Location: ' . BASE_URL . 'views/register.php');
        exit();
    }

    $result = $userModel->createUser($firstname, $lastname, $kcfapicode, $email, $phone_number, $password, $password_confirmation, $role);

    if ($result) {
        $_SESSION['success'] = 'Account created successfully. Please log in.';
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
    }

    header('Location: ' . BASE_URL . 'views/register.php');
    exit();
} else {
    $_SESSION['error'] = 'Invalid request.';
    header('Location: ' . BASE_URL . 'views/register.php');
    exit();
}
