<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/TransactionModel.php';

$transactionModel = new TransactionModel($conn);

if (isset($_POST['submit_transaction'])) {
    $applicant_id = $_POST['applicant_id'];
    $user_id = $_POST['user_id'];
    $plan_id = $_POST['plan_id'];
    $payment_date = $_POST['payment_date'];
    $amount_paid = $_POST['amount_paid'];
    $currency = $_POST['currency'];
    $payment_timing_status = $_POST['payment_timing_status'];

    // Compute next due date (1 month later)
    $next_due_date = date('Y-m-d', strtotime("+1 month", strtotime($payment_date)));

    $inserted = $transactionModel->insertTransactions($applicant_id, $user_id, $plan_id, $payment_date, $amount_paid, $currency, $next_due_date, $payment_timing_status);

    if ($inserted) {
        $_SESSION['success'] = 'Transaction recorded successfully.';
    } else {
        $_SESSION['error'] = 'Failed to save transaction.';
    }

    header('Location: ' . BASE_URL . 'views/admin/view_applicants.php');
    exit();
}


