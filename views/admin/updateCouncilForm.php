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
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Update
                                Council</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="p-4 rounded-lg dark:border-gray-700">
                <!-- Responsive grid layout -->
                <form method="POST" action="../../controllers/adminController/updateCouncilController.php">
                    <!-- Hidden input to pass the council_id -->
                    <input type="hidden" name="council_id"
                        value="<?= htmlspecialchars($council['council_id'] ?? '') ?>">

                    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0 mt-12">
                        <div class="w-full bg-white rounded-lg shadow border md:mt-0 sm:max-w-3xl xl:p-0">
                            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                                <h1
                                    class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                                    Update council information
                                </h1>

                                <!-- Council Number -->
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-900">
                                        Council number
                                    </label>
                                    <input type="text" name="council_number" id="council_number"
                                        value="<?= htmlspecialchars($council['council_number'] ?? '') ?>"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Input council number..." required>
                                </div>

                                <!-- Council Name -->
                                <div>
                                    <label for="council_name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Council
                                        Name</label>
                                    <input type="text" name="council_name" id="council_name"
                                        value="<?= htmlspecialchars($council['council_name'] ?? '') ?>"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Input council name..." required>
                                </div>

                                <!-- Unit Manager -->
                                <div>
                                    <label for="unit_manager_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Unit
                                        Manager</label>
                                    <select name="unit_manager_id" id="unit_manager_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled>Select Unit Manager</option>
                                        <?php while ($row = mysqli_fetch_assoc($unitManagers)) : ?>
                                        <option value="<?= $row['id'] ?>"
                                            <?= ($row['id'] == $council['unit_manager_id']) ? 'selected' : '' ?>>
                                            <?= $row['firstname'] . ' ' . $row['lastname'] ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- Fraternal Counselor -->
                                <div>
                                    <label for="fraternal_counselor_id"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Fraternal
                                        Counselor</label>
                                    <select name="fraternal_counselor_id" id="fraternal_counselor_id"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled>Select Fraternal Counselor</option>
                                        <?php while ($row = mysqli_fetch_assoc($fraternalCounselors)) : ?>
                                        <option value="<?= $row['id'] ?>"
                                            <?= ($row['id'] == $council['fraternal_counselor_id']) ? 'selected' : '' ?>>
                                            <?= $row['firstname'] . ' ' . $row['lastname'] ?>
                                        </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <!-- Date Established -->
                                <div>
                                    <label for="date_established"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                        Established</label>
                                    <input type="datetime-local" name="date_established" id="date_established"
                                        value="<?= isset($council['date_established']) ? date('Y-m-d\TH:i', strtotime($council['date_established'])) : '' ?>"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        required>
                                </div>

                                <!-- Submit Button -->
                                <button
                                    class="w-full bg-blue-500 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center focus:ring-blue-800 text-white"
                                    type="submit">
                                    Update
                                </button>
                            </div>
                        </div>
                    </div>
                </form>


            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>