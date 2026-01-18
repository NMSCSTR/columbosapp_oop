<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Calculated Premium</p>
        <h3 class="text-2xl font-black text-blue-600">
            ₱<?= number_format($suggestedPremium, 2) ?>
        </h3>
        <p class="text-[10px] text-slate-400 mt-2 italic">* Based on age <?= $realAge ?> and <?= $planInfo['plan_name'] ?> rates</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Face Value</p>
        <h3 class="text-2xl font-black text-slate-800">
            ₱<?= number_format($planInfo['face_value'], 2) ?>
        </h3>
        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase"><?= $planInfo['years_of_protection'] ?> Years Protection</p>
    </div>

    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Current Standing</p>
        <?php 
            // Get status based on the latest transaction's next due date
            $latestTxn = !empty($transactions) ? $transactions[0] : null;
            $currentStatus = $transactionModel->getApplicantStatus($latestTxn['next_due_date'] ?? null);
            $statusColor = ($currentStatus == 'Active') ? 'text-emerald-600' : 'text-red-500';
        ?>
        <h3 class="text-2xl font-black <?= $statusColor ?>"><?= $currentStatus ?></h3>
        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase">Next Due: <?= $latestTxn ? date("M d, Y", strtotime($latestTxn['next_due_date'])) : 'No Payment Yet' ?></p>
    </div>
</div>

<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden mb-10">
    <div class="px-8 py-5 border-b border-slate-50">
        <h3 class="font-bold text-slate-800 flex items-center">
            <i class="fas fa-history mr-2 text-slate-400"></i> Payment History for this Plan
        </h3>
    </div>

    <?php if (!empty($transactions)): ?>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-[10px] font-bold text-slate-400 uppercase tracking-widest border-b border-slate-50">
                    <th class="px-8 py-4">Ref #</th>
                    <th class="px-8 py-4">Date Paid</th>
                    <th class="px-8 py-4 text-right">Amount</th>
                    <th class="px-8 py-4">Next Due</th>
                    <th class="px-8 py-4">Notes</th>
                    <th class="px-8 py-4 text-center">Status</th>
                    <th class="px-8 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php foreach ($transactions as $txn): ?>
                <tr class="hover:bg-slate-50/50 transition-colors group">
                    <td class="px-8 py-5 font-mono text-[11px] text-slate-400">#<?= $txn['transaction_id'] ?></td>
                    <td class="px-8 py-5">
                        <div class="text-sm font-bold text-slate-700"><?= date("M d, Y", strtotime($txn['payment_date'])) ?></div>
                    </td>
                    <td class="px-8 py-5 text-right font-black text-slate-900 text-sm">₱<?= number_format($txn['amount_paid'], 2) ?></td>
                    <td class="px-8 py-5 text-sm font-bold text-blue-600"><?= date("M d, Y", strtotime($txn['next_due_date'])) ?></td>
                    <td class="px-8 py-5 text-xs text-slate-400 italic max-w-xs truncate"><?= htmlspecialchars($txn['remarks']) ?></td>
                    <td class="px-8 py-5">
                        <div class="flex flex-col gap-1 items-center">
                            <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase bg-slate-100 text-slate-600"><?= $txn['payment_timing_status'] ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-right">
                        <button onclick="openReceipt('<?= $txn['transaction_id'] ?>', '<?= date('M d, Y', strtotime($txn['payment_date'])) ?>', '₱<?= number_format($txn['amount_paid'], 2) ?>', '<?= date('M d, Y', strtotime($txn['next_due_date'])) ?>')" 
                                class="text-slate-400 hover:text-blue-600 transition-colors">
                            <i class="fas fa-print"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="p-20 text-center">
        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-200 mx-auto mb-4">
            <i class="fas fa-receipt text-2xl"></i>
        </div>
        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">No payments recorded for this plan.</p>
    </div>
    <?php endif; ?>
</div>

<div id="authentication-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full backdrop-blur-md bg-slate-900/40">
    <div class="relative p-4 w-full max-w-md">
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden">
            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Log Payment</h3>
                        <p class="text-xs font-bold text-blue-600 uppercase tracking-widest"><?= $planInfo['plan_name'] ?></p>
                    </div>
                    <button type="button" data-modal-hide="authentication-modal" class="text-slate-300 hover:text-red-500"><i class="fas fa-times text-xl"></i></button>
                </div>

                <form class="space-y-6" action="../../controllers/adminController/transactionController.php" method="POST">
                    <input type="hidden" value="<?= $selected_user_id ?>" name="user_id">
                    <input type="hidden" value="<?= $selected_plan_id ?>" name="plan_id">
                    <input type="hidden" value="<?= $applicant['applicant_id'] ?? '' ?>" name="applicant_id">

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Payment Date</label>
                            <input type="date" name="payment_date" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/20 transition-all" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Timing</label>
                            <select name="payment_timing_status" class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl text-sm font-bold outline-none ring-2 ring-transparent focus:ring-blue-500/20 transition-all">
                                <option value="On-Time">On-Time</option>
                                <option value="Late">Late</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Amount Paid (PHP)</label>
                        <input type="number" step="0.01" name="amount_paid" value="<?= $suggestedPremium ?>" class="w-full px-5 py-4 bg-blue-50 border-0 rounded-2xl text-xl font-black text-blue-700 outline-none ring-2 ring-blue-100 focus:ring-blue-500/20 transition-all" required>
                        <p class="text-[9px] text-blue-500 font-bold uppercase tracking-tighter ml-1">* Suggested based on plan rates</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ledger Remarks</label>
                        <textarea name="remarks" rows="2" placeholder="Bank ref, check number, etc." class="w-full px-4 py-3 bg-slate-50 border-0 rounded-2xl text-sm outline-none ring-2 ring-transparent focus:ring-blue-500/20 transition-all resize-none"></textarea>
                    </div>

                    <button type="submit" name="submit_transaction" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-black transition-all">
                        Confirm Payment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>