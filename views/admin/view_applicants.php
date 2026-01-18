<?php
    session_start();
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/councilModel.php';
    include '../../includes/alert2.php';
    include '../../models/adminModel/FormsModel.php';
    include '../../models/adminModel/TransactionModel.php';
    
    $searchResults = $_SESSION['search_results'] ?? [];
    $transactions = [];
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $transactionModel = new TransactionModel($conn);
        $transactions = $transactionModel->getPaymentTransactionsByApplicant($user_id);
    } else {
        echo "User ID not set in session.";
        exit;
    }

    $c = $searchResults[0]['basicInfo'] ?? null;
    $d = $searchResults[0]['fullDetails'] ?? null;
?>

<div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-xl shadow-lg dark:bg-gray-800">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Make Payment</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <div class="p-4 md:p-5">
                <form class="space-y-4" action="../../controllers/adminController/transactionController.php" method="POST">
                    <input type="hidden" value="<?= $d['applicantData']['applicant_id'] ?? ''; ?>" name="applicant_id">
                    <input type="hidden" value="<?= $c['user_id'] ?? ''; ?>" name="user_id">
                    <input type="hidden" value="<?= $d['plans']['plan_id'] ?? '' ?>" name="plan_id">
                    
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">PAYMENT DATE</label>
                        <input type="date" name="payment_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">AMOUNT</label>
                            <input type="number" name="amount_paid" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required />
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">CURRENCY</label>
                            <input type="text" value="<?= $d['plans']['currency'] ?? 'PHP' ?>" name="currency" class="bg-gray-100 border border-gray-300 text-gray-500 text-sm rounded-lg block w-full p-2.5 cursor-not-allowed" readonly />
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">TIMING STATUS</label>
                        <select name="payment_timing_status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="On-Time">On-Time</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>

                    <button type="submit" name="submit_transaction" class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-bold rounded-lg text-sm px-5 py-3 transition-all">
                        CONFIRM PAYMENT
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php' ?>

    <main class="flex-1 transition-all duration-300 sm:ml-64">
        <div class="p-6">
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-2">
                    <li class="inline-flex items-center text-sm text-gray-500">Home</li>
                    <li><div class="flex items-center"><span class="mx-2 text-gray-400">/</span><span class="text-sm text-gray-500">Admin</span></div></li>
                    <li><div class="flex items-center"><span class="mx-2 text-gray-400">/</span><span class="text-sm font-bold text-gray-900">Applicant Details</span></div></li>
                </ol>
            </nav>

            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 bg-gray-50/50">
                        <h2 class="text-lg font-bold text-gray-800">Applicant Information</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4">Name</th>
                                    <th class="px-6 py-4">Gender</th>
                                    <th class="px-6 py-4">Birthdate</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Contribution</th>
                                    <th class="px-6 py-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($searchResults as $result): 
                                    $a = $result['fullDetails']['applicantData']; ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($a['firstname'] . ' ' . $a['lastname']) ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($a['gender']) ?></td>
                                    <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($a['birthdate']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"><?= htmlspecialchars($a['application_status']) ?></span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-gray-900">
                                        ₱<?= number_format($result['fullDetails']['plans']['contribution_amount'] ?? 0, 2) ?>
                                    </td>
                                    <td class="px-6 py-4">
                                        <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="flex items-center text-blue-600 hover:text-blue-800 font-bold transition-colors">
                                            <i class="fas fa-credit-card mr-2"></i> PAY
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-2xl font-bold text-gray-900">Payment Transaction History</h2>
                        <p class="text-sm text-gray-500">Detailed logs of all processed payments</p>
                    </div>

                    <?php if (!empty($transactions)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4">ID</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Amount</th>
                                    <th class="px-6 py-4">Breakdown (Ins/Adm/Sav)</th>
                                    <th class="px-6 py-4">Timing</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                <?php foreach ($transactions as $txn): ?>
                                <tr class="hover:bg-gray-50/80 transition-colors">
                                    <td class="px-6 py-4 font-medium text-gray-900">#<?= $txn['transaction_id'] ?></td>
                                    <td class="px-6 py-4 text-gray-600">
                                        <div class="text-sm"><?= date("M d, Y", strtotime($txn['payment_date'])) ?></div>
                                        <div class="text-xs text-gray-400">Due: <?= $txn['next_due_date'] ? date("M d, Y", strtotime($txn['next_due_date'])) : 'N/A' ?></div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-base font-bold text-gray-900">₱<?= number_format($txn['amount_paid'], 2) ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded" title="Insurance">₱<?= number_format($txn['amount_paid'] * 0.10, 2) ?></span>
                                            <span class="text-xs bg-green-50 text-green-700 px-2 py-1 rounded" title="Admin">₱<?= number_format($txn['amount_paid'] * 0.05, 2) ?></span>
                                            <span class="text-xs bg-purple-50 text-purple-700 px-2 py-1 rounded" title="Savings">₱<?= number_format($txn['amount_paid'] * 0.85, 2) ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold <?= $txn['payment_timing_status'] === 'On-Time' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' ?>">
                                            <?= strtoupper($txn['payment_timing_status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="h-2.5 w-2.5 rounded-full <?= $txn['status'] === 'Paid' ? 'bg-green-500' : 'bg-red-500' ?> me-2"></div>
                                            <span class="font-medium"><?= htmlspecialchars($txn['status']) ?></span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="p-12 text-center">
                        <i class="fas fa-receipt text-gray-200 text-5xl mb-4"></i>
                        <p class="text-gray-500">No payment transactions found for this applicant.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>