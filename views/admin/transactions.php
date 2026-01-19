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

<style>
    /* DataTables Custom UI Overrides */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 8px 16px;
        background-color: #ffffff;
        outline: none;
        transition: all 0.2s;
    }
    .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    table.dataTable.no-footer { border-bottom: 1px solid #e2e8f0 !important; }
    .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate {
        font-size: 0.875rem;
        color: #64748b !important;
        margin-top: 20px;
    }
</style>

<div class="flex flex-col md:flex-row min-h-screen bg-[#f8fafc]">
    <?php include '../../partials/sidebar.php' ?>

    <main class="flex-1 p-6 sm:ml-64">
        <div class="max-w-12xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Financial Transactions</h1>
                    <p class="text-slate-500 mt-1 font-medium">Monitor and manage applicant plan payments and ledgers.</p>
                </div>
                <div class="flex items-center space-x-2 text-sm font-semibold text-slate-600 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                    <span class="flex h-2 w-2 rounded-full bg-emerald-500"></span>
                    <span>Total Records: <?= count($allApplicantPlans) ?></span>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-6">
                    <table id="plansTable" class="display responsive nowrap w-full !border-none">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Applicant Details</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Plan Information</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Payment Mode</th>
                                <th class="px-6 py-4 text-left text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Contribution</th>
                                <th class="px-6 py-4 text-right text-[11px] font-bold text-slate-400 uppercase tracking-[0.1em]">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php foreach ($allApplicantPlans as $row): ?>
                            <tr class="group hover:bg-slate-50/80 transition-all duration-200">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800"><?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?></span>
                                        <span class="text-[11px] font-mono text-slate-400">ID: #<?= $row['plan_id'] ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                        <i class="fas fa-shield-alt mr-1.5 opacity-70"></i>
                                        <?= htmlspecialchars($row['plan_name']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-semibold text-slate-600 px-2 py-1 bg-slate-100 rounded-md capitalize">
                                        <?= htmlspecialchars($row['payment_mode']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black text-slate-900">₱<?= number_format($row['contribution_amount'], 2) ?></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="<?= BASE_URL ?>controllers/adminController/searchController.php?plan_id=<?= $row['plan_id'] ?>&user_id=<?= $row['user_id'] ?>" 
                                       class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 px-4 py-2 rounded-xl text-xs font-bold hover:bg-slate-900 hover:text-white hover:border-slate-900 transition-all duration-200 shadow-sm">
                                        View Ledger
                                        <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="9 5l7 7-7 7"></path></svg>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
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
            dom: '<"flex flex-col sm:flex-row justify-between items-center mb-6 gap-4"f>rt<"flex flex-col sm:flex-row justify-between items-center mt-6 gap-4"ip>',
            language: {
                search: "",
                searchPlaceholder: "Search applicants, plans, or IDs...",
                paginate: {
                    previous: "<i class='fas fa-chevron-left'></i>",
                    next: "<i class='fas fa-chevron-right'></i>"
                }
            }
        });
    });
</script>

<?php include '../../includes/footer.php'; ?>