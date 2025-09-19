<?php
session_start();
require_once '../../middleware/auth.php';
authorize(['admin']);

include '../../includes/config.php';
include '../../includes/db.php';
include '../../models/adminModel/setQoutaModel.php';
include '../../models/adminModel/userModel.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../views/admin/users.php');
    exit();
}


if (!isset($_POST['user_id']) || !isset($_POST['quota']) || !isset($_POST['duration'])) {
    $_SESSION['error'] = "All required fields must be filled.";
    header('Location: ../../views/admin/setqouta.php?userid=' . $_POST['user_id']);
    exit();
}

$user_id = intval($_POST['user_id']);
$quota = intval($_POST['quota']);
$current_amount = isset($_POST['current_amount']) ? intval($_POST['current_amount']) : 0;
$duration = $_POST['duration'];

// Validate data
if ($quota <= 0) {
    $_SESSION['error'] = "Quota amount must be greater than 0.";
    header('Location: ../../views/admin/setqouta.php?userid=' . $user_id);
    exit();
}

if (strtotime($duration) <= time()) {
    $_SESSION['error'] = "Duration must be a future date.";
    header('Location: ../../views/admin/setqouta.php?userid=' . $user_id);
    exit();
}

if ($current_amount < 0) {
    $_SESSION['error'] = "Current amount cannot be negative.";
    header('Location: ../../views/admin/setqouta.php?userid=' . $user_id);
    exit();
}

// Verify user exists and is a unit manager
$userModel = new userModel($conn);
$user = $userModel->getUserById($user_id);

if (!$user || $user['role'] !== 'fraternal-counselor') {
    $_SESSION['error'] = "Invalid user or user is not a fraternal counselor.";
    header('Location: ../../views/admin/users.php');
    exit();
}

// Initialize quota model
$quotaModel = new setQoutaModel($conn);

// Check if user has an active quota
$activeQuota = $quotaModel->hasActiveQuota($user_id);

if ($activeQuota) {
    $_SESSION['error'] = "Cannot set new quota while current quota is in progress. Please wait until the current quota is completed or expired.";
    header('Location: ../../views/admin/setqouta.php?userid=' . $user_id);
    exit();
}

// Set the quota
$result = $quotaModel->setQouta($user_id, $quota, $current_amount, $duration);

if ($result) {
    $_SESSION['success'] = "Quota has been successfully set for " . htmlspecialchars($user['firstname'] . ' ' . $user['lastname']);
    header('Location: ../../views/admin/users.php');
} else {
    $_SESSION['error'] = "Failed to set quota. Please try again.";
    header('Location: ../../views/admin/setqouta.php?userid=' . $user_id);
}

exit();
?>
