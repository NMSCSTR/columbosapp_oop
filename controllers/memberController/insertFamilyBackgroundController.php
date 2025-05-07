<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $user_id       = $_POST['user_id'] ?? '';
    $father_lastname   = $_POST['father_lastname'] ?? '';
    $father_firstname  = $_POST['father_firstname'] ?? '';
    $father_mi         = $_POST['father_mi'] ?? '';
    $mother_lastname   = $_POST['mother_lastname'] ?? '';
    $mother_firstname  = $_POST['mother_firstname'] ?? '';
    $mother_mi         = $_POST['mother_mi'] ?? '';
    $siblings_living   = $_POST['siblings_living'] ?? 0;
    $siblings_deceased = $_POST['siblings_deceased'] ?? 0;
    $children_living   = $_POST['children_living'] ?? 0;
    $children_deceased = $_POST['children_deceased'] ?? 0;

    $model  = new MemberApplicationModel($conn);
    $result = $this->$model->insertFamilyBackground(
        $applicant_id, $user_id, $father_lastname, $father_firstname, $father_mi,
        $mother_lastname, $mother_firstname, $mother_mi, $siblings_living, $siblings_deceased,
        $children_living, $children_deceased
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
