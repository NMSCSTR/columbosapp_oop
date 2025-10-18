<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/activityLogsModel.php';

$model = new CouncilModel($conn);
$logModel = new activityLogsModel($conn);
$adminId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!$adminId) {
        $_SESSION['error'] = 'Admin session not found. Please log in again.';
        header('Location: ' . BASE_URL . 'views/admin/login.php'); // Redirect to login or error page
        exit();
    }

    $council_number         = $_POST['council_number'];
    $council_name           = $_POST['council_name'];
    $unit_manager_id        = $_POST['unit_manager_id'];
    $fraternal_counselor_id = $_POST['fraternal_counselor_id'];
    $date_established       = $_POST['date_established'];
    $date_created           = date("Y-m-d H:i:s");

    $newCouncilId = $model->insertCouncil(
        $council_number,
        $council_name,
        $unit_manager_id,
        $fraternal_counselor_id,
        $date_established,
        $date_created
    );
    if ($newCouncilId) {
        $logModel->logActivity(
            $adminId,               
            'ADD_COUNCIL',          
            'council',           
            $newCouncilId,          
            "Added new Council #{$council_number}: '{$council_name}'", 
            null,                  
            "Council No: {$council_number}, Name: {$council_name}"
        );
        $_SESSION['success'] = 'Council created successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong. Failed to create council.';
    }
    
    header('Location: ' . BASE_URL . 'views/admin/council.php');
    exit();
}
?>

   