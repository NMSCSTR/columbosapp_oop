<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'fraternal-counselor']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/memberModel/memberApplicationModel.php';

$councilModel = new CouncilModel($conn);
$fraternalBenefitsModel = new fraternalBenefitsModel($conn);
$applicationModel = new MemberApplicationModel($conn);
$applicantData = $applicationModel->getApplicantByFraternalCounselor($_SESSION['user_id']);
$totalApplicants = $applicationModel->countAllApplicants($_SESSION['user_id']);
$fetchFraternalBenefits = $fraternalBenefitsModel->getFraternalBenefitById($applicantData['fraternal_benefits_id']);
$fetchCouncil = $councilModel->getCouncilById($applicantData['council_id']);
var_dump($applicantData);

?>


<?php include '../../partials/fcsidebar.php' ?>

<!-- Modal -->
<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden justify-center items-center">
    <div class="bg-white w-full max-w-3xl rounded-lg shadow-lg p-6 overflow-y-auto max-h-[80vh]">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold">Applicant Information</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 text-xl font-bold">&times;</button>
        </div>
        <div id="modalContent" class="text-sm space-y-2">
            <!-- Injected content from JavaScript -->
        </div>
    </div>
</div>

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
            <h1 class="text-2xl font-bold text-gray-800">Welcome Back,
                <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?>!</h1>
            <p class="text-gray-600">Here's what's happening with your account today.</p>
        </header>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Applicant</h2>
                <p class="text-2xl font-bold text-green-600"><?php echo $totalApplicants ?></p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Pending applications</h2>
                <p class="text-2xl font-bold text-yellow-600">3</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Allocations</h2>
                <p class="text-2xl font-bold text-blue-600"><?php echo $fetchFraternalBenefits['type']?></p>
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
                        <th class="px-4 py-3">Council Name</th>
                        <th class="px-4 py-3">Plan type</th>
                        <th class="px-4 py-3">Plan name</th>
                        <th class="px-4 py-3">Applicant name</th>
                        <th class="px-4 py-3">Application Status</th>
                        <th class="px-4 py-3">Application date</th>
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
                        <td class='px-4 py-3'><?php echo $fetchCouncil["council_name"] ?></td>
                        <td class='px-4 py-3'><?php echo $fetchFraternalBenefits['type'] ?></td>
                        <td class='px-4 py-3'><?php echo $fetchFraternalBenefits['name'] ?></td>
                        <td class='px-4 py-3'>
                            <?php echo $applicantData['lastname'] . ' ' . $applicantData['firstname'] ?></td>
                        <td class='px-4 py-3'><?php echo $applicantData['application_status'] ?></td>
                        <td class='px-4 py-3'>
                            <?php echo date("F j, Y", strtotime($applicantData['created_at'])); ?>
                        </td>
                        <td>
                            <div class="flex gap-2 justify-center">
                                <a href="#"
                                    class="px-2 py-1 text-[10px] font-medium text-center inline-flex items-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 delete-application"
                                    data-id="<?= $applicantData['applicant_id'] ?>">
                                    <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                            clip-rule="evenodd" />
                                    </svg>

                                    Approved
                                </a>

                                <a href="javascript:void(0);"
    onclick='openModal(<?php echo json_encode($applicantData); ?>)'
    class="px-2 py-1 text-[10px] font-medium text-center inline-flex items-center text-white bg-blue-700 rounded-lg hover:bg-blue-800">
    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none"
        viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
    </svg>
    View
</a>


                            </div>
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
<script>
function openModal(data) {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('modalContent');

    let html = '';
    for (const key in data) {
        const label = key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        html += `
            <div class="flex justify-between border-b py-1">
                <span class="font-medium">${label}:</span>
                <span>${data[key]}</span>
            </div>`;
    }

    modalContent.innerHTML = html;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('viewModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}
</script>

<?php
include '../../includes/footer.php';
?>