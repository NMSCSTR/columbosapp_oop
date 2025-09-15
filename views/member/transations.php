<?php
require_once '../../middleware/auth.php';
authorize(['member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/memberModel/memberApplicationModel.php';
include '../../includes/functions.php';
$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user_id']);
$transactionModel = new TransactionModel($conn);
$transactionHistory = $transactionModel->getTransactionsById($user);
?>



<?php include '../../includes/footer.php'; ?>