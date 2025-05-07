<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id      = $_POST['applicant_id'] ?? null;
    $user_id           = $_POST['user_id'] ?? '';
    $height            = $_POST['height'] ?? '';
    $weight            = $_POST['weight'] ?? '';
    $pregnant_question = $_POST['pregnant_question'] ?? '';
    $council_id        = $_POST['council_id'] ?? '';
    $first_degree_date = $_POST['first_degree_date'] ?? '';
    $present_degree    = $_POST['present_degree'] ?? '';
    $good_standing     = $_POST['good_standing'] ?? '';

    if (isset($_FILES['signature_file']) && $_FILES['signature_file']['error'] === UPLOAD_ERR_OK) {
        $signature_tmp  = $_FILES['signature_file']['tmp_name'];
        $signature_name = basename($_FILES['signature_file']['name']);
    } else {
        $_SESSION['error'] = "Signature upload failed.";
        header("Location: personal_membership_form.php");
        exit();
    }

    $model  = new MemberApplicationModel($conn);
    $result = $this->model->insertPersonalAndMembershipDetails(
        $applicant_id,
        $user_id,
        $height,
        $weight,
        $signature_tmp,
        $signature_name,
        $pregnant_question,
        $council_id,
        $first_degree_date,
        $present_degree,
        $good_standing
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
