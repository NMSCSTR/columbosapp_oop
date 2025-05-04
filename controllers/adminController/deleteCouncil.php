<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/councilModel.php';

$councilModel = new CouncilModel($conn);

if (isset($_GET['id'])) {
    $councilId = $_GET['id'];

    $deleted = $councilModel->deleteCouncil($councilId);

    if ($deleted) {
        $_SESSION['success'] = 'Council deleted successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
    }

    header('Location: ' . BASE_URL . 'views/admin/council.php');
    exit();
} else {
    header('Location: ' . BASE_URL . 'views/admin/council.php?message=noid');
    exit();
}
