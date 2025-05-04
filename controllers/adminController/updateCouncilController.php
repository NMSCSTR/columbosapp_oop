<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/councilModel.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $council_id = $_POST['council_id'];
    $council_number = $_POST['council_number']; 
    $council_name = $_POST['council_name'];
    $unit_manager_id = $_POST['unit_manager_id'];
    $fraternal_counselor_id = $_POST['fraternal_counselor_id'];
    $date_established = $_POST['date_established'];

    $model = new CouncilModel($conn);
    $updated = $model->updateCouncil($council_id, $council_number, $council_name, $unit_manager_id, $fraternal_counselor_id, $date_established);

    if ($updated) {
        $_SESSION['success'] = 'Council updated successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
    }
    header('Location: ' . BASE_URL . 'views/admin/council.php');
    exit();
} else {
    header('Location: ' . BASE_URL . 'views/admin/council.php?message=noid');
    exit();
}