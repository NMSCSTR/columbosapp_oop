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
include '../../models/adminModel/TransactionModel.php';


$userModel          = new UserModel($conn);
$user               = $userModel->getUserById($_SESSION['user_id']);
$fraternalCounselor = $userModel->getUserWhereRoleFraternalCounselor();

$transactionModel = new TransactionModel($conn);

$applicationModel = new MemberApplicationModel($conn);
$applications     = $applicationModel->getApplicationsByUser($_SESSION['user_id']);

$applicationModel = new MemberApplicationModel($conn);
$applications     = $applicationModel->getApplicationsByUser($_SESSION['user_id']);
?>

<link rel="stylesheet" href="stylesheet/member.css">
<?php include '../../partials/memberSideBar.php'?>

<main class="flex-1 p-4 md:p-8 bg-gray-50 min-h-screen">
    <div class="max-w-12xl mx-auto">
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">My Applications</h1>
                <p class="text-sm text-gray-500">View and track the status of your benefit plan applications.</p>
            </div>
            </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200">
                            <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Application ID</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Plan Details</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Financials</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Period</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-xs font-semibold text-gray-600 uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php if (!empty($applications)): ?>
                            <?php foreach ($applications as $app): 
                                $financials = $transactionModel->getPlanFinancialSummary($_SESSION['user_id'], $app['plan_id']);
                                $status = strtolower($app['application_status']);
                                $statusClasses = match($status) {
                                    'approved' => 'bg-green-100 text-green-700 border-green-200',
                                    'pending'  => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                                    'rejected' => 'bg-red-100 text-red-700 border-red-200',
                                    default    => 'bg-gray-100 text-gray-700 border-gray-200',
                                };
                            ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span class="font-mono text-sm font-medium text-indigo-600">#<?php echo htmlspecialchars($app['applicant_id']); ?></span>
                                        <div class="text-xs text-gray-400 mt-1"><?php echo date('M d, Y', strtotime($app['application_date'])); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($app['plan_name']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars($app['plan_type']); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-medium">
                                            <?php echo htmlspecialchars($app['currency']); ?> <?php echo number_format($app['contribution_amount'], 2); ?>
                                        </div>
                                        <div class="text-xs text-gray-500">Face Value: <?php echo number_format($app['face_value'], 2); ?></div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div><?php echo htmlspecialchars($app['payment_mode']); ?></div>
                                        <div class="text-xs text-gray-400"><?php echo htmlspecialchars($app['contribution_period']); ?> Years</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900">
                                            Paid: <span class="text-green-600">₱<?php echo number_format($financials['total_paid'], 2); ?></span>
                                        </div>
                                        <div class="text-xs font-semibold text-orange-600 mt-1">
                                            Balance: ₱<?php echo number_format($financials['remaining_balance'], 2); ?>
                                        </div>
                                        <div class="text-[10px] text-gray-400 mt-1 uppercase tracking-wider">
                                            Total Contract: ₱<?php echo number_format($financials['total_contract_price'], 2); ?>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <div class="font-medium text-indigo-600"><?php echo $financials['remaining_months']; ?> Months Left</div>
                                        <div class="text-xs text-gray-400"><?php echo htmlspecialchars($app['payment_mode']); ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-full text-xs font-medium border <?php echo $statusClasses; ?>">
                                            <?php echo ucfirst(htmlspecialchars($app['application_status'])); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="transaction.php?plan_id=<?php echo $app['plan_id']; ?>"
                                           class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 text-sm font-medium rounded-md text-gray-700 hover:bg-gray-50 hover:text-indigo-600 transition-all shadow-sm">
                                           <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                           View
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <p class="text-gray-500 text-lg">No applications found.</p>
                                        <p class="text-gray-400 text-sm">When you apply for a plan, it will appear here.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>