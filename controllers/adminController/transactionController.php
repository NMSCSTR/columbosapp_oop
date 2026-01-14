<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/TransactionModel.php';
include '../../models/userModel.php';

$transactionModel = new TransactionModel($conn);

if (isset($_POST['submit_transaction'])) {
    $applicant_id = $_POST['applicant_id'];
    $user_id = $_POST['user_id'];
    $plan_id = $_POST['plan_id'];
    $payment_date = $_POST['payment_date'];
    $amount_paid = $_POST['amount_paid'];
    $currency = $_POST['currency'];
    $payment_timing_status = $_POST['payment_timing_status'];

    // Get plan details
    $planDetails = $transactionModel->getPlanDetailsByApplicantId($applicant_id);

    // Make payment_mode consistent (case-insensitive)
    $payment_mode = strtolower($planDetails['payment_mode']);

    // Compute next due date
    switch ($payment_mode) {
        case 'monthly':
            $next_due_date = date('Y-m-d', strtotime("+1 month", strtotime($payment_date)));
            break;

        case 'quarterly':
            $next_due_date = date('Y-m-d', strtotime("+3 months", strtotime($payment_date)));
            break;

        case 'semi-annually':
        case 'semi annually':
        case 'semi_annually':
            $next_due_date = date('Y-m-d', strtotime("+6 months", strtotime($payment_date)));
            break;

        case 'annually':
        case 'yearly':
        case 'annual':
            $next_due_date = date('Y-m-d', strtotime("+12 months", strtotime($payment_date)));
            break;

        default:
            $next_due_date = null;
    }

    // Insert transaction
    $inserted = $transactionModel->insertTransactions(
        $applicant_id,
        $user_id,
        $plan_id,
        $payment_date,
        $amount_paid,
        $currency,
        $next_due_date,
        $payment_timing_status
    );

    if ($inserted) {
        $_SESSION['success'] = 'Transaction recorded successfully.';
    } else {
        $_SESSION['error'] = 'Failed to save transaction.';
    }

    header('Location: ' . BASE_URL . 'views/admin/view_applicants.php');
    exit();
}
?>
