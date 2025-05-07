<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id       = $_POST['applicant_id'] ?? null;
    $user_id            = $_POST['user_id'] ?? '';
    $physician_name = $_POST['physician_name'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $clinic_address = $_POST['clinic_address'] ?? '';

    $model  = new MemberApplicationModel($conn);
    $result = $this->model->insertPhysicianDetails(
        $applicant_id,
        $user_id,
        $physician_name,
        $contact_number,
        $clinic_address
    );

    if ($result) {
        $_SESSION['success'] = 'Physician details inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
