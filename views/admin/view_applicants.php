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

    // Accessing the first result from the search session
    $c = $searchResults[0]['basicInfo'] ?? null;
    $d = $searchResults[0]['fullDetails'] ?? null;

    // Plan-specific shortcuts for cleaner code
    $planInfo = $d['plans'] ?? null;
    $applicant = $d['applicantData'] ?? null;

    // AUTOMATE PREMIUM CALCULATION
    // This calls the logic we built to check age and benefit rates
    $suggestedPremium = 0;
    if (isset($applicant['applicant_id'])) {
        $suggestedPremium = $transactionModel->calculatePremium($applicant['applicant_id']);
    }
?>

<div id="authentication-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-900/30">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-plus-circle mr-2 text-blue-600"></i> New Premium Payment
                </h3>
                <button type="button" class="text-gray-400 hover:text-red-600 transition-colors" data-modal-hide="authentication-modal">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></button>
            </div>
            <div class="p-6">
                <form class="space-y-4" action="../../controllers/adminController/transactionController.php" method="POST">
                    <input type="hidden" value="<?= $applicant['applicant_id'] ?? ''; ?>" name="applicant_id">
                    <input type="hidden" value="<?= $c['user_id'] ?? ''; ?>" name="user_id">
                    <input type="hidden" value="<?= $planInfo['plan_id'] ?? '' ?>" name="plan_id">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Payment Date</label>
                            <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-blue-500 outline-none" required />
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Timing</label>
                            <select name="payment_timing_status" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="On-Time">On-Time</option>
                                <option value="Late">Late</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Amount Paid</label>
                            <input type="number" step="0.01" name="amount_paid" value="<?= $suggestedPremium ?>" class="bg-blue-50 border border-blue-200 text-sm font-bold text-blue-700 rounded-xl block w-full p-3 focus:ring-2 focus:ring-blue-500 outline-none" required />
                            <p class="text-[9px] text-blue-500 mt-1 font-semibold">* Suggested based on age/rate table</p>
                        </div>
                        <div>
                            <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Currency</label>
                            <input type="text" value="<?= $planInfo['currency'] ?? 'PHP' ?>" name="currency" class="bg-gray-100 border border-gray-200 text-gray-400 text-sm rounded-xl block w-full p-3" readonly />
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 text-xs font-bold text-gray-500 uppercase">Remarks</label>
                        <textarea name="remarks" rows="2" placeholder="Add ledger notes..." class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                    </div>

                    <button type="submit" name="submit_transaction" class="w-full text-white bg-blue-600 hover:bg-blue-700 font-bold rounded-xl py-3.5 shadow-lg transition-all">LOG TRANSACTION</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="edit-notebook-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full backdrop-blur-sm bg-gray-900/30">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white rounded-2xl shadow-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Edit Ledger Notes</h3>
            <form action="../../controllers/adminController/transactionController.php" method="POST">
                <input type="hidden" id="edit_txn_id" name="transaction_id">
                <textarea id="edit_remarks" name="remarks" rows="4" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3 focus:ring-2 focus:ring-blue-500 outline-none mb-4"></textarea>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeNotebookModal()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200">Cancel</button>
                    <button type="submit" name="update_notebook" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row min-h-screen bg-[#f8fafc] font-sans">
    <?php include '../../partials/sidebar.php' ?>

    <main class="flex-1 sm:ml-64 transition-all duration-300">
        <div class="p-6 md:p-10 max-w-7xl mx-auto">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
                <div>
                    <span class="text-blue-600 font-bold text-xs uppercase tracking-[0.2em]">Financial Ledger</span>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Premium Management</h1>
                </div>
                <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" class="bg-slate-900 text-white px-6 py-3 rounded-xl font-bold shadow-xl hover:bg-black transition-all flex items-center text-sm">
                    <i class="fas fa-plus mr-2 text-blue-400"></i> NEW PAYMENT
                </button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                <div class="lg:col-span-1 bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-center">
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
                            <i class="fas fa-user-tie text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Full Name</p>
                            <h2 class="text-lg font-bold text-slate-800"><?= htmlspecialchars($c['firstname'] . ' ' . $c['lastname']) ?></h2>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 border-t border-slate-50 pt-4">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Applicant ID / Age</p>
                            <?php 
                                $birthDate = new DateTime($applicant['birthdate']);
                                $realAge = (new DateTime())->diff($birthDate)->y;
                            ?>
                            <p class="text-sm font-semibold text-slate-600">
                                #<?= htmlspecialchars($applicant['applicant_id'] ?? 'N/A') ?> — <?= $realAge ?> yrs old
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email Address</p>
                            <p class="text-sm font-semibold text-slate-600"><?= htmlspecialchars($c['email'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-slate-900 p-8 rounded-3xl shadow-xl relative overflow-hidden group">
                    <i class="fas fa-shield-alt absolute -right-6 -bottom-6 text-white/5 text-9xl group-hover:scale-110 transition-transform"></i>
                    
                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-1">Active Plan Details</p>
                                <h3 class="text-white text-2xl font-black"><?= htmlspecialchars($planInfo['plan_name'] ?? 'No Plan Selected') ?></h3>
                            </div>
                            <span class="px-3 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-full uppercase tracking-tighter">
                                <?= htmlspecialchars($applicant['application_status'] ?? 'Active') ?>
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-500 uppercase">Premium Due</p>
                                <p class="text-lg font-bold text-white"><?= $planInfo['currency'] ?? 'PHP' ?> <?= number_format($planInfo['contribution_amount'] ?? 0, 2) ?></p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-500 uppercase">Payment Mode</p>
                                <p class="text-lg font-bold text-white capitalize"><?= htmlspecialchars($planInfo['payment_mode'] ?? 'N/A') ?></p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-500 uppercase">Face Value</p>
                                <p class="text-lg font-bold text-blue-400">₱ <?= number_format($planInfo['face_value'] ?? 0, 0) ?></p>
                            </div>
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-500 uppercase">Protection</p>
                                <p class="text-lg font-bold text-white"><?= htmlspecialchars($planInfo['years_of_protection'] ?? '0') ?> Years</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center text-slate-400">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                <span class="text-xs font-medium">Maturity: <strong><?= $planInfo['years_to_maturity'] ?? '0' ?> Years</strong></span>
                            </div>
                            <div class="flex items-center text-slate-400">
                                <i class="fas fa-fingerprint mr-2 text-blue-500"></i>
                                <span class="text-xs font-medium">Type: <strong><?= htmlspecialchars($planInfo['type'] ?? 'N/A') ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center">
                        <i class="fas fa-history mr-2 text-slate-400"></i> Transaction History
                    </h3>
                </div>

                <?php if (!empty($transactions)): ?>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                <th class="px-8 py-4">ID</th>
                                <th class="px-8 py-4">Payment Date</th>
                                <th class="px-8 py-4 text-right">Amount Paid</th>
                                <th class="px-8 py-4">Next Due Date</th>
                                <th class="px-8 py-4">Ledger Notes</th>
                                <th class="px-8 py-4">Timing</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($transactions as $txn): ?>
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5 font-mono text-[11px] text-slate-400">#<?= $txn['transaction_id'] ?></td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-slate-700"><?= date("M d, Y", strtotime($txn['payment_date'])) ?></div>
                                    <div class="text-[9px] text-slate-400 font-medium">Logged: <?= date("M d, h:i A", strtotime($txn['created_at'])) ?></div>
                                </td>
                                <td class="px-8 py-5 text-right font-black text-slate-900 text-sm">
                                    ₱<?= number_format($txn['amount_paid'], 2) ?>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="text-sm font-bold text-blue-600">
                                        <?= $txn['next_due_date'] ? date("M d, Y", strtotime($txn['next_due_date'])) : 'N/A' ?>
                                    </div>
                                </td>
                                <td class="px-8 py-5 max-w-xs">
                                    <?php if (!empty($txn['remarks'])): ?>
                                        <div class="text-xs text-slate-500 italic leading-snug">
                                            "<?= htmlspecialchars($txn['remarks']) ?>"
                                            <button onclick="openNotebookModal('<?= $txn['transaction_id'] ?>', '<?= htmlspecialchars($txn['remarks'], ENT_QUOTES) ?>')" 
                                                    class="block mt-1 text-[9px] font-bold text-blue-500 uppercase tracking-tighter hover:underline">
                                                Edit
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <button onclick="openNotebookModal('<?= $txn['transaction_id'] ?>', '')" 
                                                class="text-[10px] font-bold text-slate-300 hover:text-blue-500 flex items-center transition-colors">
                                            <i class="fas fa-pen mr-1"></i> Add remarks
                                        </button>
                                    <?php endif; ?>
                                </td>
                                <td class="px-8 py-5">
                                    <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-tight <?= $txn['payment_timing_status'] === 'On-Time' ? 'bg-emerald-50 text-emerald-600' : 'bg-orange-50 text-orange-600' ?>">
                                        <?= $txn['payment_timing_status'] ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="p-16 text-center text-slate-300">
                    <i class="fas fa-folder-open text-4xl mb-4 opacity-20"></i>
                    <p class="text-xs font-bold uppercase tracking-widest">No transactions logged</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
    function openNotebookModal(txnId, currentRemarks) {
        document.getElementById('edit_txn_id').value = txnId;
        document.getElementById('edit_remarks').value = currentRemarks;
        const modal = document.getElementById('edit-notebook-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
    function closeNotebookModal() {
        const modal = document.getElementById('edit-notebook-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<?php include '../../includes/footer.php'; ?>