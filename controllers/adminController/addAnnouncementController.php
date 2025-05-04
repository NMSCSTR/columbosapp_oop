<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/announcementModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject      = trim($_POST['subject']);
    $announcement = trim($_POST['announcement']);

    if (strlen($announcement) < 30) {
        $_SESSION['error'] = 'Something went wrong. Please enter long announcement.';
        header("Location: " . BASE_URL . "views/admin/announcements.php?error=short");
        exit();
    }

    $model = new AnnouncementModel($conn);
    $result = $model->insertAnnouncement($subject, $announcement);

    if ($result) {
        $_SESSION['success'] = 'Announcement posted & send successfully.';
        header("Location: " . BASE_URL . "views/admin/announcements.php?success=true");
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
        header("Location: " . BASE_URL . "views/admin/announcements.php?error=true");
    }
} else {
    header("Location: " . BASE_URL . "views/admin/announcements.php");
    exit();
}
?>
