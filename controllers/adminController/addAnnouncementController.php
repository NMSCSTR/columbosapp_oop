<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/announcementModel.php';
include '../../models/adminModel/activityLogsModel.php';

$adminId = $_SESSION['user_id'] ?? null;


$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject      = trim($_POST['subject']);
    $announcement = trim($_POST['announcement']);

    if (!$adminId) {
        $errorMessage = 'Session expired. Admin ID not found.';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $errorMessage]);
        } else {
            $_SESSION['error'] = $errorMessage;
            header("Location: " . BASE_URL . "views/admin/announcements.php");
        }
        exit();
    }

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



    $logModel  = new activityLogsModel($conn);
    $model = new AnnouncementModel($conn);
    $newAnnouncementId = $model->insertAnnouncement($subject, $announcement);

    if ($newAnnouncementId) {
        

        $logModel->logActivity(
            $adminId,                     
            'ANNOUNCEMENT_POST',           
            'announcements',             
            $newAnnouncementId,            
            "Posted new announcement: '" . substr($subject, 0, 50) . "...'", 
            null,                          // null ne since ga add raman ug announcement
            "Subject: $subject"           
        );
        
        $successMessage = 'Announcement posted & send successfully.';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit();
        } else {
            $_SESSION['success'] = $successMessage;
            header("Location: " . BASE_URL . "views/admin/announcements.php?success=true");
            exit();
        }
    } else {
        $errorMessage = 'Failed to add announcement.';
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $errorMessage]);
            exit();
        } else {
            $_SESSION['error'] = 'Something went wrong. Please try again.';
            header("Location: " . BASE_URL . "views/admin/announcements.php?error=true");
            exit();
        }
    }
} else {
    header("Location: " . BASE_URL . "views/admin/announcements.php");
    exit();
}