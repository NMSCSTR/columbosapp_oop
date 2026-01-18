<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/TransactionModel.php';

$transactionModel = new TransactionModel($conn);


if (isset($_POST['update_notebook'])) {
    $transaction_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);
    $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

    $updated = $transactionModel->updateNotebookRemarks($transaction_id, $remarks);

    if ($updated) {
        $_SESSION['success'] = 'Notebook entry updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update notebook entry.';
    }

    header('Location: ' . BASE_URL . 'views/admin/view_applicants.php');
    exit();
}


if (isset($_POST['submit_transaction'])) {
    $applicant_id          = mysqli_real_escape_string($conn, $_POST['applicant_id']);
    $user_id               = mysqli_real_escape_string($conn, $_POST['user_id']);
    $plan_id               = mysqli_real_escape_string($conn, $_POST['plan_id']);
    $payment_date          = $_POST['payment_date']; 
    $amount_paid           = $_POST['amount_paid'];
    $currency              = mysqli_real_escape_string($conn, $_POST['currency']);
    $payment_timing_status = mysqli_real_escape_string($conn, $_POST['payment_timing_status']);
    $remarks               = mysqli_real_escape_string($conn, $_POST['remarks']);

    
    $planDetails = $transactionModel->getPlanDetailsByApplicantId($applicant_id);
    
    $payment_mode = isset($planDetails['payment_mode']) ? strtolower($planDetails['payment_mode']) : 'monthly';


    $base_timestamp = strtotime($payment_date);

    switch ($payment_mode) {
        case 'monthly':
            $next_due_date = date('Y-m-d', strtotime("+1 month", $base_timestamp));
            break;

        case 'quarterly':
            $next_due_date = date('Y-m-d', strtotime("+3 months", $base_timestamp));
            break;

        case 'semi-annually':
        case 'semi annually':
        case 'semi_annually':
            $next_due_date = date('Y-m-d', strtotime("+6 months", $base_timestamp));
            break;

        case 'annually':
        case 'yearly':
        case 'annual':
            $next_due_date = date('Y-m-d', strtotime("+1 year", $base_timestamp));
            break;

        default:

            $next_due_date = date('Y-m-d', strtotime("+1 month", $base_timestamp));
            break;
    }


    $inserted = $transactionModel->insertTransactions(
        $applicant_id,
        $user_id,
        $plan_id,
        $payment_date,
        $amount_paid,
        $currency,
        $next_due_date,
        $payment_timing_status,
        $remarks
    );


    if ($inserted) {
        $_SESSION['success'] = "Transaction recorded. Next payment due on: " . date('M d, Y', strtotime($next_due_date));
    } else {
        $_SESSION['error'] = 'Critical Error: Could not save transaction to the ledger.';
    }

    header('Location: ' . BASE_URL . 'views/admin/view_applicants.php');
    exit();
}


header('Location: ' . BASE_URL . 'views/admin/view_applicants.php');
exit();