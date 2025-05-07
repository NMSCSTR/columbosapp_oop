<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id       = $_POST['applicant_id'] ?? null;
    $user_id            = $_POST['user_id'] ?? '';
    $past_illness       = $_POST['past_illness'] ?? '';
    $current_medication = $_POST['current_medication'] ?? '';

    $model  = new MemberApplicationModel($conn);
    $result = $this->$model->insertMedicalHistory(
        $applicant_id,
        $user_id,
        $past_illness,
        $current_medication
    );

    if ($result) {
        $_SESSION['success'] = 'Medical history inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
