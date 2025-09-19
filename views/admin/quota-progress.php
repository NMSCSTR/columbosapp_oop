<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/setQoutaModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';

    // Fetch all unit managers with their quota information
    $quotaModel = new setQoutaModel($conn);
    $unitManagers = $quotaModel->getAllFraternalCounselorWithQuota();
?>

<script>
    const BASE_URL = '<?php echo rtrim(dirname(dirname(dirname($_SERVER['PHP_SELF']))), '/') . '/'; ?>';
</script>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <?php
            $breadcrumbItems = [
                ['title' => 'Admin', 'url' => 'dashboard.php'],
                ['title' => 'Quota Progress']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>
            
            <section class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fraternal Counselor Quota Progress</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Monitor quota progress for all fraternal counselor</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="users.php" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors duration-200">
                                Back to Users
                            </a>
                        </div>
                    </div>
                </div>

                <!-- DataTable -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table id="quotaProgressTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Unit Manager</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Quota Amount</th>
                                    <th scope="col" class="px-6 py-3">Current Amount</th>
                                    <th scope="col" class="px-6 py-3">Progress</th>
                                    <th scope="col" class="px-6 py-3">Quota Status</th>
                                    <th scope="col" class="px-6 py-3">Duration</th>
                                    <th scope="col" class="px-6 py-3">Date Created</th>
                                    <th scope="col" class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($unitManagers as $manager): ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div class="font-medium"><?= htmlspecialchars($manager['firstname'] . ' ' . $manager['lastname']) ?></div>
                                            <div class="text-sm text-gray-500"><?= htmlspecialchars($manager['email']) ?></div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($manager['status'] === 'approved'): ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300">
                                                Approved
                                            </span>
                                        <?php elseif ($manager['status'] === 'pending'): ?>
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-yellow-900 dark:text-yellow-300">
                                                Pending
                                            </span>
                                        <?php else: ?>
                                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300">
                                                Disabled
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($manager['quota_id']): ?>
                                            <span class="font-medium"><?= number_format($manager['qouta']) ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400">No quota set</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($manager['quota_id']): ?>
                                            <span class="font-medium"><?= number_format($manager['current_amount']) ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($manager['quota_id']): ?>
                                            <div class="flex items-center">
                                                <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                                    <?php 
                                                    $progress = $manager['progress_percentage'];
                                                    $colorClass = $progress >= 100 ? 'bg-green-600' : ($progress >= 70 ? 'bg-yellow-500' : 'bg-blue-600');
                                                    ?>
                                                    <div class="<?= $colorClass ?> h-2 rounded-full" style="width: <?= min(100, $progress) ?>%"></div>
                                                </div>
                                                <span class="text-sm font-medium"><?= $progress ?>%</span>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($manager['quota_id']): ?>
                                            <?php 
                                            $status = $manager['quota_status'];
                                            $statusClass = $status === 'completed' ? 'bg-green-100 text-green-800' : 
                                                         ($status === 'expired' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');
                                            $statusLabel = ucfirst($status);
                                            ?>
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full <?= $statusClass ?>">
                                                <?= $statusLabel ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-400">No quota</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($manager['quota_id']): ?>
                                            <span class="text-sm"><?= date('M d, Y', strtotime($manager['duration'])) ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($manager['quota_id']): ?>
                                            <span class="text-sm"><?= date('M d, Y', strtotime($manager['date_created'])) ?></span>
                                        <?php else: ?>
                                            <span class="text-gray-400">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex space-x-2">
                                            <?php if ($manager['status'] === 'approved'): ?>
                                                <a href="setqouta.php?userid=<?= $manager['id'] ?>" 
                                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                    </svg>
                                                    Set Quota
                                                </a>
                                            <?php else: ?>
                                                <button disabled class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed opacity-50">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                                    </svg>
                                                    Set Quota
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

<script>
$(document).ready(function() {
    $('#quotaProgressTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'asc']],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'action-button bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-excel mr-2"></i>Export to Excel',
                title: 'Unit Manager Quota Progress - ' + new Date().toLocaleDateString()
            },
            {
                extend: 'pdf',
                className: 'action-button bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-pdf mr-2"></i>Export to PDF',
                title: 'Unit Manager Quota Progress - ' + new Date().toLocaleDateString()
            }
        ],
        columnDefs: [
            {
                targets: [1, 5], // Status and Quota Status columns
                orderable: true,
                searchable: true
            },
            {
                targets: [4], // Progress column
                orderable: true,
                searchable: false
            }
        ]
    });
});
</script> 