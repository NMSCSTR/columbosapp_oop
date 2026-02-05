<?php
session_start();
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../models/adminModel/TransactionModel.php';

if (isset($_POST['approve_payment'])) {
    $transactionModel = new TransactionModel($conn);
    
    $txn_id = mysqli_real_escape_string($conn, $_POST['transaction_id']);
    $plan_id = mysqli_real_escape_string($conn, $_POST['plan_id']);
    $applicant_id = mysqli_real_escape_string($conn, $_POST['applicant_id']);

    // 1. Calculate the new Next Due Date
    // This logic should exist in your TransactionModel. 
    // It looks at the current payment and adds 1 month (Monthly) or 12 months (Annual)
    $next_due_date = $transactionModel->calculateNextDueDate($applicant_id, $plan_id);

    // 2. Update the transaction status and set the next due date
    $sql = "UPDATE transactions SET 
            status = 'Paid', 
            next_due_date = '$next_due_date' 
            WHERE transaction_id = '$txn_id'";

    if (mysqli_query($conn, $sql)) {
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Payment verified and ledger updated!'];
    } else {
        $_SESSION['alert'] = ['type' => 'error', 'message' => 'Failed to update ledger.'];
    }

    header("Location: ../../views/adminView/view_applicants.php");
    exit;
}