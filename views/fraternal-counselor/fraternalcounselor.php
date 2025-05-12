<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'fraternal-counselor']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/adminModel/memberApplicationModel.php';

$applicationModel = new MemberApplicationModel($conn);
$applicantData = $applicationModel->fetchAllApplicantById($_SESSION['user_id']);


?>
<?php include '../../partials/fcsidebar.php' ?>
<!-- Main Content -->
<main class="flex-1 p-4 md:p-6 overflow-y-auto">
    <!-- Mobile Menu Toggle -->
    <div class="md:hidden flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-gray-800">COLUMBOS</h1>
        <button @click="openSidebar = !openSidebar" class="p-2 rounded-full hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>

    <!-- Dashboard Section -->
    <div x-show="activeSection === 'dashboard'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">Welcome Back,  <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?>!</h1>
            <p class="text-gray-600">Here's what's happening with your account today.</p>
        </header>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Applicant</h2>
                <p class="text-2xl font-bold text-green-600">12</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Pending applications</h2>
                <p class="text-2xl font-bold text-yellow-600">3</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Allocations</h2>
                <p class="text-2xl font-bold text-blue-600">$500.00</p>
            </div>
        </div>
    </div>

    <!-- Orders Section -->
    <div x-show="activeSection === 'orders'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">List of applicant</h1>
            <p class="text-gray-600">View and manage your applicants.</p>
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
        <table id="myTable" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                                <!-- <th class="px-4 py-3">Id</th> -->
                                <th class="px-4 py-3">Council Number</th>
                                <th class="px-4 py-3">Plan</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">UNIT MANAGER</th>
                                <th class="px-4 py-3">FRATERNAL COUNSELOR</th>
                                <th class="px-4 py-3">Council Established</th>
                                <!-- <th class="px-4 py-3">Date Created</th> -->
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            <?php
                            $councilModel = new CouncilModel($conn);
                            $councils     = $councilModel->getAllCouncil();

                            if ($councils) {
                                foreach ($councils as $council) {
                                    $um_name = $councilModel->getUserNameById($council['unit_manager_id'], 'unit-manager');
                                    $fc_name = $councilModel->getUserNameById($council['fraternal_counselor_id'], 'fraternal-counselor'); ?>

                            <tr class='border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'>
                                <!-- <td class='px-4 py-3'><?php echo $council['council_id'] ?></td> -->
                                <td class='px-4 py-3'><?php echo $council['council_number'] ?></td>
                                <td class='px-4 py-3'><?php echo $council['council_name'] ?></td>
                                <td class='px-4 py-3'><?php echo $um_name ?></td>
                                <td class='px-4 py-3'><?php echo $fc_name ?></td>
                                <td class='px-4 py-3'>
                                    <?php echo date("F j, Y", strtotime($council['date_established'])); ?>
                                </td>
                                <!-- <td class='px-4 py-3'><?php echo date("F j, Y", strtotime($council['date_created'])); ?>
                                        </td> -->
                                <td>
                                    <a href="#"
                                        class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-red-700 rounded-lg hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 delete-council"
                                        data-id="<?= $council['council_id'] ?>">
                                        <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M5 7h14m-9 3v8m4-8v8M10 3h4a1 1 0 0 1 1 1v3H9V4a1 1 0 0 1 1-1ZM6 7h12v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7Z" />
                                        </svg>
                                        Delete
                                    </a>
                                    <a href="updateCouncilForm.php?id=<?= $council['council_id'] ?>"
                                        class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z" />
                                        </svg>
                                        Update
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7' class='px-4 py-3 text-center'>No councils found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
        </div>
    </div>

    <!-- Profile Section -->
    <div x-show="activeSection === 'profile'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">Your Profile</h1>
            <p class="text-gray-600">Update your personal information and settings.</p>
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <form class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="John Doe">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="john.doe@example.com">
                </div>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</main>
<?php
include '../../includes/footer.php';
?>