<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/councilModel.php';
// NEW: Include the activity logs model
include '../../models/adminModel/activityLogsModel.php'; 

$model = new CouncilModel($conn);
// NEW: Initialize the activity logs model
$logModel = new activityLogsModel($conn);
// Retrieve Admin User ID (CRITICAL for logging)
$adminId = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $council_id = $_POST['council_id'];
    $council_number = $_POST['council_number']; 
    $council_name = $_POST['council_name'];
    $unit_manager_id = $_POST['unit_manager_id'];
    $fraternal_counselor_id = $_POST['fraternal_counselor_id'];
    $date_established = $_POST['date_established'];

    // Basic check for admin ID
    if (!$adminId) {
        $_SESSION['error'] = 'Admin session not found. Please log in again.';
        header('Location: ' . BASE_URL . 'views/admin/login.php');
        exit();
    }

    // 1. NEW: Get old details BEFORE update
    $oldDetails = $model->getCouncilAllDetails($council_id);

    // 2. Execute the update
    $updated = $model->updateCouncil(
        $council_id, 
        $council_number, 
        $council_name, 
        $unit_manager_id, 
        $fraternal_counselor_id, 
        $date_established
    );

    if ($updated) {
        
        // 3. NEW: Construct the new details for logging
        $newDetails = [
            'council_number' => $council_number,
            'council_name' => $council_name,
            'unit_manager_id' => $unit_manager_id,
            'fraternal_counselor_id' => $fraternal_counselor_id,
            'date_established' => $date_established
        ];
        
        // 4. NEW: Log the activity
        $logModel->logActivity(
            $adminId,                  // user_id: ID of the admin
            'COUNCIL_UPDATE',          // action_type
            'council',                 // entity_type
            $council_id,               // entity_id: The ID of the updated council
            "Updated Council #{$council_number}: '{$council_name}'", // action_details
            $oldDetails ? json_encode($oldDetails) : 'N/A', // old_value: Previous data
            json_encode($newDetails)   // new_value: Updated data
        );
        
        $_SESSION['success'] = 'Council updated successfully.';
    } else {
        // If the update fails, it might be due to a database error OR no changes were made.
        $_SESSION['error'] = 'Failed to update council, or no changes were detected.';
    }
    
    header('Location: ' . BASE_URL . 'views/admin/council.php');
    exit();
} else {
    header('Location: ' . BASE_URL . 'views/admin/council.php?message=noid');
    exit();
}