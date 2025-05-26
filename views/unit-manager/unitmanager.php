<?php
    require_once '../../middleware/auth.php';
    authorize(['admin', 'unit-manager']);

    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/usersModel.php';
    include '../../models/adminModel/councilModel.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
    include '../../models/memberModel/memberApplicationModel.php';
    include '../../models/adminModel/announcementModel.php';
    include '../../models/adminModel/FormsModel.php';

    $councilModel           = new CouncilModel($conn);
    $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
    $applicationModel       = new MemberApplicationModel($conn);
    $formsModel             = new FormsModel($conn);
    $announcementModel      = new announcementModel($conn);

    $announcements   = $announcementModel->getAllAnnouncement();
    $totalApplicants = $applicationModel->countAllApplicants($_SESSION['user_id']);
    $totals          = $applicationModel->calculateTotalAllocationsForAllApplicants();
    $files           = $formsModel->viewAllForms();

    // $applicantData = $applicationModel->getApplicantByFraternalCounselor($_SESSION['user_id']);
    // $fetchFraternalBenefits = $fraternalBenefitsModel->getFraternalBenefitById($applicantData['fraternal_benefits_id']);
    // $fetchCouncil = $councilModel->getCouncilById($applicantData['council_id']);

    // var_dump($applicantData);

?>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<?php include '../../partials/umsidebar.php'?>

