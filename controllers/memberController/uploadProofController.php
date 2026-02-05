<?php
session_start();
include '../../includes/db.php';

if (isset($_POST['submit_proof'])) {
    $plan_id = $_POST['plan_id'];
    $user_id = $_POST['user_id'];
    
    // File Upload Logic
    $target_dir = "../../uploads/payment_proofs/";
    if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
    
    $file_name = time() . "_" . basename($_FILES["payment_proof"]["name"]);
    $target_file = $target_dir . $file_name;
    
    if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
        // Path to save in DB
        $db_path = "uploads/payment_proofs/" . $file_name;

        // Create a 'Pending' transaction for the admin to approve
        $sql = "INSERT INTO transactions (applicant_id, user_id, plan_id, payment_date, amount_paid, status, payment_proof, remarks) 
                SELECT applicant_id, '$user_id', '$plan_id', CURDATE(), contribution_amount, 'Pending Verification', '$db_path', 'Online Payment Proof' 
                FROM plans WHERE plan_id = '$plan_id'";
        
        mysqli_query($conn, $sql);
        header("Location: ../../views/member/transaction.php?plan_id=$plan_id&upload=success");
    }
}