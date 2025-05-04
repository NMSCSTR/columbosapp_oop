<?php
session_start();
include '../../includes/db.php';
include '../../models/usersModel.php';
include '../../includes/config.php';

$userModel = new UserModel($conn);
$userModel->logout();
?>
