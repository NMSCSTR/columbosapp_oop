<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/announcementModel.php';
include '../../models/adminModel/activityLogsModel.php';

$adminId = $_SESSION['user_id'] ?? null;
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "views/admin/announcements.php");
    exit();
}

$subject = trim($_POST['subject'] ?? '');
$announcement = trim($_POST['announcement'] ?? '');
$recipients = $_POST['recipients'] ?? [];

function respond($success, $message = '')
{
    global $isAjax;
    if ($isAjax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
        exit();
    } else {
        $_SESSION[$success ? 'success' : 'error'] = $message;
        $redirect = BASE_URL . "views/admin/announcements.php";
        header("Location: $redirect");
        exit();
    }
}

// Validate session
if (!$adminId) {
    respond(false, 'Session expired. Admin ID not found.');
}

// Validate inputs
if (strlen($announcement) < 30) {
    respond(false, 'Announcement must be at least 30 characters long.');
}

if (empty($recipients)) {
    respond(false, 'Please select at least one recipient.');
}

// Insert announcement
$model = new announcementModel($conn);
$logModel = new activityLogsModel($conn);

$newAnnouncementId = $model->insertAnnouncement($subject, $announcement, $recipients);

if ($newAnnouncementId) {
    // Log activity
    $logModel->logActivity(
        $adminId,
        'ANNOUNCEMENT_POST',
        'announcements',
        $newAnnouncementId,
        "Posted new announcement: '" . substr($subject, 0, 50) . "...'",
        null,
        "Subject: $subject"
    );

    respond(true, 'Announcement posted & SMS sent successfully.');
} else {
    respond(false, 'Failed to add announcement.');
}
