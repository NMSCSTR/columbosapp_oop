<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplicationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id               = intval($_POST['user_id']);
    $fraternal_counselor_id = intval($_POST['fraternal_counselor_id'] ?? 0);
    $firstname             = htmlspecialchars(trim($_POST['firstname']));
    $lastname              = htmlspecialchars(trim($_POST['lastname']));
    $middlename            = htmlspecialchars(trim($_POST['middlename']));
    $birthdate             = $_POST['birthdate'];
    $birthplace            = htmlspecialchars(trim($_POST['birthplace']));
    $age                   = intval($_POST['age']);
    $gender                = htmlspecialchars(trim($_POST['gender']));
    $marital_status        = htmlspecialchars(trim($_POST['marital_status']));
    $tin_sss               = htmlspecialchars(trim($_POST['tin_sss']));
    $nationality           = htmlspecialchars(trim($_POST['nationality']));

    // insertApplicantContactDetailsController
    // Contact Details
    $street        = htmlspecialchars(trim($_POST['street'] ?? ''));
    $barangay      = htmlspecialchars(trim($_POST['barangay'] ?? ''));
    $city_province = htmlspecialchars(trim($_POST['city_province'] ?? ''));
    $mobile_number = htmlspecialchars(trim($_POST['mobile_number'] ?? ''));
    $email_address = htmlspecialchars(trim($_POST['email_address'] ?? ''));

    // insertEmploymentDetailsController
    $occupation             = htmlspecialchars(trim($_POST['occupation'] ?? ''));
    $employment_status      = htmlspecialchars(trim($_POST['employment_status'] ?? ''));
    $duties                 = htmlspecialchars(trim($_POST['duties'] ?? ''));
    $employer               = htmlspecialchars(trim($_POST['employer'] ?? ''));
    $work                   = htmlspecialchars(trim($_POST['work'] ?? ''));
    $nature_business        = htmlspecialchars(trim($_POST['nature_business'] ?? ''));
    $employer_mobile_number = htmlspecialchars(trim($_POST['employer_mobile_number'] ?? ''));
    $employer_email_address = htmlspecialchars(trim($_POST['employer_email_address'] ?? ''));
    $monthly_income         = htmlspecialchars(trim($_POST['monthly_income'] ?? ''));

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
    $father_lastname   = htmlspecialchars(trim($_POST['father_lastname'] ?? ''));
    $father_firstname  = htmlspecialchars(trim($_POST['father_firstname'] ?? ''));
    $father_mi         = htmlspecialchars(trim($_POST['father_mi'] ?? ''));
    $mother_lastname   = htmlspecialchars(trim($_POST['mother_lastname'] ?? ''));
    $mother_firstname  = htmlspecialchars(trim($_POST['mother_firstname'] ?? ''));
    $mother_mi         = htmlspecialchars(trim($_POST['mother_mi'] ?? ''));
    $siblings_living   = intval($_POST['siblings_living'] ?? 0);
    $siblings_deceased = intval($_POST['siblings_deceased'] ?? 0);
    $children_living   = intval($_POST['children_living'] ?? 0);
    $children_deceased = intval($_POST['children_deceased'] ?? 0);

    // insertMedicalHistoryController
    $past_illness       = htmlspecialchars(trim($_POST['past_illness'] ?? ''));
    $current_medication = htmlspecialchars(trim($_POST['current_medication'] ?? ''));

    // insertFamilyHealthController
    $father_living_age   = htmlspecialchars(trim($_POST['father_living_age'] ?? ''));
    $father_health       = htmlspecialchars(trim($_POST['father_health'] ?? ''));
    $mother_living_age   = htmlspecialchars(trim($_POST['mother_living_age'] ?? ''));
    $mother_health       = htmlspecialchars(trim($_POST['mother_health'] ?? ''));
    $siblings_living_age = htmlspecialchars(trim($_POST['siblings_living_age'] ?? ''));
    $siblings_health     = htmlspecialchars(trim($_POST['siblings_health'] ?? ''));
    $children_living_age = htmlspecialchars(trim($_POST['children_living_age'] ?? ''));
    $children_health     = htmlspecialchars(trim($_POST['children_health'] ?? ''));
    $father_death_age    = htmlspecialchars(trim($_POST['father_death_age'] ?? ''));
    $father_cause        = htmlspecialchars(trim($_POST['father_cause'] ?? ''));
    $mother_death_age    = htmlspecialchars(trim($_POST['mother_death_age'] ?? ''));
    $mother_cause        = htmlspecialchars(trim($_POST['mother_cause'] ?? ''));
    $siblings_death_age  = htmlspecialchars(trim($_POST['siblings_death_age'] ?? ''));
    $siblings_cause      = htmlspecialchars(trim($_POST['siblings_cause'] ?? ''));
    $children_death_age  = htmlspecialchars(trim($_POST['children_death_age'] ?? ''));
    $children_cause      = htmlspecialchars(trim($_POST['children_cause'] ?? ''));

    // insertPhysicianDetailsController
    $physician_name = htmlspecialchars(trim($_POST['physician_name'] ?? ''));
    $contact_number = htmlspecialchars(trim($_POST['contact_number'] ?? ''));
    $clinic_address = htmlspecialchars(trim($_POST['clinic_address'] ?? ''));

    // insertPersonalAndMembershipDetailsController
    $height            = $_POST['height'] ?? '';
    $weight            = $_POST['weight'] ?? '';
    $pregnant_question = $_POST['pregnant_question'] ?? '';
    $first_degree_date = $_POST['first_degree_date'] ?? '';
    $present_degree    = $_POST['present_degree'] ?? '';
    $good_standing     = $_POST['good_standing'] ?? '';


    $model  = new MemberApplicationModel($conn);
    $applicant_id = $model->insertApplicant(
        $user_id, $fraternal_counselor_id, $firstname, $lastname, $middlename,
        $birthdate, $birthplace, $age, $gender, $marital_status, $tin_sss, $nationality
    );

    $result1 = $model->insertApplicantContactDetails($applicant_id, $user_id, $street, $barangay, $city_province, $mobile_number, $email_address);

    $result2 = $model->insertEmploymentDetails($applicant_id, $user_id, $occupation, $employment_status, $duties, $employer, $work, $nature_business, $employer_mobile_number, $employer_email_address, $monthly_income);
    $result3 = $model->insertPlanInformation($applicant_id, $user_id, $fraternal_benefits_id, $council_id, $payment_mode, $contribution_amount, $currency);

    $result4 = $model->addBeneficiaries($applicant_id, $user_id, $benefit_types, $benefit_names, $benefit_birthdates, $benefit_relationships);

    $result5 = $model->insertFamilyBackground($applicant_id, $user_id, $father_lastname, $father_firstname, $father_mi, $mother_lastname, $mother_firstname, $mother_mi, $siblings_living, $siblings_deceased, $children_living, $children_deceased);

    $result6 = $model->insertMedicalHistory($applicant_id, $user_id, $past_illness, $current_medication);

    $result7 = $model->insertFamilyHealth($applicant_id, $user_id, $father_living_age, $father_health, $mother_living_age, $mother_health, $siblings_living_age, $siblings_health, $children_living_age, $children_health, $father_death_age, $father_cause, $mother_death_age, $mother_cause, $siblings_death_age, $siblings_cause, $children_death_age, $children_cause);

    $result8 = $model->insertPhysicianDetails($applicant_id, $user_id, $physician_name, $contact_number, $clinic_address);

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

    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/app/uploads/signature/';
    $imagePath = ''; 


    if (isset($_FILES['signature_file']) && $_FILES['signature_file']['error'] === 0) {
        $cleanType = preg_replace('/[^A-Za-z0-9]/', '', $type);
        $imageFileType = strtolower(pathinfo($_FILES['signature_file']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {

            $imageName = $cleanType . 'Image_' . time() . '.' . $imageFileType;
            $targetPath = $uploadDir . $imageName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['signature_file']['tmp_name'], $targetPath)) {
                $signature_file = BASE_URL . 'uploads/signature/' . $imageName; 
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: " . BASE_URL . "member/member.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
            header("Location: " . BASE_URL . "member/member.php");
            exit;
        }
    }

    $result10 = $model->insertPersonalAndMembershipDetails($applicant_id, $user_id, $height, $weight, $signature_file, $pregnant_question, $council_id, $first_degree_date, $present_degree, $good_standing);

    if (! $applicant_id || ! $result2 || ! $result4 || ! $result5 || ! $result6 || ! $result7 || ! $result8 || ! $result9 || ! $result10) {
        $_SESSION['error'] = "There was an error saving the application. Please try again.";
        header("Location: " . BASE_URL . "views/member/member.php");
        exit();
    } else {
        $_SESSION['success'] = "Application submitted successfully.";
        header("Location: " . BASE_URL . "views/member/member.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();

}
