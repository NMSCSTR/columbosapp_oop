<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplicationModel.php';

$memberApplicationModel = new MemberApplicationModel($conn);

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id     = $_GET['id'];
    $action = $_GET['action'];

    if (empty($id) || ! is_numeric($id)) {
        $_SESSION['error'] = 'Invalid user ID.';
        header('Location: ' . BASE_URL . 'views/fraternal-counselor/fraternalcounselor.php');
        exit();
    }

    if ($action === 'approve') {
        $status = 'Approved';
    }else if ($action === 'not_approve'){
        $status = 'Dis-approved';
    } else {
        $_SESSION['error'] = 'Invalid action.';
        header('Location: ' . BASE_URL . 'views/fraternal-counselor/fraternalcounselor.php');
        exit();
    }

    if ($memberApplicationModel->changedApplicationStatus($id, $status)) {
        $_SESSION['success'] = "Applicatiobn status updated to '{$status}' successfully.";
    } else {
        $_SESSION['error'] = 'Failed to update user status. Please try again.';
    }

    header('Location: ' . BASE_URL . 'views/fraternal-counselor/fraternalcounselor.php');
    exit();
} else {

    $_SESSION['error'] = 'Missing ID or action parameter.';
    header('Location: ' . BASE_URL . 'views/fraternal-counselor/fraternalcounselor.php?message=noid');
    exit();
}
