<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/userModel.php';
include '../../models/adminModel/activityLogsModel.php';

$userModel = new UserModel($conn);
$logModel  = new activityLogsModel($conn);
$adminId   = $_GET['adminId'] ?? null;

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id     = $_GET['id'];
    $action = $_GET['action'];

    if (empty($id) || ! is_numeric($id)) {
        $_SESSION['error'] = 'Invalid user ID.';
        header('Location: ' . BASE_URL . 'views/admin/users.php');
        exit();
    }

    $newStatus  = '';
    $actionType = '';

    $oldStatus = $userModel->getUserStatus($id);

    if ($action === 'approve') {
        $newStatus    = 'approved';
        $actionType   = 'USER_APPROVED';
        $actionDetail = 'Approved user ID ' . $id;
    } elseif ($action === 'reject') {
        $newStatus    = 'rejected';
        $actionType   = 'USER_REJECTED';
        $actionDetail = 'Rejected user ID ' . $id;
    } else {
        $_SESSION['error'] = 'Invalid action.';
        header('Location: ' . BASE_URL . 'views/admin/users.php');
        exit();
    }

    if ($oldStatus && $oldStatus !== $newStatus) {
        if ($userModel->updateUserStatus($id, $newStatus)) {
            $logModel->logActivity(
                $adminId,
                $actionType,
                'users',
                $id,
                $actionDetail,
                $oldStatus,
                $newStatus
            );

            $_SESSION['success'] = "User status updated to '{$status}' successfully.";
        } else {
            $_SESSION['error'] = 'Failed to update user status. Please try again.';
        }
    } else {
        $_SESSION['error'] = "User status is already '{$newStatus}'. No update performed.";
    }

    header('Location: ' . BASE_URL . 'views/admin/users.php');
    exit();
} else {

    $_SESSION['error'] = 'Missing ID or action parameter.';
    header('Location: ' . BASE_URL . 'views/admin/users.php?message=noid');
    exit();
}
