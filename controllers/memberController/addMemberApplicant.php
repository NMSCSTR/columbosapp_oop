<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplicationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id               = $_POST['user_id'];
    $frateral_counselor_id = $_POST['frateral_counselor_id'];
    $firstname             = $_POST['firstname'];
    $lastname              = $_POST['lastname'];
    $middlename            = $_POST['middlename'];
    $birthdate             = $_POST['birthdate'];
    $age                   = $_POST['age'];
    $gender                = $_POST['gender'];
    $marital_status        = $_POST['marital_status'];
    $tin_sss               = $_POST['tin_sss'];
    $nationality           = $_POST['nationality'];

    $model  = new MemberApplicationModel($conn);
    $result = $model->insertApplicant($user_id, $frateral_counselor_id, $firstname, $lastname, $middlename, $birthdate, $age, $gender, $marital_status, $tin_sss, $nationality);

    // insertApplicantContactDetailsController
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $street        = $_POST['street'] ?? '';
    $barangay      = $_POST['barangay'] ?? '';
    $city_province = $_POST['city_province'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $email_address = $_POST['email_address'] ?? '';

    if ($applicant_id) {
        $result = $this->model->insertApplicantContactDetails(
            $applicant_id, 
            $user_id, 
            $street, 
            $barangay, 
            $city_province, 
            $mobile_number, 
            $email_address
        );
    }

    // insertEmploymentDetailsController
    $occupation             = $_POST['occupation'] ?? '';
    $employment_status      = $_POST['employment_status'] ?? '';
    $duties                 = $_POST['duties'] ?? '';
    $employer               = $_POST['employer'] ?? '';
    $work                   = $_POST['work'] ?? '';
    $nature_business        = $_POST['nature_business'] ?? '';
    $employer_mobile_number = $_POST['employer_mobile_number'] ?? '';
    $employer_email_address = $_POST['employer_email_address'] ?? '';
    $monthly_income         = $_POST['monthly_income'] ?? '';

    if ($applicant_id && $user_id) {
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

    }

    // insertPlanInformationController
    $fraternal_benefits_id = $_POST['fraternal_benefits_id'] ?? '';
    $council_id            = $_POST['council_id'] ?? '';
    $payment_mode          = $_POST['payment_mode'] ?? '';
    $contribution_amount   = $_POST['contribution_amount'] ?? '';
    $currency              = $_POST['currency'] ?? '';

    if ($applicant_id && $user_id) {
        $result = $this->model->insertPlanInformation(
            $applicant_id,
            $user_id,
            $fraternal_benefits_id,
            $council_id,
            $payment_mode,
            $contribution_amount,
            $currency
        );

    }

    // 


}
