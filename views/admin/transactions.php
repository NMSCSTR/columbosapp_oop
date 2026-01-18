<?php
    session_start();
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/TransactionModel.php';

    $transactionModel = new TransactionModel($conn);
    $allApplicantPlans = $transactionModel->fetchAllApplicantsWithPlans();
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<div class="flex flex-col md:flex-row min-h-screen bg-[#f8fafc]">
    <?php include '../../partials/sidebar.php' ?>

    <main class="flex-1 p-4 sm:ml-64">
        <div class="p-8 bg-white rounded-3xl shadow-sm border border-slate-200">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Financial Transactions</h1>
                <p class="text-slate-500 font-medium">Select a specific plan below to manage payments.</p>
            </div>

            <table id="plansTable" class="display responsive nowrap w-full">
                <thead>
                    <tr class="text-[11px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50">
                        <th class="px-4 py-3">Applicant Name</th>
                        <th class="px-4 py-3">Applied Plan</th>
                        <th class="px-4 py-3">Policy ID</th>
                        <th class="px-4 py-3">Payment Mode</th>
                        <th class="px-4 py-3">Contribution</th>
                        <th class="px-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($allApplicantPlans as $row): ?>
                    <tr class="hover:bg-blue-50/50 transition-colors">
                        <td class="px-4 py-4 font-bold text-slate-700">
                            <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                        </td>
                        <td class="px-4 py-4">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-[10px] font-black uppercase">
                                <?= htmlspecialchars($row['plan_name']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-4 font-mono text-xs text-slate-400">#<?= $row['plan_id'] ?></td>
                        <td class="px-4 py-4 text-sm text-slate-600 capitalize"><?= $row['payment_mode'] ?></td>
                        <td class="px-4 py-4 font-black text-slate-900">₱<?= number_format($row['contribution_amount'], 2) ?></td>
                        <td class="px-4 py-4">
                            <a href="<?= BASE_URL ?>controllers/adminController/searchController.php?plan_id=<?= $row['plan_id'] ?>&user_id=<?= $row['user_id'] ?>" 
                               class="inline-flex items-center justify-center bg-slate-900 text-white px-4 py-2 rounded-xl text-xs font-bold hover:bg-black transition-all">
                               VIEW LEDGER <i class="fas fa-chevron-right ml-2 text-[10px] text-blue-400"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
    $(document).ready(function() {
        $('#plansTable').DataTable({
            responsive: true,
            pageLength: 10,
            dom: '<"flex justify-between mb-4"f>rt<"flex justify-between mt-4"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search applicant or plan..."
            }
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>