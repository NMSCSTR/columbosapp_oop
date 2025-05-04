<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../models/adminModel/userModel.php';
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">

            <h1 class="flex items-center text-5xl font-extrabold dark:text-white">Welcome <?php echo $_SESSION['firstname'] .' '. $_SESSION['lastname'] ?></h1>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <!-- Responsive grid layout -->
                <?php include '../../partials/content.php'?>
            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>