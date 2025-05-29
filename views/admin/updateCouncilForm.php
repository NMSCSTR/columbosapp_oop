<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/adminModel/councilModel.php';

    $councilModel = new CouncilModel($conn);

    $council_id = $_GET['id'] ?? null;
    $council = null;
    
    if ($council_id) {
        $council = $councilModel->getCouncilById($council_id);
    }
    

    $unitManagers        = mysqli_query($conn, "SELECT id, firstname, lastname FROM users WHERE role = 'unit-manager'");
    $fraternalCounselors = mysqli_query($conn, "SELECT id, firstname, lastname FROM users WHERE role = 'fraternal-counselor'");

?>



<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
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
                            <a href="<?php echo BASE_URL?>views/admin/council.php"
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Council Management</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Update Council</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <section class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Update Council Information</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Modify the details of the council below.</p>
                </div>

                <form class="p-6" method="POST" action="../../controllers/adminController/updateCouncilController.php">
                    <!-- Hidden input to pass the council_id -->
                    <input type="hidden" name="council_id"
                        value="<?= htmlspecialchars($council['council_id'] ?? '') ?>">

                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="council_number" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                Council Number
                            </label>
                            <input type="text" name="council_number" id="council_number"
                                value="<?= htmlspecialchars($council['council_number'] ?? '') ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter council number" required>
                        </div>

                        <div>
                            <label for="council_name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Council
                                Name</label>
                            <input type="text" name="council_name" id="council_name"
                                value="<?= htmlspecialchars($council['council_name'] ?? '') ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                placeholder="Enter council name" required>
                        </div>

                        <div>
                            <label for="unit_manager_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Unit
                                Manager</label>
                            <select name="unit_manager_id" id="unit_manager_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected disabled value="">Select Unit Manager</option>
                                <?php mysqli_data_seek($unitManagers, 0); // Reset pointer if already iterated
                                 while ($row = mysqli_fetch_assoc($unitManagers)) : ?>
                                <option value="<?= $row['id'] ?>"
                                    <?= (isset($council['unit_manager_id']) && $row['id'] == $council['unit_manager_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div>
                            <label for="fraternal_counselor_id"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fraternal
                                Counselor</label>
                            <select name="fraternal_counselor_id" id="fraternal_counselor_id"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected disabled value="">Select Fraternal Counselor</option>
                                <?php mysqli_data_seek($fraternalCounselors, 0); // Reset pointer if already iterated
                                while ($row = mysqli_fetch_assoc($fraternalCounselors)) : ?>
                                <option value="<?= $row['id'] ?>"
                                    <?= (isset($council['fraternal_counselor_id']) && $row['id'] == $council['fraternal_counselor_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                                </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label for="date_established"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                Established</label>
                            <input type="date" name="date_established" id="date_established" placeholder="YYYY-MM-DD"
                                value="<?= isset($council['date_established']) ? date('Y-m-d', strtotime($council['date_established'])) : '' ?>"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="<?php echo BASE_URL?>views/admin/council.php" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition-colors duration-200">
                            <svg class="me-1.5 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10.219 6.219a1 1 0 00-1.414 0l-4 4a1 1 0 001.414 1.414L10 8.414l3.781 3.781a1 1 0 001.414-1.414l-4-4zM5 13.5a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"
                                    clip-rule="evenodd" transform="rotate(90 10 10)"></path> <!-- Placeholder icon, update if needed -->
                            </svg>
                            Update Council
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>