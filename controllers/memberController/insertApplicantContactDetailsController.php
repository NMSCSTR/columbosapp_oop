<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $user_id       = $_POST['user_id'] ?? '';
    $street        = $_POST['street'] ?? '';
    $barangay      = $_POST['barangay'] ?? '';
    $city_province = $_POST['city_province'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $email_address = $_POST['email_address'] ?? '';

    $model  = new MemberApplicationModel($conn);
    $result = $this->model->insertApplicantContactDetails(
        $applicant_id,
        $user_id,
        $street,
        $barangay,
        $city_province,
        $mobile_number,
        $email_address
    );

    if ($result) {
        $_SESSION['success'] = 'Contact details inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
