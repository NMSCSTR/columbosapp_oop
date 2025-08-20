<?php
require_once '../../middleware/auth.php';
authorize(['admin']);
include '../../includes/db.php';
include '../../models/adminModel/sendSmsModel.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	try {
		$applicant_id = isset($_POST['applicant_id']) ? intval($_POST['applicant_id']) : 0;
		$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
		$content = isset($_POST['content']) ? trim($_POST['content']) : '';

		if ($applicant_id <= 0) {
			throw new Exception('Invalid applicant ID');
		}

		if ($subject === '' || $content === '') {
			throw new Exception('Subject and content are required');
		}

		$template = isset($_POST['template']) ? trim($_POST['template']) : null;
		$dueDate = isset($_POST['due_date']) ? trim($_POST['due_date']) : null;

		$smsModel = new sendSmsModel($conn);
		$result = $smsModel->sendSmsToApplicant($applicant_id, $subject, $content, $template, $dueDate);

		echo json_encode($result);
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