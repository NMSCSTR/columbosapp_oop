<?php
require_once '../../middleware/auth.php';
require_once '../../includes/config.php';
require_once '../../includes/db.php';
require_once '../../models/adminModel/userModel.php';

// Ensure only admin can access this
authorize(['admin']);

header('Content-Type: application/json');

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit;
}

$userId = $_GET['id'];
$userModel = new UserModel($conn);

try {

    $tempPassword = bin2hex(random_bytes(4));
    
    
    $user = $userModel->getUserById($userId);
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }


    $hashedPassword = password_hash($tempPassword, PASSWORD_DEFAULT);
    
    $updated = $userModel->updatePassword($userId, $hashedPassword);
    
    if ($updated) {

        $message = "Your password has been reset. Your temporary password is: " . $tempPassword . ". Please change it after logging in.";
        

        $ch = curl_init();
        $parameters = [
            'apikey' => '5bf90b2585f02b48d22e01d79503e591',
            'number' => $user['phone_number'],
            'message' => $message,
            'sendername' => 'SEMAPHORE'
        ];
        
        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/messages');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        
        $smsResponse = json_decode($response, true);
        
        if ($response && !isset($smsResponse['error'])) {
            echo json_encode(['success' => true, 'message' => 'Password reset successful. SMS sent to user.']);
        } else {
            echo json_encode([
                'success' => false, 
                'message' => 'Password updated but SMS could not be sent. Error: ' . ($smsResponse['error'] ?? 'Unknown error')
            ]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update password']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred: ' . $e->getMessage()]);
} 