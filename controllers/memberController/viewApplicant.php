<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplicationModel.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $model = new MemberApplicationModel($conn); // rename to your actual model class
    $applicantData = $model->fetchAllApplicantById($user_id);

    echo json_encode($applicantData);
} else {
    echo json_encode(['error' => 'No applicant ID provided.']);
}
?>