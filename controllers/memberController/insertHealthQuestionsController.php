<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/memberModel/memberApplucationModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $applicant_id       = $_POST['applicant_id'] ?? null;
    $user_id            = $_POST['user_id'] ?? '';
    
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
    $result = $this->model->insertHealthQuestions($applicant_id, $user_id, $responses);


    if ($result) {
        $_SESSION['success'] = 'Physician details inserted successfully.';
        header("Location: " . BASE_URL . "views/member/member.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/member/member.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/member/member.php");
    exit();
}
