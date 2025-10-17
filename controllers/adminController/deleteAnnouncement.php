<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/announcementModel.php';
include '../../models/adminModel/activityLogsModel.php';

$announcementModel = new announcementModel($conn);
$logModel = new activityLogsModel($conn);
$adminId = $_SESSION['user_id'] ?? null;

if (isset($_GET['id'])) {
    $announcementId = $_GET['id'];

    $details = $announcementModel->getAnnouncementDetails($announcementId);
    $subject = $details['subject'] ?? 'Unknown Announcement';
    $oldValue = $details ? json_encode($details) : 'Details unavailable';

    $deleted = $announcementModel->deleteAnnouncement($announcementId);

    if ($deleted) {
        $logModel->logActivity(
            $adminId,
            'ANNOUNCEMENT_DELETE',
            'announcements',
            $announcementId,
            "Deleted announcement: '" . substr($subject, 0, 50) . "...'",
            $oldValue,
            null                       // null ne kay ga delete manta walay new value
        );

        $_SESSION['success'] = 'Announcement deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete announcement. It may have already been deleted or does not exist.';
    }

    header('Location: ' . BASE_URL . 'views/admin/announcements.php');
    exit();
} else {
    $_SESSION['error'] = 'Missing ID parameter or Admin ID.';
    header('Location: ' . BASE_URL . 'views/admin/announcements.php?message=noid');
    exit();
}