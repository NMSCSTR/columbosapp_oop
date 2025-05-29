<?php
require_once '../../middleware/auth.php';
authorize(['admin']);
include '../../includes/config.php';
include '../../includes/header.php';
include '../../includes/db.php';
include '../../models/adminModel/userModel.php';
include '../../includes/alert2.php';
include '../../partials/breadcrumb.php';

$userModel = new UserModel($conn);
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <?php
            $breadcrumbItems = [
                ['title' => 'Admin Dashboard']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <!-- Rest of the existing dashboard content -->
                // ... existing code ...
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?> 