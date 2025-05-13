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
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="#"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                </svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <a href="#"
                                    class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Admin</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 9 4-4-4-4" />
                                </svg>
                                <span
                                    class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Fraternal
                                    Benefits</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <div class="flex flex-col justify-center items-center min-h-screen">
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-xl w-full">
                        <img src="<?php echo BASE_URL . $benefit['image']; ?>" alt="Plan Image"
                            class="w-full h-64 object-cover">

                        <div class="p-6">
                            <h2 class="text-2xl font-bold text-gray-800 mb-2">
                                <?php echo htmlspecialchars($benefit['name']); ?>
                            </h2>
                            <p class="text-gray-700 leading-tight mb-4">
                                <?php echo nl2br(htmlspecialchars($benefit['type'])); ?>
                            </p>

                            <div class="mb-2">
                                <strong>About:</strong>
                                <p class="text-gray-700 leading-tight mb-4">
                                    <?php echo htmlspecialchars($benefit['about']); ?>
                                </p>
                                <strong>Benefits:</strong>
                                <p class="text-gray-700 leading-tight mb-4">
                                    <?php echo htmlspecialchars($benefit['benefits']); ?>
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div class="bg-white p-4 rounded-lg shadow-md">
                                        <strong>Contribution Period:</strong>
                                        <p class="text-gray-700 leading-tight mb-4">
                                            <?php echo htmlspecialchars($benefit['contribution_period']); ?>
                                        </p>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-md">
                                        <strong>Face value:</strong>
                                        <p class="text-gray-700 leading-tight mb-4">
                                            PHP <?php echo number_format($benefit['face_value'],2); ?>
                                        </p>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-md">
                                        <strong>Years to maturity:</strong>
                                        <p class="text-gray-700 leading-tight mb-4">
                                            <?php echo $benefit['years_to_maturity']; ?>
                                        </p>
                                    </div>
                                    <div class="bg-white p-4 rounded-lg shadow-md">
                                        <strong>Years of protection:</strong>
                                        <p class="text-gray-700 leading-tight mb-4">
                                            <?php echo $benefit['years_of_protection']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-between mt-4">
                                <a href="fraternalBenefits.php" class="text-blue-600 hover:underline">← Back to list</a>
                                <a href="updateplanform.php?id=<?php echo $benefit['id']; ?>"
                                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                    Update
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>