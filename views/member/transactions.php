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

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<!-- Add custom styles -->
<style>
.dashboard-card {
    @apply bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 border border-gray-100;
}
.dashboard-card-title {
    @apply text-lg font-semibold text-gray-700 mb-2;
}
.dashboard-card-value {
    @apply text-3xl font-bold;
}
.table-container {
    @apply bg-white p-6 rounded-xl shadow-lg border border-gray-100;
}
/* Custom DataTables styling */
.dataTables_wrapper .dataTables_filter input {
    @apply rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
}
.dataTables_wrapper .dataTables_length select {
    @apply rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    @apply bg-blue-500 text-white rounded-lg border-0 !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button:hover {
    @apply bg-blue-100 text-blue-700 rounded-lg border-0 !important;
}

/* Full width and zoom stability */
.main-container {
    width: 100% !important;
    max-width: none !important;
    min-width: 0 !important;
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* Ensure content doesn't break on zoom */
.content-wrapper {
    width: 100%;
    overflow-x: auto;
    min-width: 0;
    max-width: none !important;
}

/* Override sidebar constraints */
main {
    width: calc(100% - 5rem) !important; /* Account for sidebar width on mobile */
}

@media (min-width: 768px) {
    main {
        width: calc(100% - 16rem) !important; /* Account for sidebar width on desktop */
    }
}

/* Prevent layout shifts on zoom */
* {
    box-sizing: border-box;
}

/* Ensure tables and cards maintain full width */
.table-container,
.dashboard-card,
.grid {
    width: 100% !important;
    max-width: 100% !important;
}

/* DataTables full width configuration */
.dataTables_wrapper {
    width: 100% !important;
    overflow-x: auto;
}

.dataTables_wrapper .dataTables_scroll {
    width: 100% !important;
}

.dataTables_wrapper table {
    width: 100% !important;
    min-width: 100% !important;
}

/* Ensure all containers use full available width */
.grid,
.table-container,
.dashboard-card {
    width: 100% !important;
    max-width: 100% !important;
    min-width: 0 !important;
}

/* Force full width on all child elements */
.content-wrapper * {
    max-width: 100% !important;
}

/* Ensure responsive grid maintains full width */
.grid-cols-1,
.grid-cols-2,
.grid-cols-4 {
    width: 100% !important;
}
</style>

<?php include '../../partials/memberSideBar.php'?>

<!-- Main Content -->
<main class="flex-1 p-6 md:p-8 overflow-y-auto w-full min-w-0 main-container bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="content-wrapper w-full">
        <!-- Header Section -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-8 text-white mb-8">
            <div class="relative z-10">
                <h1 class="text-4xl text-green-900 font-bold mb-2">Transaction History 💳</h1>
                <p class="text-blue-900 text-lg">Track your payment progress and contribution status</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-blue-400 opacity-20 transform rotate-12"></div>
        </div>

        <!-- Payment Overview Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Remaining Months Card -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-4 border border-orange-200 hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3">
                    <div class="p-2 bg-orange-500 rounded-lg mb-2 sm:mb-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-2xl font-bold text-orange-700">
                            <?php echo ($totalPayments > 0 && $monthsPerPayment > 0) ? (int)$remainingMonths : 'N/A'; ?>
                        </p>
                        <p class="text-xs text-orange-600 font-medium">Remaining Months</p>
                    </div>
                </div>
                <div class="flex items-center text-xs text-orange-600">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Time to complete</span>
                </div>
            </div>

            <!-- Payment Mode Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-4 border border-purple-200 hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3">
                    <div class="p-2 bg-purple-500 rounded-lg mb-2 sm:mb-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-lg font-bold text-purple-700"><?php echo htmlspecialchars($paymentMode ?: 'Unknown'); ?></p>
                        <p class="text-xs text-purple-600 font-medium">Payment Mode</p>
                    </div>
                </div>
                <div class="flex items-center text-xs text-purple-600">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Billing frequency</span>
                </div>
            </div>

            <!-- Contribution Period Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-4 border border-green-200 hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3">
                    <div class="p-2 bg-green-500 rounded-lg mb-2 sm:mb-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-2xl font-bold text-green-700"><?php echo (int)$contributionPeriodYears; ?></p>
                        <p class="text-xs text-green-600 font-medium">Contribution Period</p>
                    </div>
                </div>
                <div class="flex items-center text-xs text-green-600">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Years to complete</span>
                </div>
            </div>

            <!-- Fraternal Benefit Card -->
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-lg p-4 border border-indigo-200 hover:shadow-lg transition-all duration-300">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3">
                    <div class="p-2 bg-indigo-500 rounded-lg mb-2 sm:mb-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div class="text-left sm:text-right">
                        <p class="text-sm font-bold text-indigo-700 truncate"><?php echo htmlspecialchars($benefitName); ?></p>
                        <p class="text-xs text-indigo-600 font-medium">Fraternal Benefit</p>
                    </div>
                </div>
                <div class="flex items-center text-xs text-indigo-600">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Your plan</span>
                </div>
            </div>
        </div>

        <!-- Payment Details Section -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <!-- Payment Amount Card -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg p-4 border border-blue-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-blue-800 mb-1">Payment per <?php echo htmlspecialchars($paymentLabel); ?></h3>
                        <p class="text-xl sm:text-2xl font-bold text-blue-700">
                            <?php echo htmlspecialchars($currencyCode); ?> <?php echo number_format($perPeriodAmount, 2); ?>
                        </p>
                    </div>
                    <div class="p-2 bg-blue-500 rounded-lg mt-2 sm:mt-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center text-xs text-blue-600">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Regular payment amount</span>
                </div>
            </div>

            <!-- Total Paid Card -->
            <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg p-4 border border-emerald-200">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="text-sm font-semibold text-emerald-800 mb-1">Total Paid</h3>
                        <p class="text-xl sm:text-2xl font-bold text-emerald-700">
                            <?php echo htmlspecialchars($currencyCode); ?> <?php echo number_format($totalPaid, 2); ?>
                        </p>
                    </div>
                    <div class="p-2 bg-emerald-500 rounded-lg mt-2 sm:mt-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="flex items-center text-xs text-emerald-600">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Amount contributed so far</span>
                </div>
            </div>
        </div>
        <!-- Transaction History Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 sm:mb-6">
                <h2 class="text-lg sm:text-2xl font-bold text-gray-800 mb-2 sm:mb-0">Transaction History</h2>
                <div class="flex items-center text-xs sm:text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                    <span>Payment Records</span>
                </div>
            </div>

            <?php if (empty($transactionHistory)): ?>
                <!-- No Transactions State -->
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">No Transactions Found</h3>
                    <p class="text-gray-600 mb-4">You don't have any payment records yet.</p>
                    <p class="text-sm text-gray-500">Your transaction history will appear here once you make your first payment.</p>
                </div>
            <?php else: ?>
                <!-- Transactions Table -->
                <div class="overflow-x-auto w-full">
                    <table id="myTable25" class="stripe hover w-full border-collapse" style="width:100% !important; min-width: 100%; font-size: 0.75rem;">
                        <thead class="bg-gray-800 text-white text-xs uppercase">
                            <tr>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap">Transaction ID</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap">Payment Date</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap">Amount Paid</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap">Currency</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap">Status</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap">Payment Timing</th>
                                <th class="px-2 py-2 sm:px-4 sm:py-3 whitespace-nowrap">Next Due Date</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs sm:text-sm">
                            <?php foreach ($transactionHistory as $transaction): ?>
                                <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-700 font-medium">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-2 sm:mr-3">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                </svg>
                                            </div>
                                            <span class="font-mono text-xs sm:text-sm truncate"><?php echo htmlspecialchars($transaction['transaction_id']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-700">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-xs sm:text-sm"><?php echo date('M d, Y', strtotime($transaction['payment_date'])); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-700">
                                        <div class="flex items-center">
                                            <span class="font-semibold text-sm sm:text-lg"><?php echo htmlspecialchars($transaction['currency']); ?> <?php echo number_format((float)$transaction['amount_paid'], 2); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-700">
                                        <span class="px-1 py-1 sm:px-2 sm:py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full">
                                            <?php echo htmlspecialchars($transaction['currency']); ?>
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3">
                                        <span class="px-2 py-1 sm:px-3 sm:py-1 text-xs font-medium rounded-full
                                            <?php 
                                                $status = strtolower($transaction['status']);
                                                if ($status === 'paid') {
                                                    echo 'bg-green-100 text-green-700';
                                                } elseif ($status === 'pending') {
                                                    echo 'bg-yellow-100 text-yellow-700';
                                                } else {
                                                    echo 'bg-red-100 text-red-700';
                                                }
                                            ?>">
                                            <div class="flex items-center">
                                                <?php if ($status === 'paid'): ?>
                                                    <svg class="w-2 h-2 sm:w-3 sm:h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                <?php else: ?>
                                                    <svg class="w-2 h-2 sm:w-3 sm:h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                <?php endif; ?>
                                                <span class="text-xs"><?php echo htmlspecialchars($transaction['status']); ?></span>
                                            </div>
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-700">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="text-xs sm:text-sm"><?php echo htmlspecialchars($transaction['payment_timing_status']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-2 py-2 sm:px-4 sm:py-3 text-gray-700">
                                        <div class="flex items-center">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-gray-400 mr-1 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="text-xs sm:text-sm"><?php echo date('M d, Y', strtotime($transaction['next_due_date'])); ?></span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        </div>
    </main>

<!-- DataTables JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    const tableConfig = {
        responsive: true,
        autoWidth: false,
        scrollX: true,
        dom: '<"flex justify-between items-center mb-4"Bf>rt<"flex justify-between items-center mt-4"lip>',
        buttons: [
            {
                extend: 'copy',
                className: 'px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500',
            },
            {
                extend: 'csv',
                className: 'px-3 py-2 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
            },
            {
                extend: 'excel',
                className: 'px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
            },
            {
                extend: 'pdf',
                className: 'px-3 py-2 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
            },
            {
                extend: 'print',
                className: 'px-3 py-2 text-xs font-medium text-white bg-purple-600 rounded hover:bg-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-purple-500',
            }
        ],
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    };

    $('#myTable25').DataTable(tableConfig);
});
</script>

<?php include '../../includes/footer.php'; ?>