<div id="viewModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 z-50 hidden items-center justify-center">
    <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full max-h-[80vh] overflow-y-auto p-6 relative">
        <button onclick="closeModal()"
            class="absolute top-2 right-2 text-gray-600 hover:text-red-600 text-xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Applicant Details</h2>
        <div id="modalContent" class="space-y-2"></div>
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
                <p class="text-2xl font-bold text-blue-600">
                    <?php echo number_format($totals['total_contribution'] ?? 0, 2) ?></p>
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
                        <th class="px-4 py-3">APPLICANT NAME</th>
                        <th class="px-4 py-3">PLAN TYPE</th>
                        <th class="px-4 py-3">PLAN NAME</th>
                        <th class="px-4 py-3">FACE VALUE</th>
                        <th class="px-4 py-3">YEARS TO MATURE</th>
                        <th class="px-4 py-3">YEARS PROTECT</th>
                        <th class="px-4 py-3">PAYMENT MODE</th>
                        <th class="px-4 py-3">CONTRIBUTION AMOUNT</th>
                        <th class="px-4 py-3">TOTAL CONTRIBUTION</th>
                        <th class="px-4 py-3">INSURANCE COST</th>
                        <th class="px-4 py-3">ADMIN FEE</th>
                        <th class="px-4 py-3">SAVINGS FUND(ALLOCATIONS)</th>
                        <th class="px-4 py-3">APPLICATION STATUS</th>
                        <th class="px-4 py-3">ACTIONS</th>
                    </tr>
                </thead>
                <tbody class="text-xs">
                    <?php
                        $councilModel           = new CouncilModel($conn);
                        $applicationModel       = new MemberApplicationModel($conn);
                        $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
                        $fraternals             = $fraternalBenefitsModel->getAllFraternalBenefits();
                        $councils               = $councilModel->getAllCouncil();
                        $applicants             = $applicationModel->getAllApplicants();

                        if ($applicants && is_array($applicants) && count($applicants) > 0) {
                            foreach ($applicants as $applicant) {
                            ?>
                    <tr>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['applicant_name']) ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['plan_type']) ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['plan_name']) ?></td>
                        <td class="px-4 py-3">
                            ₱<?php echo htmlspecialchars(number_format($applicant['face_value'], 2)) ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['years_to_maturity']) ?>
                        </td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['years_of_protection']) ?>
                        </td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['payment_mode']) ?></td>
                        <td class="px-4 py-3">₱<?php echo number_format($applicant['contribution_amount'], 2) ?>
                        </td>
                        <td class="px-4 py-3">₱<?php echo number_format($applicant['total_contribution'], 2); ?>
                        </td>
                        <td class="px-4 py-3">₱<?php echo number_format($applicant['insurance_cost'], 2); ?>
                        </td>
                        <td class="px-4 py-3">₱<?php echo number_format($applicant['admin_fee'], 2); ?></td>
                        <td class="px-4 py-3">₱<?php echo number_format($applicant['savings_fund'], 2); ?></td>
                        <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['application_status']) ?>
                        </td>
                        <td>
                            <a href="moreapplicationdetails.php?id=<?php echo $applicant['applicant_id']?>&user_id=<?php echo $applicant['user_id']?>"
                                class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                More details
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                        } else {

                            if (empty($councils) || empty($fraternals)) {
                                echo "<tr><td colspan='8' class='px-4 py-3 text-center'>No councils or fraternal benefits found.</td></tr>";
                            } else {
                                echo "<tr><td colspan='8' class='px-4 py-3 text-center'>No data available.</td></tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Forms Section -->
    <div x-show="activeSection === 'forms'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">View list of forms</h1>
            <!-- <p class="text-gray-600">Update your personal information and settings.</p> -->
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="p-4 rounded-lg dark:border-gray-700">
                <section class="bg-gray-50 p-5 rounded shadow">
                    <table id="myTable2" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                                <!-- <th class="px-4 py-3">Id</th> -->
                                <th class="px-4 py-3">FILENAME</th>
                                <th class="px-4 py-3">DESCRIPTION</th>
                                <th class="px-4 py-3">TYPE</th>
                                <th class="px-4 py-3">UPLOADED</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            <?php while ($row = mysqli_fetch_assoc($files)): ?>
                            <tr class='border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'>
                                <!-- <td contenteditable="true" class="filename"><?php echo htmlspecialchars($file['filename'])?></td> -->
                                <td class='px-4 py-3'><?php echo htmlspecialchars($row['filename'])?></td>
                                <td class='px-4 py-3'><?php echo $row['description'] ?></td>
                                <td class='px-4 py-3'><?php echo htmlspecialchars($row['file_type'])?></td>
                                <td class='px-4 py-3'><?php echo date("F j, Y", strtotime($row['uploaded_on']))?></td>
                                <td>
                                    <!-- if live -->
                                    <!-- <a href="https://docs.google.com/gview?url=<?php echo urlencode($row['file_located'])?>&embedded=true"
                                        target="_blank" class="text-green-600">View</a> | -->

                                    <a href="<?php echo BASE_URL?>controllers/adminController/view_docx.php?path=uploads/forms/<?php echo basename($row['file_located'])?>"
                                        target="_blank" class="text-green-600">View</a> |

                                    <a href="<?php echo BASE_URL?>controllers/adminController/formControllers.php?download=<?php echo $row['id']?>"
                                        class="text-blue-600">Download</a>

                                    <!-- <a href="#" data-id="<?php echo $row['id']?>" class="text-red-600 delete-btn">Delete</a> -->

                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>

    <!-- Councils Section -->
    <div x-show="activeSection === 'council'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">View list of Councils</h1>
            <!-- <p class="text-gray-600">Update your personal information and settings.</p> -->
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="p-4 rounded-lg dark:border-gray-700">
                <section class="bg-gray-50 p-5 rounded shadow">
                    <table id="myTable3" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                                <!-- <th class="px-4 py-3">Id</th> -->
                                <th class="px-4 py-3">Council Number</th>
                                <th class="px-4 py-3">Name</th>
                                <th class="px-4 py-3">UNIT MANAGER</th>
                                <th class="px-4 py-3">FRATERNAL COUNSELOR</th>
                                <th class="px-4 py-3">Council Established</th>
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
                            </tr>
                            <?php
                                }
                                } else {
                                    echo "<tr><td colspan='7' class='px-4 py-3 text-center'>No councils found.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>

    <!-- Announcement Section -->
    <div x-show="activeSection === 'announcement'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">List of anouncements</h1>
            <!-- <p class="text-gray-600">Update your personal information and settings.</p> -->
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="p-4 rounded-lg dark:border-gray-700">
                <section>
                    <?php foreach ($announcements as $index => $announcement): ?>
                    <div class="flex items-start gap-3 mb-3">
                        <div class="flex-1 p-4 bg-gray-100 rounded-xl dark:bg-gray-700 relative shadow-sm border hover:bg-gray-200 dark:hover:bg-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm font-semibold text-gray-900 dark:text-white">Admin</span>
                                    <span class="text-sm text-gray-500 dark:text-gray-300">
                                        <?= date("M d, Y H:i", strtotime($announcement['date_posted'])) ?>
                                    </span>
                                </div>
                            </div>

                            <p class="mt-3 text-sm text-gray-900 dark:text-white">
                                <strong><?= htmlspecialchars($announcement['subject']) ?>:</strong>
                                <?= nl2br(htmlspecialchars($announcement['content'])) ?>
                            </p>
                            <span class="block mt-2 text-sm text-gray-500 dark:text-gray-400">Delivered</span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </section>
            </div>
        </div>
    </div>


    <!-- Fraternal Section -->
    <div x-show="activeSection === 'fraternalbenefits'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">View list of Fraternal Benefits</h1>
            <!-- <p class="text-gray-600">Update your personal information and settings.</p> -->
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <div class="p-4 rounded-lg dark:border-gray-700">
                <section class="bg-gray-50 p-5 rounded shadow">
                    <table id="myTable4" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                                <th scope="col" class="px-4 py-3">TYPE</th>
                                <th scope="col" class="px-4 py-3">NAME</th>
                                <!-- <th scope="col" class="px-4 py-3">FACE VALUE</th>
                                <th scope="col" class="px-4 py-3">YEARS TO MATURITY</th>
                                <th scope="col" class="px-4 py-3">YEARS OF PROTECTION</th> -->
                                <th scope="col" class="px-4 py-3">CONTRIBUTION PERIOD</th>
                                <th scope="col" class="px-4 py-3">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            <?php
                                $fraternalBenefitsModel = new fraternalBenefitsModel($conn);

                                $fraternals = $fraternalBenefitsModel->getAllFraternalBenefits();
                                if ($fraternals) {
                                foreach ($fraternals as $fraternal) {?>
                            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-4 py-3"><?php echo $fraternal['type']; ?></td>
                                <td class="px-4 py-3"><?php echo $fraternal['name']; ?></td>
                                <!-- <td class="px-4 py-3"><?php echo $fraternal['face_value']; ?></td>
                                <td class="px-4 py-3"><?php echo $fraternal['years_to_maturity']; ?></td>
                                <td class="px-4 py-3"><?php echo $fraternal['years_of_protection']; ?></td> -->
                                <td class="px-4 py-3"><?php echo $fraternal['contribution_period']; ?></td>
                                <td>
                                    <a href="moreplandetails.php?id=<?php echo $fraternal['id']?>"
                                        class="px-3 py-2 text-xs font-medium text-center inline-flex items-center text-white bg-green-700 rounded-lg hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                            viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                        More details
                                    </a>
                                </td>

                            </tr>
                            <?php }
                                } else {
                                    echo "No fraternal benefits found.";
                                }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
        </div>
    </div>
</main>


<script>
function fetchApplicantData(userId) {
    fetch(`../../controllers/memberController/viewApplicant.php?id=${userId}`)
        .then(res => res.json())
        .then(data => {
            openModal(data);
        })
        .catch(err => console.error('Error:', err));
}

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
    modal.classList.add('hidden');
}
</script>

<script>
$(document).ready(function() {
    $('#myTable2').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
    });
});
</script>
<script>
$(document).ready(function() {
    $('#myTable3').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
    });
});
</script>
<script>
$(document).ready(function() {
    $('#myTable4').DataTable({
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength: 10,
    });
});
</script>



<?php
include '../../includes/footer.php';
?>