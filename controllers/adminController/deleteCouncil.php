<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/activityLogsModel.php'; 

$councilModel = new CouncilModel($conn);

$logModel = new activityLogsModel($conn);

$adminId = $_SESSION['user_id'] ?? null;

if (isset($_GET['id']) && $adminId) {
    $councilId = $_GET['id'];
    
    $details = $councilModel->getCouncilDetails($councilId);
    $councilNumber = $details['council_number'] ?? 'N/A';
    $councilName = $details['council_name'] ?? 'Unknown Council';
    $oldValue = $details ? json_encode($details) : 'Details unavailable'; // Store details as JSON or text


    $deleted = $councilModel->deleteCouncil($councilId);

    if ($deleted) {
        
        
        $logModel->logActivity(
            $adminId,                 
            'COUNCIL_DELETE',         
            'council',                 
            $councilId,               
            "Deleted Council #{$councilNumber}: '{$councilName}'", 
            $oldValue,               
            null        
        );
        
        $_SESSION['success'] = 'Council deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete council. It may have already been deleted or does not exist.';
    }

    header('Location: ' . BASE_URL . 'views/admin/council.php');
    exit();
} else {
    $_SESSION['error'] = 'Missing ID parameter or Admin ID.';
    header('Location: ' . BASE_URL . 'views/admin/council.php?message=noid');
    exit();
}