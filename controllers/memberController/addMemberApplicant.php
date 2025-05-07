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
    $result = $model->insertApplicant(
        $user_id,
        $frateral_counselor_id,
        $firstname,
        $lastname,
        $middlename,
        $birthdate,
        $age,
        $gender,
        $marital_status,
        $tin_sss,
        $nationality
    );

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

    // addBeneficiariesController
    $benefit_types         = $_POST['benefit_type'] ?? [];
    $benefit_names         = $_POST['benefit_name'] ?? [];
    $benefit_birthdates    = $_POST['benefit_birthdate'] ?? [];
    $benefit_relationships = $_POST['benefit_relationship'] ?? [];

    if ($applicant_id && $user_id) {
        $result = $this->model->addBeneficiaries(
            $applicant_id,
            $user_id,
            $benefit_types,
            $benefit_names,
            $benefit_birthdates,
            $benefit_relationships
        );
    }

    // insertFamilyBackgroundController
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

    $result = $this->familyBackgroundModel->insertFamilyBackground(
        $applicant_id, $user_id, $father_lastname, $father_firstname, $father_mi,
        $mother_lastname, $mother_firstname, $mother_mi, $siblings_living, $siblings_deceased,
        $children_living, $children_deceased
    );

    // insertMedicalHistoryController
    $past_illness       = $_POST['past_illness'] ?? '';
    $current_medication = $_POST['current_medication'] ?? '';

    $result = $this->medicalHistoryModel->insertMedicalHistory(
        $applicant_id,
        $user_id,
        $past_illness,
        $current_medication
    );

    // insertFamilyHealthController
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

    $result = $this->familyHealthModel->insertFamilyHealth(
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

}
