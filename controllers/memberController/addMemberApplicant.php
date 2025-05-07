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
    $birthplace            = $_POST['birthplace'];
    $age                   = $_POST['age'];
    $gender                = $_POST['gender'];
    $marital_status        = $_POST['marital_status'];
    $tin_sss               = $_POST['tin_sss'];
    $nationality           = $_POST['nationality'];

    // insertApplicantContactDetailsController
    $applicant_id  = $_POST['applicant_id'] ?? null;
    $street        = $_POST['street'] ?? '';
    $barangay      = $_POST['barangay'] ?? '';
    $city_province = $_POST['city_province'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $email_address = $_POST['email_address'] ?? '';

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

    // insertPlanInformationController
    $fraternal_benefits_id = $_POST['fraternal_benefits_id'] ?? '';
    $council_id            = $_POST['council_id'] ?? '';
    $payment_mode          = $_POST['payment_mode'] ?? '';
    $contribution_amount   = $_POST['contribution_amount'] ?? '';
    $currency              = $_POST['currency'] ?? '';

    // addBeneficiariesController
    $benefit_types         = $_POST['benefit_type'] ?? [];
    $benefit_names         = $_POST['benefit_name'] ?? [];
    $benefit_birthdates    = $_POST['benefit_birthdate'] ?? [];
    $benefit_relationships = $_POST['benefit_relationship'] ?? [];

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

    // insertMedicalHistoryController
    $past_illness       = $_POST['past_illness'] ?? '';
    $current_medication = $_POST['current_medication'] ?? '';

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

    // insertPhysicianDetailsController
    $physician_name = $_POST['physician_name'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $clinic_address = $_POST['clinic_address'] ?? '';

    // insertPersonalAndMembershipDetailsController
    $height            = $_POST['height'] ?? '';
    $weight            = $_POST['weight'] ?? '';
    $pregnant_question = $_POST['pregnant_question'] ?? '';
    $first_degree_date = $_POST['first_degree_date'] ?? '';
    $present_degree    = $_POST['present_degree'] ?? '';
    $good_standing     = $_POST['good_standing'] ?? '';

    $model  = new MemberApplicationModel($conn);
    $result = $model->insertApplicant($user_id,$frateral_counselor_id,$lastname,$firstname,$middlename,$birthdate,$birthplace,$age,$gender,$marital_status,$tin_sss,$nationality);

    $result1 = $model->insertApplicantContactDetails($applicant_id,$user_id,$street,$barangay,$city_province,$mobile_number,$email_address);

    $result2 = $model->insertEmploymentDetails( $applicant_id, $user_id, $occupation, $employment_status, $duties, $employer, $work, $nature_business, $employer_mobile_number, $employer_email_address, $monthly_income);
    $result3 = $model->insertPlanInformation( $applicant_id, $user_id, $fraternal_benefits_id, $council_id, $payment_mode, $contribution_amount, $currency);

    $result4 = $model->addBeneficiaries( $applicant_id, $user_id, $benefit_types, $benefit_names, $benefit_birthdates,$benefit_relationships);

    $result5 = $model->insertFamilyBackground( $applicant_id, $user_id, $father_lastname, $father_firstname, $father_mi, $mother_lastname, $mother_firstname, $mother_mi, $siblings_living, $siblings_deceased, $children_living, $children_decease);

    $result6 = $model->insertMedicalHistory( $applicant_id, $user_id, $past_illness, $current_medication);

    $result7 = $model->insertFamilyHealth( $applicant_id, $user_id, $father_living_age, $father_health, $mother_living_age, $mother_health, $siblings_living_age, $siblings_health, $children_living_age, $children_health, $father_death_age, $father_cause, $mother_death_age, $mother_cause, $siblings_death_age, $siblings_cause, $children_death_age, $children_cause);

    $result8 = $model->insertPhysicianDetails( $applicant_id, $user_id, $physician_name, $contact_number, $clinic_address);

    // insertHealthQuestionsController
    $responses = [];
    for ($i = 1; $i <= 12; $i++) {
        $question_code = "q" . $i;
        if ($i === 10) {
            foreach (['a', 'b'] as $suffix) {
                $code             = "q10$suffix";
                $responses[$code] = [
                    'response' => $_POST["{$code}_response"] ?? 'No',
                    'details'  => $_POST["{$code}_details"] ?? '',
                ];
            }
            continue;
        }

        $responses[$question_code] = [
            'response' => $_POST["{$question_code}_response"] ?? 'No',
            'details'  => $_POST["{$question_code}_details"] ?? '',
        ];
    }
    $result9 = $model->insertHealthQuestions($applicant_id, $user_id, $responses);

    if (isset($_FILES['signature_file']) && $_FILES['signature_file']['error'] === UPLOAD_ERR_OK) {
        $signature_tmp  = $_FILES['signature_file']['tmp_name'];
        $signature_name = basename($_FILES['signature_file']['name']);
    } else {
        $_SESSION['error'] = "Signature upload failed.";
        header("Location: personal_membership_form.php");
        exit();
    }

    $result10 = $model->insertPersonalAndMembershipDetails( $applicant_id, $user_id, $height, $weight, $signature_tmp, $signature_name, $pregnant_question, $council_id, $first_degree_date, $present_degree, $good_standing);

}
