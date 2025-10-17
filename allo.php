<?php
// user_status_update.php (Example AJAX endpoint)

// --- 1. Include necessary files and initialize
// NOTE: You'll need to adapt these include paths
require_once 'db_config.php';          // Your database connection file ($conn)
require_once 'activityLogsModel.php';  // The class we created
// Assume you have a UserModel for updating user data
require_once 'userModel.php';          

// Start the session to get the Admin's ID
session_start();

// --- Basic Security Checks
// Check if the request is an AJAX POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'], $_POST['action'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['success' => false, 'message' => 'Invalid request method or missing parameters.']);
    exit;
}

// Check if the admin user is logged in (replace with your actual session check)
if (!isset($_SESSION['admin_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['success' => false, 'message' => 'Admin session expired or unauthorized.']);
    exit;
}

// --- 2. Setup Variables and Models
$adminId = (int) $_SESSION['admin_id']; // The ID of the admin performing the action
$userIdToUpdate = (int) $_POST['id'];
$action = $_POST['action']; // 'approve' or 'disable'

$logModel = new activityLogsModel($conn);
$userModel = new userModel($conn); // Assuming you have this model

// Determine the new status
$newStatus = '';
$actionDetails = '';
$actionType = 'USER_STATUS_CHANGE';

if ($action === 'approve') {
    $newStatus = 'active'; // or 'approved', depending on your `users` table
    $actionDetails = "Approved user ID $userIdToUpdate's account.";
} elseif ($action === 'disable') {
    $newStatus = 'disabled';
    $actionDetails = "Disabled user ID $userIdToUpdate's account.";
} else {
    // Handle invalid action
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid action specified.']);
    exit;
}

// --- 3. Get Current Status (for logging the 'old_value')
// You must retrieve the user's current status BEFORE updating it.
$currentStatus = $userModel->getUserStatus($userIdToUpdate); // You need to implement this function in userModel

if (!$currentStatus) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'User not found or failed to retrieve current status.']);
    exit;
}

// --- 4. Update the User's Status (The main action)
$updateSuccess = $userModel->updateUserStatus($userIdToUpdate, $newStatus); // You need to implement this function in userModel

if ($updateSuccess) {
    
    // --- 5. Log the Activity (The goal!) 💾
    $logSuccess = $logModel->logActivity(
        $adminId,
        $actionType,
        'users',
        $userIdToUpdate,
        $actionDetails,
        $currentStatus, // Old status
        $newStatus      // New status
    );

    // --- 6. Send Response
    // Log success/failure is generally not sent to the frontend, but logged internally
    // For debugging, you could include it: 'log_recorded' => $logSuccess
    
    echo json_encode([
        'success' => true, 
        'message' => "User ID $userIdToUpdate successfully set to '$newStatus'.",
        // 'log_recorded' => $logSuccess // Optional
    ]);

} else {
    // Update failed
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database update failed.']);
}

// Close the database connection
if (isset($conn)) {
    mysqli_close($conn);
}
?>