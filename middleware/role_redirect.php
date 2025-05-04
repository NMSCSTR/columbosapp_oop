<?php 
session_start();
include '.././includes/config.php';

if (!isset($_SESSION['role'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit;
}

$roleRedirectMap = [
    'admin' => 'views/admin/admin.php',
    'family-member' => 'views/family-member/familymember.php',
    'member' => 'views/member/member.php',
    'unit-manager' => 'views/unit-manager/unitmanager.php',
    'fraternal-counselor' => 'views/fraternal-counselor/fraternalcounselor.php',
];

$role = $_SESSION['role'];

if (array_key_exists($role, $roleRedirectMap)) {
    header('Location: ' . BASE_URL . $roleRedirectMap[$role]);
    exit;
} else {
    header('Location: ' . BASE_URL . 'views/unauthorized.php');
    exit;
}

?>