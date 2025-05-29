<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/adminModel/announcementModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';

    $model = new announcementModel($conn);
    $announcements = $model->getAllAnnouncement();
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <?php
            $breadcrumbItems = [
                ['title' => 'Admin', 'url' => 'dashboard.php'],
                ['title' => 'Announcements']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>

            <div class="container mx-auto px-4 py-8">
                <!-- Two Column Layout -->
                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Left: Announcement List -->
                    <div class="lg:w-2/3 w-full max-h-[600px] overflow-y-auto space-y-6 pr-2">
                        <?php foreach ($announcements as $index => $announcement): ?>
                        <div class="flex items-start gap-3">
                            <div class="flex-1 p-4 bg-gray-100 rounded-xl dark:bg-gray-700 relative shadow-sm border">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">Admin</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-300">
                                            <?= date("M d, Y H:i", strtotime($announcement['date_posted'])) ?>
                                        </span>
                                    </div>
                                    <!-- Dropdown Button -->
                                    <button id="dropdownMenuIconButton<?= $index ?>"
                                        data-dropdown-toggle="dropdownDots<?= $index ?>"
                                        class="p-2 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none">
                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                            viewBox="0 0 4 15" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                        </svg>
                                    </button>
                                </div>

                                <p class="mt-3 text-sm text-gray-900 dark:text-white">
                                    <strong><?= htmlspecialchars($announcement['subject']) ?>:</strong>
                                    <?= nl2br(htmlspecialchars($announcement['content'])) ?>
                                </p>
                                <span class="block mt-2 text-sm text-gray-500 dark:text-gray-400">Delivered</span>

                                <!-- Dropdown Content -->
                                <div id="dropdownDots<?= $index ?>"
                                    class="z-10 hidden absolute top-10 right-0 bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-40 dark:bg-gray-700 dark:divide-gray-600">
                                    <ul class="py-2 text-sm text-gray-700 dark:text-gray-200"
                                        aria-labelledby="dropdownMenuIconButton<?= $index ?>">

                                        <li><a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white copy-announcement">Copy</a>
                                        </li>
                                        <li><a href="#"
                                                class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white delete-announcement" data-id="<?= $announcement['id'] ?>">Delete</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Right: Add Announcement Form -->
                    <div class="lg:w-1/3 w-full">
                        <form action="<?php echo BASE_URL ?>controllers/adminController/addAnnouncementController.php"
                            method="POST" class="bg-white border rounded-lg shadow-md p-6">
                            <h2 class="text-2xl font-semibold text-blue-900 mb-4">Add Announcement</h2>

                            <input type="text" name="subject" id="subject"
                                class="mb-4 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 w-full p-2.5"
                                placeholder="Add Subject" required>

                            <textarea name="announcement" id="announcement"
                                class="mb-2 h-40 px-3 py-2 text-sm text-gray-900 placeholder:text-sm border border-gray-300 rounded-lg w-full resize-none focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Add your announcement here" required></textarea>

                            <div class="flex justify-between items-center mt-2">
                                <p class="text-sm text-gray-600">Enter at least 30 characters</p>
                                <button type="submit"
                                    class="h-10 w-48 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition">
                                    SEND ANNOUNCEMENT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    </main>
</div>

<?php
include '../../includes/footer.php';
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../public/js/announcements.js"></script>
</div>