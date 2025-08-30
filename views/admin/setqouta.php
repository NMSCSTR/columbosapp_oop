<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/userModel.php';
    include '../../models/adminModel/setQoutaModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';

    if (!isset($_GET['userid'])) {
        die("User id is required.");
    }

    $user_id = intval($_GET['userid']);
    
    // Fetch user details
    $userModel = new userModel($conn);
    $user = $userModel->getUserById($user_id);
    
    if (!$user || $user['role'] !== 'unit-manager') {
        die("Invalid user or user is not a unit manager.");
    }
    
    // Check for existing quota and active quota
    $quotaModel = new setQoutaModel($conn);
    $existingQuota = $quotaModel->checkExistingQuota($user_id);
    $activeQuota = $quotaModel->hasActiveQuota($user_id);
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
                ['title' => 'Set Qouta']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>
            <section
                class="user-card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Set Quota</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Set quota for unit manager: <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-6">
                    <?php if ($activeQuota): ?>
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <h3 class="text-lg font-medium text-red-900 mb-2">⚠️ Active Quota in Progress</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-red-700">Quota Amount:</span>
                                <span class="text-red-900"><?= number_format($activeQuota['qouta']) ?></span>
                            </div>
                            <div>
                                <span class="font-medium text-red-700">Current Amount:</span>
                                <span class="text-red-900"><?= number_format($activeQuota['current_amount']) ?></span>
                            </div>
                            <div>
                                <span class="font-medium text-red-700">Duration:</span>
                                <span class="text-red-900"><?= date('M d, Y', strtotime($activeQuota['duration'])) ?></span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-red-600 font-medium">Cannot set new quota while current quota is in progress. Please wait until the current quota is completed or expired.</p>
                    </div>
                    <?php elseif ($existingQuota): ?>
                    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-2">Previous Quota Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-blue-700">Quota Amount:</span>
                                <span class="text-blue-900"><?= number_format($existingQuota['qouta']) ?></span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-700">Current Amount:</span>
                                <span class="text-blue-900"><?= number_format($existingQuota['current_amount']) ?></span>
                            </div>
                            <div>
                                <span class="font-medium text-blue-700">Duration:</span>
                                <span class="text-blue-900"><?= date('M d, Y', strtotime($existingQuota['duration'])) ?></span>
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-blue-600">Previous quota has been completed or expired. You can now set a new quota.</p>
                    </div>
                    <?php endif; ?>
                    
                    <form action="../../controllers/adminController/setQoutaController.php" method="POST" class="space-y-6" <?= $activeQuota ? 'style="display: none;"' : '' ?>>
                        <input type="hidden" name="user_id" value="<?= $user_id ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Quota Amount -->
                            <div>
                                <label for="quota" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Quota Amount
                                </label>
                                <input type="number" 
                                       id="quota" 
                                       name="quota" 
                                       required 
                                       min="1"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                       placeholder="Enter quota amount">
                            </div>

                            <!-- Duration -->
                            <div>
                                <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Duration (End Date)
                                </label>
                                <input type="date" 
                                       id="duration" 
                                       name="duration" 
                                       required 
                                       min="<?= date('Y-m-d') ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>

                        <!-- Current Amount (Optional) -->
                        <div>
                            <label for="current_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Current Amount (Optional)
                            </label>
                            <input type="number" 
                                   id="current_amount" 
                                   name="current_amount" 
                                   min="0"
                                   value="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                   placeholder="Enter current amount (default: 0)">
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Leave as 0 if starting fresh</p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end space-x-4">
                            <a href="users.php" 
                               class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:border-gray-500 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200">
                                Set Quota
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
</div>