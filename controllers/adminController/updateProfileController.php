<?php
require_once '../../middleware/auth.php';
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../models/adminModel/userModel.php';

// Ensure only admin can access this
authorize(['admin']);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$userModel = new UserModel($conn);
$userId = $_SESSION['user_id'];
$user = $userModel->getUserById($userId);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    exit;
}

// Get form data
$firstname = $_POST['firstname'] ?? '';
$lastname = $_POST['lastname'] ?? '';
$email = $_POST['email'] ?? '';
$phone_number = $_POST['phone_number'] ?? '';
$current_password = $_POST['current_password'] ?? '';
$new_password = $_POST['new_password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Validate required fields
if (empty($firstname) || empty($lastname) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'First name, last name, and email are required']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit;
}

// Check if password change is requested
$shouldUpdatePassword = !empty($current_password) || !empty($new_password) || !empty($confirm_password);

if ($shouldUpdatePassword) {
    // Validate all password fields are provided
    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        echo json_encode(['success' => false, 'message' => 'All password fields are required for password change']);
        exit;
    }

    // Verify current password
    if (!password_verify($current_password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit;
    }

    // Validate new password match
    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'New passwords do not match']);
        exit;
    }

    // Validate new password length
    if (strlen($new_password) < 8) {
        echo json_encode(['success' => false, 'message' => 'New password must be at least 8 characters long']);
        exit;
    }
}

try {
    // Start transaction
    mysqli_begin_transaction($conn);

    // Update user profile
    $updateData = [
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'phone_number' => $phone_number
    ];

    if ($shouldUpdatePassword) {
        $updateData['password'] = password_hash($new_password, PASSWORD_DEFAULT);
    }

    $success = $userModel->updateProfile($userId, $updateData);

    if ($success) {
        mysqli_commit($conn);
        echo json_encode([
            'success' => true, 
            'message' => 'Profile updated successfully',
            'reload' => true
        ]);
    } else {
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
    }
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
} 