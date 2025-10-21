<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/fraternalBenefitsModel.php';

$fraternalBenefitsModel = new fraternalBenefitsModel($conn);

if (isset($_GET['id'])) {
    $fraternalId = mysqli_real_escape_string($conn, $_GET['id']);

    // Get the current image path from DB
    $query = "SELECT image FROM fraternal_benefits WHERE id = '$fraternalId'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $imagePath = $row['image'];

        // Delete the image from server
        if (!empty($imagePath)) {
            $appRoot = dirname(__DIR__, 2);
            $fullImagePath = $appRoot . '/' . $imagePath;
            $fullImagePath = str_replace(['//', '\\'], '/', $fullImagePath);
            if (file_exists($fullImagePath)) {
                unlink($fullImagePath);
            }
        }

        // Now delete from database
        $deleted = $fraternalBenefitsModel->deleteFraternalBenefits($fraternalId);

        if ($deleted) {
            $_SESSION['success'] = 'Plan deleted successfully.';
        } else {
            $_SESSION['error'] = 'Something went wrong. Please try again.';
        }
    } else {
        $_SESSION['error'] = 'Fraternal benefit not found.';
    }

    header('Location: ' . BASE_URL . 'views/admin/fraternalBenefits.php');
    exit();
} else {
    header('Location: ' . BASE_URL . 'views/admin/fraternalBenefits.php?message=noid');
    exit();
}
?>
