<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/announcementModel.php';

$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject      = trim($_POST['subject']);
    $announcement = trim($_POST['announcement']);

    if (strlen($announcement) < 30) {
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Announcement must be at least 30 characters long.']);
            exit();
        } else {
            $_SESSION['error'] = 'Something went wrong. Please enter long announcement.';
            header("Location: " . BASE_URL . "views/admin/announcements.php?error=short");
            exit();
        }
    }

    $model = new AnnouncementModel($conn);
    $result = $model->insertAnnouncement($subject, $announcement);

    if ($isAjax) {
        header('Content-Type: application/json');
        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to add announcement.']);
        }
        exit();
    } else {
        if ($result) {
            $_SESSION['success'] = 'Announcement posted & send successfully.';
            header("Location: " . BASE_URL . "views/admin/announcements.php?success=true");
        } else {
            $_SESSION['error'] = 'Something went wrong. Please try again.';
            header("Location: " . BASE_URL . "views/admin/announcements.php?error=true");
        }
        exit();
    }
} else {
    header("Location: " . BASE_URL . "views/admin/announcements.php");
    exit();
}
?>
