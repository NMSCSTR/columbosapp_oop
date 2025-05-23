<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/councilModel.php';

$model = new CouncilModel($conn);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $council_number         = $_POST['council_number'];
    $council_name           = $_POST['council_name'];
    $unit_manager_id        = $_POST['unit_manager_id'];
    $fraternal_counselor_id = $_POST['fraternal_counselor_id'];
    $date_established       = $_POST['date_established'];
    $date_created           = date("Y-m-d H:i:s");

    $success = $model->insertCouncil(
        $council_number,
        $council_name,
        $unit_manager_id,
        $fraternal_counselor_id,
        $date_established,
        $date_created
    );

    if ($success) {
        $_SESSION['success'] = 'Council created successfully.';
    } else {
        $_SESSION['error'] = 'Something went wrong. Please try again.';
    }
    header('Location: ' . BASE_URL . 'views/admin/council.php');
    exit();

    
}
?>
