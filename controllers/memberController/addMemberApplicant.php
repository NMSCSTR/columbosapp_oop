<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplicationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $frateral_counselor_id = $_POST['frateral_counselor_id'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $middlename = $_POST['middlename'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $marital_status = $_POST['marital_status'];
    $tin_sss = $_POST['tin_sss'];
    $nationality = $_POST['nationality'];

    $model = new MemberApplicationModel($conn);
    $result = $model->insertApplicant($user_id, $frateral_counselor_id,$firstname, $lastname,$middlename, $birthdate,$age, $gender,$marital_status, $tin_sss, $nationality);



    
}
?>
