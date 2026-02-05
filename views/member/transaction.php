<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/memberModel/memberApplicationModel.php';
include '../../models/adminModel/TransactionModel.php';

if (!isset($_GET['plan_id'])) {
    die("Plan ID not specified.");
}

$plan_id = intval($_GET['plan_id']);
$applicationModel = new MemberApplicationModel($conn);
$transactions = $applicationModel->getTransactionsByPlan($plan_id);
$plan_id = intval($_GET['plan_id']);
$user_id = $_SESSION['user_id']; // Get current logged-in user

$transactionModel = new TransactionModel($conn);
$applicationModel = new MemberApplicationModel($conn);

$transactions = $applicationModel->getTransactionsByPlan($plan_id);
$planDetails = $applicationModel->getPlanDetails($plan_id);

// Fetch the calculated financials
$financials = $transactionModel->getPlanFinancialSummary($user_id, $plan_id);

// Optional: get plan details to show on top
$planDetails = $applicationModel->getPlanDetails($plan_id);
?>

<link rel="stylesheet" href="stylesheet/member.css">
<?php include '../../partials/memberSideBar.php'; ?>

<main
    class="flex-1 p-6 md:p-8 overflow-y-auto w-full min-w-0 main-container bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="content-wrapper w-full">
        <div class="p-4 rounded-lg dark:border-gray-700">
            <div class="max-w-12xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden p-6">

                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Transactions for Plan:
                    <?php echo $planDetails['plan_name']; ?></h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl">
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-wider">Total Contract Price</p>
                        <p class="text-xl font-black text-blue-900">₱
                            <?php echo number_format($financials['total_contract_price'], 2); ?></p>
                    </div>
                    <div class="bg-green-50 border border-green-100 p-4 rounded-xl">
                        <p class="text-xs font-bold text-green-600 uppercase tracking-wider">Total Amount Paid</p>
                        <p class="text-xl font-black text-green-900">₱
                            <?php echo number_format($financials['total_paid'], 2); ?></p>
                    </div>
                    <div class="bg-orange-50 border border-orange-100 p-4 rounded-xl">
                        <p class="text-xs font-bold text-orange-600 uppercase tracking-wider">Remaining Balance</p>
                        <p class="text-xl font-black text-orange-900">₱
                            <?php echo number_format($financials['remaining_balance'], 2); ?></p>
                    </div>
                </div>

                <p class="text-gray-600 mb-6">
                    Type: <?php echo $planDetails['plan_type']; ?> |
                    <span class="font-bold text-indigo-600"><?php echo $financials['remaining_months']; ?> months
                        remaining</span>
                </p>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Transaction ID</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Payment Date</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Amount Paid</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Currency</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Next Due Date</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">
                                    Timing</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (count($transactions) > 0): ?>
                            <?php foreach ($transactions as $tx): ?>
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-4 py-2 text-gray-700 whitespace-nowrap">
                                    <?php echo $tx['transaction_id']; ?></td>
                                <td class="px-4 py-2 text-gray-700 whitespace-nowrap">
                                    <?php echo date('M d, Y', strtotime($tx['payment_date'])); ?></td>
                                <td class="px-4 py-2 text-gray-700 whitespace-nowrap">
                                    <?php echo number_format($tx['amount_paid'],2); ?></td>
                                <td class="px-4 py-2 text-gray-700 whitespace-nowrap"><?php echo $tx['currency']; ?>
                                </td>
                                <td class="px-4 py-2 text-gray-700 whitespace-nowrap">
                                    <?php echo $tx['next_due_date'] ? date('M d, Y', strtotime($tx['next_due_date'])) : '-'; ?>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <?php if($tx['status'] == 'Paid'): ?>
                                    <span
                                        class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800"><?php echo $tx['status']; ?></span>
                                    <?php else: ?>
                                    <span
                                        class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800"><?php echo $tx['status']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap">
                                    <?php if(strtolower($tx['payment_timing_status']) == 'on-time'): ?>
                                    <span
                                        class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800"><?php echo $tx['payment_timing_status']; ?></span>
                                    <?php else: ?>
                                    <span
                                        class="px-2 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><?php echo $tx['payment_timing_status']; ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-4 py-4 text-center text-gray-500">No transactions found for
                                    this plan.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card Layout -->
                <div class="mt-6 md:hidden">
                    <?php foreach ($transactions as $tx): ?>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-gray-700">Transaction ID:</span>
                            <span class="text-gray-600"><?php echo $tx['transaction_id']; ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-gray-700">Payment Date:</span>
                            <span
                                class="text-gray-600"><?php echo date('M d, Y', strtotime($tx['payment_date'])); ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-gray-700">Amount Paid:</span>
                            <span
                                class="text-gray-600"><?php echo number_format($tx['amount_paid'],2) . ' ' . $tx['currency']; ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-gray-700">Next Due:</span>
                            <span
                                class="text-gray-600"><?php echo $tx['next_due_date'] ? date('M d, Y', strtotime($tx['next_due_date'])) : '-'; ?></span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-gray-700">Status:</span>
                            <?php if($tx['status'] == 'Paid'): ?>
                            <span
                                class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800"><?php echo $tx['status']; ?></span>
                            <?php else: ?>
                            <span
                                class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800"><?php echo $tx['status']; ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="font-semibold text-gray-700">Timing:</span>
                            <?php if(strtolower($tx['payment_timing_status']) == 'on-time'): ?>
                            <span
                                class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800"><?php echo $tx['payment_timing_status']; ?></span>
                            <?php else: ?>
                            <span
                                class="px-2 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><?php echo $tx['payment_timing_status']; ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-6">
                    <a href="myApplication.php"
                        class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition duration-150">Back
                        to Applications</a>
                </div>

            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>