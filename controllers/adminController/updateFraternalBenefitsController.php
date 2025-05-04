<?php
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/fraternalBenefitsModel.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $type = $_POST['type'];
    $name = $_POST['name'];
    $about = $_POST['about'];
    $benefits = $_POST['benefits'];
    $contribution_period = $_POST['contribution_period'];

    // Upload path setup
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/app/uploads/fraternalBenefitsUpload/';
    $imagePath = $_POST['current_image'] ?? ''; // Retain current image unless a new one is uploaded

    // If a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $cleanType = preg_replace('/[^A-Za-z0-9]/', '', $type);
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowedTypes)) {
            // Rename uploaded file using same scheme as insert
            $imageName = $cleanType . 'Image_' . time() . '.' . $imageFileType;
            $targetPath = $uploadDir . $imageName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = 'uploads/fraternalBenefitsUpload/' . $imageName;
            } else {
                $_SESSION['error'] = "Failed to upload image.";
                header("Location: " . BASE_URL . "views/admin/fraternalBenefits.php");
                exit;
            }
        } else {
            $_SESSION['error'] = "Invalid image format. Only JPG, PNG, and GIF are allowed.";
            header("Location: " . BASE_URL . "views/admin/fraternalBenefits.php");
            exit;
        }
    }

    // Update in DB
    $model = new fraternalBenefitsModel($conn);
    $result = $model->updateFraternalBenefits($id, $type, $name, $about, $benefits, $contribution_period, $imagePath);

    if ($result) {
        $_SESSION['success'] = 'Plan updated successfully.';
        header("Location: " . BASE_URL . "views/admin/fraternalBenefits.php");
        exit;
    } else {
        $_SESSION['error'] = 'Failed to update. Please try again.';
        header("Location: " . BASE_URL . "views/admin/editFraternalBenefit.php?id=" . $id);
        exit;
    }
}
?>
