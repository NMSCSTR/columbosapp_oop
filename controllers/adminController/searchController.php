<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/TransactionModel.php';
include '../../models/adminModel/memberApplicationModel.php';



if (isset($_GET['q'])) {
    $keyword = $_GET['q'];
    $transactionModel = new TransactionModel($conn);
    $results = $transactionModel->fetchApplicantsByKeyword($keyword);

    if (!empty($results)) {
        // Redirect to a results page or render data
        // For now, just display raw JSON (for testing)
        header('Content-Type: application/json');
        echo json_encode($results);
        foreach ($results as $applicant) {
            $memberApplicationModel = new MemberApplicationModel($conn);
            $getOtherDetails = $memberApplicationModel->fetchAllApplicantsByIdV2($applicant['user_id']);
        }
    } else {
        $_SESSION['error'] = 'No matching applicants found.';
         header('Location: ' . $_SERVER['HTTP_REFERER']);
    }
} else {
    $_SESSION['error'] = 'Search keyword missing.';
     header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>