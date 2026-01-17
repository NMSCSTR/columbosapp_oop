<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/memberModel/memberApplicationModel.php';

$userModel          = new UserModel($conn);
$user               = $userModel->getUserById($_SESSION['user_id']);
$fraternalCounselor = $userModel->getUserWhereRoleFraternalCounselor();

$applicationModel = new MemberApplicationModel($conn);
$applications     = $applicationModel->getApplicationsByUser($_SESSION['user_id']);
?>

<link rel="stylesheet" href="stylesheet/member.css">
<?php include '../../partials/memberSideBar.php'?>

<main class="flex-1 p-6 md:p-8 overflow-y-auto w-full min-w-0 main-container bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="content-wrapper w-full">
        <div class="p-4 rounded-lg dark:border-gray-700">
            <div class="max-w-12xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden p-4">
                <h2 class="text-xl font-bold mb-4">List of Applications</h2>
                
                <div class="overflow-x-auto">
                    <!-- <div class="mt-6 text-right">
                        <a href="applyNewPlan.php" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-150">
                        Apply for New Plan
                        </a>
                    </div> -->

                    <table class="table-auto w-full border-collapse border border-gray-300 text-sm">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="border px-4 py-2">Application ID</th>
                                <th class="border px-4 py-2">Plan Name</th>
                                <th class="border px-4 py-2">Type</th>
                                <th class="border px-4 py-2">Face Value</th>
                                <th class="border px-4 py-2">Years of Protection</th>
                                <th class="border px-4 py-2">Contribution Period</th>
                                <th class="border px-4 py-2">Payment Mode</th>
                                <th class="border px-4 py-2">Contribution Amount</th>
                                <th class="border px-4 py-2">Currency</th>
                                <th class="border px-4 py-2">Status</th>
                                <th class="border px-4 py-2">Applied On</th>
                                <th class="border px-4 py-2">Transactions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($applications)): ?>
                                <?php foreach ($applications as $app): ?>
                                    <tr>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['applicant_id']); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['plan_name']); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['plan_type']); ?></td>
                                        <td class="border px-4 py-2"><?php echo number_format($app['face_value'], 2); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['years_of_protection']); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['contribution_period']); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['payment_mode']); ?></td>
                                        <td class="border px-4 py-2"><?php echo number_format($app['contribution_amount'], 2); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['currency']); ?></td>
                                        <td class="border px-4 py-2"><?php echo htmlspecialchars($app['application_status']); ?></td>
                                        <td class="border px-4 py-2"><?php echo date('Y-m-d', strtotime($app['application_date'])); ?></td>
                                        <td class="border px-4 py-2">
                                            <a href="transaction.php?plan_id=<?php echo $app['plan_id']; ?>"
                                               class="px-3 py-1 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700 transition duration-150">
                                               View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="12" class="border px-4 py-2 text-center text-gray-500">No applications found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>
