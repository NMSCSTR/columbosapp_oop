<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $user_id       = $_POST['user_id'] ?? '';
    $occupation             = $_POST['occupation'] ?? '';
    $employment_status      = $_POST['employment_status'] ?? '';
    $duties                 = $_POST['duties'] ?? '';
    $employer               = $_POST['employer'] ?? '';
    $work                   = $_POST['work'] ?? '';
    $nature_business        = $_POST['nature_business'] ?? '';
    $employer_mobile_number = $_POST['employer_mobile_number'] ?? '';
    $employer_email_address = $_POST['employer_email_address'] ?? '';
    $monthly_income         = $_POST['monthly_income'] ?? '';

    $model  = new MemberApplicationModel($conn);
    $result = $this->model->insertEmploymentDetails(
        $applicant_id,
        $user_id,
        $occupation,
        $employment_status,
        $duties,
        $employer,
        $work,
        $nature_business,
        $employer_mobile_number,
        $employer_email_address,
        $monthly_income
    );

    if ($result) {
        $_SESSION['success'] = 'Employment details inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
