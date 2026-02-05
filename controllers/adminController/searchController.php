<?php
session_start();
unset($_SESSION['search_results']);
unset($_SESSION['selected_plan_id']);

include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplicationModel.php';

if (isset($_GET['plan_id']) && isset($_GET['user_id'])) {
    $plan_id = $_GET['plan_id'];
    $user_id = $_GET['user_id'];
    $applicant_id = $_GET['applicant_id'] ?? null; 
    $memberApplicationModel = new MemberApplicationModel($conn);
    
    $details = $memberApplicationModel->fetchAllApplicantsByIdV3($user_id, $plan_id);

    if ($details && is_array($details)) {
        $_SESSION['search_results'] = [$details];
        $_SESSION['user_id'] = $user_id;
        $_SESSION['selected_plan_id'] = $plan_id;

        header('Location: ' . BASE_URL . 'views/admin/view_applicants.php');
        exit();
    } else {
        $_SESSION['error'] = "Plan details not found.";
        header('Location: ' . BASE_URL . 'views/admin/transactions.php');
        exit();
    }
} else {
    header('Location: ' . BASE_URL . 'views/admin/transactions.php');
    exit();
}