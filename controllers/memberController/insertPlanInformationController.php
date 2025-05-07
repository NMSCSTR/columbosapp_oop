<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $user_id       = $_POST['user_id'] ?? '';
    $fraternal_benefits_id = $_POST['fraternal_benefits_id'] ?? '';
    $council_id            = $_POST['council_id'] ?? '';
    $payment_mode          = $_POST['payment_mode'] ?? '';
    $contribution_amount   = $_POST['contribution_amount'] ?? '';
    $currency              = $_POST['currency'] ?? '';

    $model  = new MemberApplicationModel($conn);
    $result = $this->model->insertPlanInformation(
        $applicant_id,
        $user_id,
        $fraternal_benefits_id,
        $council_id,
        $payment_mode,
        $contribution_amount,
        $currency
    );


    if ($result) {
        $_SESSION['success'] = 'Plan details inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
