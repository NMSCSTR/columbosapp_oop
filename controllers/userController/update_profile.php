<?php
require_once '../../middleware/auth.php';
require_once '../../includes/config.php';
require_once '../../includes/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

try {
    // Get POST data
    $firstname = $_POST['firstname'] ?? '';
    $lastname = $_POST['lastname'] ?? '';
    $email = $_POST['email'] ?? '';
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';

    // Validate current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user || !password_verify($current_password, $user['password'])) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect']);
        exit;
    }

    // Start building the update query
    $updates = [];
    $types = "";
    $params = [];

    // Add basic info updates
    $updates[] = "firstname = ?";
    $updates[] = "lastname = ?";
    $updates[] = "email = ?";
    $types .= "sss";
    $params[] = $firstname;
    $params[] = $lastname;
    $params[] = $email;

    // Add password update if provided
    if (!empty($new_password)) {
        $updates[] = "password = ?";
        $types .= "s";
        $params[] = password_hash($new_password, PASSWORD_DEFAULT);
    }

    // Add user ID to params
    $types .= "i";
    $params[] = $_SESSION['user_id'];

    // Build and execute the update query
    $sql = "UPDATE users SET " . implode(", ", $updates) . " WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    
    if ($stmt->execute()) {
        // Update session variables
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['email'] = $email;

        echo json_encode([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);
    } else {
        throw new Exception("Error updating profile");
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred: ' . $e->getMessage()
    ]);
} 