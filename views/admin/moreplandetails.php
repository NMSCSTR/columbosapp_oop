<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';


    if (!isset($_GET['id'])) {
        die("ID is required.");
    }
    
    $id = intval($_GET['id']);
    $model = new fraternalBenefitsModel($conn);
    $benefit = $model->getFraternalBenefitById($id);
    
    if (!$benefit) {
        die("No plan found.");
    }
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <div class="p-4 rounded-lg dark:border-gray-700">
                <!-- Breadcrumb -->
                <nav class="flex mb-8" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="<?php echo BASE_URL?>views/admin/dashboard.php"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-4 h-4 me-2.5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <a href="fraternalbenefits.php"
                                    class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Fraternal Benefits</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Plan Details</span>
                            </div>
                        </li>
                    </ol>
                </nav>

                <!-- Main Content Container -->
                <div class="max-w-4xl mx-auto">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                        <!-- Plan Header with Image -->
                        <div class="relative h-80">
                            <img src="<?php echo BASE_URL . $benefit['image']; ?>" alt="<?php echo htmlspecialchars($benefit['name']); ?>"
                                class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600 text-white mb-3">
                                    <?php echo htmlspecialchars($benefit['type']); ?>
                                </span>
                                <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($benefit['name']); ?></h1>
                            </div>
                        </div>

                        <!-- Plan Details -->
                        <div class="p-6 space-y-6">
                            <!-- About Section -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">About the Plan</h2>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                    <?php echo nl2br(htmlspecialchars($benefit['about'])); ?>
                                </p>
                            </div>

                            <!-- Benefits Section -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-3">Plan Benefits</h2>
                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                    <?php echo nl2br(htmlspecialchars($benefit['benefits'])); ?>
                                </p>
                            </div>

                            <!-- Key Details Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                <!-- Face Value -->
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Face Value</div>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                        PHP <?php echo number_format($benefit['face_value'], 2); ?>
                                    </div>
                                </div>

                                <!-- Contribution Period -->
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Contribution Period</div>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($benefit['contribution_period']); ?> years
                                    </div>
                                </div>

                                <!-- Years to Maturity -->
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Years to Maturity</div>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <?php echo $benefit['years_to_maturity']; ?> years
                                    </div>
                                </div>

                                <!-- Years of Protection -->
                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                    <div class="text-sm text-gray-500 dark:text-gray-400 mb-1">Years of Protection</div>
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white">
                                        <?php echo $benefit['years_of_protection']; ?> years
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-200 dark:border-gray-600">
                                <a href="fraternalbenefits.php" 
                                   class="inline-flex items-center text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-500 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                    </svg>
                                    Back to list
                                </a>
                                <a href="updateplanform.php?id=<?php echo $benefit['id']; ?>"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Update Plan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>