<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $user_id       = $_POST['user_id'] ?? '';
    $benefit_types         = $_POST['benefit_type'] ?? [];
    $benefit_names         = $_POST['benefit_name'] ?? [];
    $benefit_birthdates    = $_POST['benefit_birthdate'] ?? [];
    $benefit_relationships = $_POST['benefit_relationship'] ?? [];

    $model  = new MemberApplicationModel($conn);
    $result = $this->model->addBeneficiaries(
        $applicant_id,
        $user_id,
        $benefit_types,
        $benefit_names,
        $benefit_birthdates,
        $benefit_relationships
    );


    if ($result) {
        $_SESSION['success'] = 'Beneficiaries inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
