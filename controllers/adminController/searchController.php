<!-- searchController.php -->
<?php
session_start();
unset($_SESSION['search_results']);
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/TransactionModel.php';
include '../../models/memberModel/memberApplicationModel.php';

if (isset($_GET['q'])) {
    $keyword          = $_GET['q'];
    $transactionModel = new TransactionModel($conn);
    $results          = $transactionModel->fetchApplicantsByKeyword($keyword);

    if (! empty($results)) {
        $fullDetails            = [];
        $memberApplicationModel = new MemberApplicationModel($conn);

        foreach ($results as $applicant) {
            $userId  = $applicant['user_id'];
            $details = $memberApplicationModel->fetchAllApplicantsByIdV2($userId);

            $fullDetails[] = [
                'basicInfo'   => $applicant,
                'fullDetails' => $details,
            ];
            $_SESSION['user_id']        = $applicant['user_id'];
        }

        
        $_SESSION['search_results'] = $fullDetails;

        header('Location: ' . BASE_URL . 'views/admin/view_applicants.php');
        exit();
    } else {
        $_SESSION['error'] = 'No matching applicants found.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    $_SESSION['error'] = 'Search keyword missing.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
