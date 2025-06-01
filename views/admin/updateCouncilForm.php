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

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="<?php echo BASE_URL?>views/admin/dashboard.php"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-green-600 transition-colors duration-200">
                            <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
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
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-green-600 transition-colors duration-200 md:ms-2">Council Management</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Update Council</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <section class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
                <div class="p-8 border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Update Council Information</h2>
                            <p class="mt-1 text-sm text-gray-600">Modify the details of the council below.</p>
                        </div>
                    </div>
                </div>

                <form class="p-8" method="POST" action="../../controllers/adminController/updateCouncilController.php">
                    <input type="hidden" name="council_id" value="<?= htmlspecialchars($council['council_id'] ?? '') ?>">

                    <div class="grid gap-8 mb-8 md:grid-cols-2">
                        <div class="space-y-2">
                            <label for="council_number" class="block text-sm font-medium text-gray-700">
                                Council Number
                            </label>
                            <input type="text" name="council_number" id="council_number"
                                value="<?= htmlspecialchars($council['council_number'] ?? '') ?>"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                placeholder="Enter council number" required>
                        </div>

                        <div class="space-y-2">
                            <label for="council_name" class="block text-sm font-medium text-gray-700">
                                Council Name
                            </label>
                            <input type="text" name="council_name" id="council_name"
                                value="<?= htmlspecialchars($council['council_name'] ?? '') ?>"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                placeholder="Enter council name" required>
                        </div>

                        <div class="space-y-2">
                            <label for="unit_manager_id" class="block text-sm font-medium text-gray-700">
                                Unit Manager
                            </label>
                            <div class="relative">
                                <select name="unit_manager_id" id="unit_manager_id"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out appearance-none">
                                    <option selected disabled value="">Select Unit Manager</option>
                                    <?php mysqli_data_seek($unitManagers, 0);
                                    while ($row = mysqli_fetch_assoc($unitManagers)) : ?>
                                    <option value="<?= $row['id'] ?>"
                                        <?= (isset($council['unit_manager_id']) && $row['id'] == $council['unit_manager_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="fraternal_counselor_id" class="block text-sm font-medium text-gray-700">
                                Fraternal Counselor
                            </label>
                            <div class="relative">
                                <select name="fraternal_counselor_id" id="fraternal_counselor_id"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out appearance-none">
                                    <option selected disabled value="">Select Fraternal Counselor</option>
                                    <?php mysqli_data_seek($fraternalCounselors, 0);
                                    while ($row = mysqli_fetch_assoc($fraternalCounselors)) : ?>
                                    <option value="<?= $row['id'] ?>"
                                        <?= (isset($council['fraternal_counselor_id']) && $row['id'] == $council['fraternal_counselor_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['firstname'] . ' ' . $row['lastname']) ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="md:col-span-2 space-y-2">
                            <label for="date_established" class="block text-sm font-medium text-gray-700">
                                Date Established
                            </label>
                            <input type="date" name="date_established" id="date_established"
                                value="<?= isset($council['date_established']) ? date('Y-m-d', strtotime($council['date_established'])) : '' ?>"
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-150 ease-in-out"
                                required>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="<?php echo BASE_URL?>views/admin/council.php"
                            class="px-6 py-3 rounded-lg text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-3 rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors duration-200 inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Council
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>