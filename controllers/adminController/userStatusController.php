<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/userModel.php';

$userModel = new UserModel($conn);

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id     = $_GET['id'];
    $action = $_GET['action'];

    if (empty($id) || ! is_numeric($id)) {
        $_SESSION['error'] = 'Invalid user ID.';
        header('Location: ' . BASE_URL . 'views/admin/users.php');
        exit();
    }

    if ($action === 'approve') {
        $status = 'approved';
    } elseif ($action === 'disable') {
        $status = 'disabled';
    } else {
        $_SESSION['error'] = 'Invalid action.';
        header('Location: ' . BASE_URL . 'views/admin/users.php');
        exit();
    }

    if ($userModel->updateUserStatus($id, $status)) {
        $_SESSION['success'] = "User status updated to '{$status}' successfully.";
    } else {
        $_SESSION['error'] = 'Failed to update user status. Please try again.';
    }

    header('Location: ' . BASE_URL . 'views/admin/users.php');
    exit();
} else {

    $_SESSION['error'] = 'Missing ID or action parameter.';
    header('Location: ' . BASE_URL . 'views/admin/users.php?message=noid');
    exit();
}
