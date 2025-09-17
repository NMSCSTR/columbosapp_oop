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

// Debug (remove in production)
// echo "<pre>"; print_r($transactionHistory); echo "</pre>";
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/memberSideBar.php'?>
    <!-- Main Content -->
    <main class="flex-1 w-full">
        <div class="p-4">
            <h2 class="text-2xl font-bold mb-4">Transaction History</h2>
            
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
                                    <td class="px-6 py-4"><?php echo number_format($transaction['amount_paid'], 2); ?></td>
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