<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $user_id       = $_POST['user_id'] ?? '';
    $father_living_age   = $_POST['father_living_age'] ?? '';
    $father_health       = $_POST['father_health'] ?? '';
    $mother_living_age   = $_POST['mother_living_age'] ?? '';
    $mother_health       = $_POST['mother_health'] ?? '';
    $siblings_living_age = $_POST['siblings_living_age'] ?? '';
    $siblings_health     = $_POST['siblings_health'] ?? '';
    $children_living_age = $_POST['children_living_age'] ?? '';
    $children_health     = $_POST['children_health'] ?? '';
    $father_death_age    = $_POST['father_death_age'] ?? '';
    $father_cause        = $_POST['father_cause'] ?? '';
    $mother_death_age    = $_POST['mother_death_age'] ?? '';
    $mother_cause        = $_POST['mother_cause'] ?? '';
    $siblings_death_age  = $_POST['siblings_death_age'] ?? '';
    $siblings_cause      = $_POST['siblings_cause'] ?? '';
    $children_death_age  = $_POST['children_death_age'] ?? '';
    $children_cause      = $_POST['children_cause'] ?? '';

    $model  = new MemberApplicationModel($conn);
    $result = $this->$model->insertFamilyHealth(
        $applicant_id,
        $user_id,
        $father_living_age,
        $father_health,
        $mother_living_age,
        $mother_health,
        $siblings_living_age,
        $siblings_health,
        $children_living_age,
        $children_health,
        $father_death_age,
        $father_cause,
        $mother_death_age,
        $mother_cause,
        $siblings_death_age,
        $siblings_cause,
        $children_death_age,
        $children_cause
    );


    if ($result) {
        $_SESSION['success'] = 'Family health inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
