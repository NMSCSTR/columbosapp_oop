<?php
require_once '../../middleware/auth.php';
authorize(['admin']);
include '../../includes/db.php';
include '../../models/memberModel/memberApplicationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicationModel = new MemberApplicationModel($conn);
    
    try {
        // Sanitize and prepare data
        $data = [
            'firstname' => filter_input(INPUT_POST, 'firstname', FILTER_SANITIZE_STRING),
            'middlename' => filter_input(INPUT_POST, 'middlename', FILTER_SANITIZE_STRING),
            'lastname' => filter_input(INPUT_POST, 'lastname', FILTER_SANITIZE_STRING),
            'birthdate' => $_POST['birthdate'],
            'gender' => filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_STRING),
            'marital_status' => filter_input(INPUT_POST, 'marital_status', FILTER_SANITIZE_STRING),
            'email_address' => filter_input(INPUT_POST, 'email_address', FILTER_SANITIZE_EMAIL),
            'mobile_number' => filter_input(INPUT_POST, 'mobile_number', FILTER_SANITIZE_STRING),
            'street' => filter_input(INPUT_POST, 'street', FILTER_SANITIZE_STRING),
            'barangay' => filter_input(INPUT_POST, 'barangay', FILTER_SANITIZE_STRING),
            'city_province' => filter_input(INPUT_POST, 'city_province', FILTER_SANITIZE_STRING),
            'occupation' => filter_input(INPUT_POST, 'occupation', FILTER_SANITIZE_STRING),
            'employer' => filter_input(INPUT_POST, 'employer', FILTER_SANITIZE_STRING),
            'monthly_income' => filter_input(INPUT_POST, 'monthly_income', FILTER_SANITIZE_NUMBER_FLOAT),
            'employment_status' => filter_input(INPUT_POST, 'employment_status', FILTER_SANITIZE_STRING),
            'plan_type' => filter_input(INPUT_POST, 'plan_type', FILTER_SANITIZE_STRING),
            'council_id' => filter_input(INPUT_POST, 'council_id', FILTER_SANITIZE_NUMBER_INT),
            'payment_mode' => filter_input(INPUT_POST, 'payment_mode', FILTER_SANITIZE_STRING),
            'face_amount' => filter_input(INPUT_POST, 'face_amount', FILTER_SANITIZE_NUMBER_FLOAT),
            'benefit_name' => filter_input(INPUT_POST, 'benefit_name', FILTER_SANITIZE_STRING),
            'benefit_relationship' => filter_input(INPUT_POST, 'benefit_relationship', FILTER_SANITIZE_STRING),
            'benefit_birthdate' => $_POST['benefit_birthdate'],
            'application_status' => 'Pending'
        ];

        // Calculate age
        $birthdate = new DateTime($data['birthdate']);
        $today = new DateTime();
        $age = $today->diff($birthdate)->y;
        $data['age'] = $age;

        // Validate required fields
        foreach ($data as $key => $value) {
            if ($value === '' || $value === null) {
                throw new Exception("Required field '$key' is missing");
            }
        }

        // Validate age
        if ($age < 18) {
            throw new Exception("Applicant must be at least 18 years old");
        }

        // Create the application
        $result = $applicationModel->createApplication($data);

        if ($result) {
            $_SESSION['success'] = "Application created successfully";
            header("Location: ../admin/applications.php");
            exit();
        } else {
            throw new Exception("Failed to create application");
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header("Location: ../admin/new-application.php");
        exit();
    }
} else {
    header("Location: ../admin/new-application.php");
    exit();
}
?> 