<?php 
session_start();
include '../../includes/db.php';
include '../../includes/config.php';
include '../../models/adminModel/FormsModel.php';

$formsModel = new FormsModel($conn);

// Handle File Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $filename    = $_POST['filename'];
    $description = $_POST['description'];
    $fileType    = $_POST['file_type'];
    $userId      = $_POST['user_id'];

    $uploadPath = '../../uploads/forms/';
    if (!is_dir($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $originalName = basename($_FILES['file']['name']);
    $extension    = pathinfo($originalName, PATHINFO_EXTENSION);
    $uniqueName   = uniqid('form_', true) . '.' . $extension;
    $newFilePath  = $uploadPath . $uniqueName;
    $fileLocated  = BASE_URL . 'uploads/forms/' . $uniqueName;

    $uploadedOn = date("Y-m-d H:i:s"); // current timestamp

    if (move_uploaded_file($_FILES['file']['tmp_name'], $newFilePath)) {
        $result = $formsModel->addForms($filename, $description, $fileLocated, $fileType, $userId, $uploadedOn);
        $_SESSION['success'] = $result ? 'File uploaded successfully.' : 'Database error occurred.';
    } else {
        $_SESSION['error'] = 'File upload failed.';
    }

    header('Location: ' . BASE_URL . 'views/admin/forms.php');
    exit;
}

// Handle File Deletion
if (isset($_GET['delete'])) {
    $id   = $_GET['delete'];
    $form = mysqli_fetch_assoc($formsModel->getFormById($id));
    $path = '../../uploads/forms/' . basename($form['file_located']);

    if (file_exists($path)) {
        unlink($path);
    }

    $formsModel->deleteForms($id);
    header('Location: ' . BASE_URL . 'views/admin/forms.php?deleted=1');
    exit;
}

// Handle File Download
if (isset($_GET['download'])) {
    $id   = $_GET['download'];
    $form = mysqli_fetch_assoc($formsModel->getFormById($id));
    $path = '../../uploads/forms/' . basename($form['file_located']);

    if (file_exists($path)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"" . basename($path) . "\"");
        readfile($path);
        exit;
    }
}


?>