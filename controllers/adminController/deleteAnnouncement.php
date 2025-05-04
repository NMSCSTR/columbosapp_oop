<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/announcementModel.php';

$announcementModel = new announcementModel($conn);

if (isset($_GET['id'])) {
    $announcementId = $_GET['id'];

    $deleted = $announcementModel->deleteAnnouncement($announcementId);

    if ($deleted) {
        $_SESSION['success'] = 'Announcement deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
    }

    header('Location: ' . BASE_URL . 'views/admin/announcements.php');
    exit();
} else {
    header('Location: ' . BASE_URL . 'views/admin/announcements.php?message=noid');
    exit();
}
