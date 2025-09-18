<?php
require_once '../../middleware/auth.php';
authorize(['member']);
include '../../includes/config.php';
include '../../includes/db.php';    
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/TransactionModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/memberModel/memberApplicationModel.php';
include '../../includes/functions.php';

$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user_id']);
$transactionModel = new TransactionModel($conn);
$transactionHistory = $transactionModel->getTransactionsById($_SESSION['user_id']);

// Compute total expected contribution based on selected fraternal benefit
$memberAppModel = new MemberApplicationModel($conn);
$applicantData = $memberAppModel->fetchAllApplicantsByIdV2($_SESSION['user_id']);
$totalContribution = 0;
if ($applicantData && isset($applicantData['total_contribution'])) {
    $totalContribution = (float)$applicantData['total_contribution'];
}

// Determine remaining months config from plan and benefit
$plansData = $applicantData['plans'] ?? null;
$paymentMode = strtolower($plansData['payment_mode'] ?? '');
$fraternalBenefitsId = isset($plansData['fraternal_benefits_id']) ? (int)$plansData['fraternal_benefits_id'] : 0;

$benefitModel = new fraternalBenefitsModel($conn);
$benefit = $fraternalBenefitsId ? $benefitModel->getFraternalBenefitById($fraternalBenefitsId) : null;
$contributionPeriodYears = (int)($benefit['contribution_period'] ?? 0);
$benefitName = $benefit['name'] ?? 'Unknown Plan';

// Payments per year and months per payment by mode
$periodsPerYear = 0;
$monthsPerPayment = 0;
switch ($paymentMode) {
    case 'monthly':
        $periodsPerYear = 12; $monthsPerPayment = 1; break;
    case 'quarterly':
        $periodsPerYear = 4; $monthsPerPayment = 3; break;
    case 'semi-annually':
    case 'semiannually':
        $periodsPerYear = 2; $monthsPerPayment = 6; break;
    case 'annually':
    case 'yearly':
        $periodsPerYear = 1; $monthsPerPayment = 12; break;
}

$totalPayments = $periodsPerYear * $contributionPeriodYears;

// Compute per-period payment amount based on mode
$contributionAmount = isset($plansData['contribution_amount']) ? (float)$plansData['contribution_amount'] : 0;
$currencyCode = $plansData['currency'] ?? ($transactionHistory[0]['currency'] ?? 'PHP');
$perPeriodAmount = ($monthsPerPayment > 0) ? $contributionAmount * $monthsPerPayment : 0;
$paymentLabel = $paymentMode ? ucfirst($paymentMode) : 'Payment';

// Count completed payments and compute remaining months overall
$paymentsCount = 0;
foreach ($transactionHistory as $t) {
    if (strtolower($t['status'] ?? '') === 'paid') {
        $paymentsCount++;
    }
}
$remainingPayments = max($totalPayments - $paymentsCount, 0);
$remainingMonths = ($monthsPerPayment > 0) ? $remainingPayments * $monthsPerPayment : 0;

// Compute total amount paid (sum of Paid transactions)
$totalPaid = 0;
foreach ($transactionHistory as $t) {
    if (strtolower($t['status'] ?? '') === 'paid') {
        $totalPaid += (float)($t['amount_paid'] ?? 0);
    }
}

// Sort transactions by payment date ascending for correct running balance
if (!empty($transactionHistory)) {
    usort($transactionHistory, function($a, $b) {
        $ad = strtotime($a['payment_date'] ?? '');
        $bd = strtotime($b['payment_date'] ?? '');
        if ($ad === $bd) { return 0; }
        return ($ad < $bd) ? -1 : 1;
    });
}

// Debug (remove in production)
// echo "<pre>"; print_r($transactionHistory); echo "</pre>";
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/memberSideBar.php'?>
    <!-- Main Content -->
    <main class="flex-1 w-full">
        <div class="p-4">
            <h2 class="text-2xl font-bold mb-4">Transaction History</h2>

            <div class="mb-4 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 rounded-lg border bg-white shadow-sm">
                    <div class="text-sm text-gray-500">Remaining Months</div>
                    <div class="text-2xl font-semibold text-gray-900">
                        <?php echo ($totalPayments > 0 && $monthsPerPayment > 0) ? (int)$remainingMonths : 'N/A'; ?>
                    </div>
                </div>
                <div class="p-4 rounded-lg border bg-white shadow-sm">
                    <div class="text-sm text-gray-500">Payment Mode</div>
                    <div class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($paymentMode ?: 'Unknown'); ?></div>
                </div>
                <div class="p-4 rounded-lg border bg-white shadow-sm">
                    <div class="text-sm text-gray-500">Contribution Period (years)</div>
                    <div class="text-lg font-medium text-gray-900"><?php echo (int)$contributionPeriodYears; ?></div>
                </div>
                <div class="p-4 rounded-lg border bg-white shadow-sm">
                    <div class="text-sm text-gray-500">Fraternal Benefit</div>
                    <div class="text-lg font-medium text-gray-900"><?php echo htmlspecialchars($benefitName); ?></div>
                </div>
            </div>

            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 rounded-lg border bg-white shadow-sm">
                    <div class="text-sm text-gray-500">Payment per <?php echo htmlspecialchars($paymentLabel); ?></div>
                    <div class="text-xl font-semibold text-gray-900">
                        <?php echo htmlspecialchars($currencyCode); ?> <?php echo number_format($perPeriodAmount, 2); ?>
                    </div>
                </div>
                <div class="p-4 rounded-lg border bg-white shadow-sm">
                    <div class="text-sm text-gray-500">Total Paid</div>
                    <div class="text-xl font-semibold text-gray-900">
                        <?php echo htmlspecialchars($currencyCode); ?> <?php echo number_format($totalPaid, 2); ?>
                    </div>
                </div>
            </div>
            
            <?php if (empty($transactionHistory)): ?>
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    No transactions found.
                </div>
            <?php else: ?>
                <div class="overflow-x-auto w-full">
                    <table id="myTable" class="w-full min-w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Transaction ID</th>
                                <th scope="col" class="px-6 py-3">Payment Date</th>
                                <th scope="col" class="px-6 py-3">Amount Paid</th>
                                <th scope="col" class="px-6 py-3">Currency</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Payment Timing</th>
                                <th scope="col" class="px-6 py-3">Next Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactionHistory as $transaction): ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($transaction['transaction_id']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($transaction['payment_date']); ?></td>
                                    <td class="px-6 py-4"><?php echo number_format((float)$transaction['amount_paid'], 2); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($transaction['currency']); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            <?php echo $transaction['status'] === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?>">
                                            <?php echo htmlspecialchars($transaction['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($transaction['payment_timing_status']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($transaction['next_due_date']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>