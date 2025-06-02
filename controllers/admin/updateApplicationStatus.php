<?php
require_once '../../middleware/auth.php';
authorize(['admin']);
include '../../includes/db.php';
include '../../models/memberModel/memberApplicationModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Get and validate input
        $applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        // Validate inputs
        if ($applicant_id <= 0) {
            throw new Exception('Invalid applicant ID');
        }

        if (!in_array($status, ['Approved', 'Rejected'])) {
            throw new Exception('Invalid status');
        }

        // Initialize model and update status
        $applicationModel = new MemberApplicationModel($conn);
        $result = $applicationModel->updateApplicationStatus($applicant_id, $status);

        if ($result) {
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