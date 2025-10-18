<?php
require_once '../../middleware/auth.php';
authorize(['admin']);
include '../../includes/db.php';
include '../../models/memberModel/memberApplicationModel.php';
include '../../models/adminModel/activityLogsModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
        $newStatus = isset($_POST['status']) ? $_POST['status'] : '';

        if ($applicant_id <= 0) {
            throw new Exception('Invalid applicant ID');
        }

        if (!in_array($newStatus, ['Approved', 'Rejected'])) {
            throw new Exception('Invalid status');
        }

        $applicationModel = new MemberApplicationModel($conn);
        $logModel = new activityLogsModel($conn);

        $adminId = $_SESSION['user_id'] ?? null;
        if (!$adminId) {
             throw new Exception('Admin user session not found.');
        }


        $details = $applicationModel->getApplicationDetails($applicant_id);
        
        if (!$details) {
            throw new Exception('Applicant not found.');
        }

        $oldStatus = $details['application_status'];
        $applicantName = $details['firstname'] . ' ' . $details['lastname'];
        

        if ($oldStatus === $newStatus) {
            throw new Exception("Application is already '$newStatus'. No update performed.");
        }

        $result = $applicationModel->updateApplicationStatus($applicant_id, $newStatus);

        if ($result) {
            
            $logModel->logActivity(
                $adminId,                 
                'APPLICATION_STATUS_CHANGE', 
                'applicants',            
                $applicant_id,        
                "$newStatus application for: $applicantName", 
                $oldStatus,                
                $newStatus                 
            );
            
            echo json_encode([
                'success' => true,
                'message' => 'Application status updated successfully'
            ]);
        } else {
            throw new Exception('Failed to update application status');
        }

    } catch (Exception $e) {

        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}