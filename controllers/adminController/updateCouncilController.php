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
    $council_id = $_POST['council_id'];
    $council_number = $_POST['council_number']; 
    $council_name = $_POST['council_name'];
    $unit_manager_id = $_POST['unit_manager_id'];
    $fraternal_counselor_id = $_POST['fraternal_counselor_id'];
    $date_established = $_POST['date_established'];

    if (!$adminId) {
        $_SESSION['error'] = 'Admin session not found. Please log in again.';
        header('Location: ' . BASE_URL . 'views/admin/login.php');
        exit();
    }

    $oldDetails = $model->getCouncilAllDetails($council_id);

    $updated = $model->updateCouncil(
        $council_id, 
        $council_number, 
        $council_name, 
        $unit_manager_id, 
        $fraternal_counselor_id, 
        $date_established
    );

    if ($updated) {
        
        $newDetails = [
            'council_number' => $council_number,
            'council_name' => $council_name,
            'unit_manager_id' => $unit_manager_id,
            'fraternal_counselor_id' => $fraternal_counselor_id,
            'date_established' => $date_established
        ];
        
    
        $logModel->logActivity(
            $adminId,                 
            'COUNCIL_UPDATE',         
            'council',                 
            $council_id,             
            "Updated Council #{$council_number}: '{$council_name}'", 
            $oldDetails ? json_encode($oldDetails) : 'N/A', 
            json_encode($newDetails)  
        );
        
        $_SESSION['success'] = 'Council updated successfully.';
    } else {
    
        $_SESSION['error'] = 'Failed to update council, or no changes were detected.';
    }
    
    header('Location: ' . BASE_URL . 'views/admin/council.php');
    exit();
} else {
    header('Location: ' . BASE_URL . 'views/admin/council.php?message=noid');
    exit();
}