<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    // include '../../models/adminModel/councilModel.php'; // CouncilModel seems unused here
    // include '../../models/adminModel/userModel.php'; // UserModel seems unused here
    include '../../models/memberModel/memberApplicationModel.php';
    // include '../../models/adminModel/fraternalBenefitsModel.php'; // fraternalBenefitsModel seems unused here
    include '../../includes/alert2.php';
?>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    .dataTables_wrapper {
        width: 100%;
        overflow-x: auto;
    }
    
    #myTable {
        width: 100% !important;
        margin: 0;
    }
    
    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    @media screen and (max-width: 1024px) {
        .table-container {
            margin: 0 -1rem;
        }
    }
</style>

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
                            <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Admin</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Member Applications</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <section class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
                 <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Application List</h2>
                </div>
                <div class="overflow-x-auto">
                    <table id="myTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-4">Applicant Name</th>
                                <th scope="col" class="px-6 py-4">Plan Type</th>
                                <th scope="col" class="px-6 py-4">Plan Name</th>
                                <th scope="col" class="px-6 py-4 text-right">Face Value</th>
                                <th scope="col" class="px-6 py-4 text-center">Years to Mature</th>
                                <th scope="col" class="px-6 py-4 text-center">Years Protect</th>
                                <th scope="col" class="px-6 py-4">Payment Mode</th>
                                <th scope="col" class="px-6 py-4 text-right">Contribution Amt.</th>
                                <th scope="col" class="px-6 py-4 text-right">Total Contribution</th>
                                <th scope="col" class="px-6 py-4 text-right">Insurance Cost</th>
                                <th scope="col" class="px-6 py-4 text-right">Admin Fee</th>
                                <th scope="col" class="px-6 py-4 text-right">Savings Fund</th>
                                <th scope="col" class="px-6 py-4">Status</th>
                                <th scope="col" class="px-6 py-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $applicationModel = new MemberApplicationModel($conn);
                            $applicants       = $applicationModel->getAllApplicants();

                            if ($applicants && is_array($applicants) && count($applicants) > 0) {
                                foreach ($applicants as $applicant) {
                                ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo htmlspecialchars($applicant['applicant_name'])?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($applicant['plan_type'])?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($applicant['plan_name'])?></td>
                                <td class="px-6 py-4 text-right">
                                    ₱<?php echo htmlspecialchars(number_format($applicant['face_value'],2))?></td>
                                <td class="px-6 py-4 text-center"><?php echo htmlspecialchars($applicant['years_to_maturity'])?></td>
                                <td class="px-6 py-4 text-center"><?php echo htmlspecialchars($applicant['years_of_protection'])?></td>
                                <td class="px-6 py-4"><?php echo htmlspecialchars($applicant['payment_mode'])?></td>
                                <td class="px-6 py-4 text-right">₱<?php echo number_format($applicant['contribution_amount'], 2)?></td>
                                <td class="px-6 py-4 text-right">₱<?php echo number_format($applicant['total_contribution'], 2); ?></td>
                                <td class="px-6 py-4 text-right">₱<?php echo number_format($applicant['insurance_cost'], 2); ?></td>
                                <td class="px-6 py-4 text-right">₱<?php echo number_format($applicant['admin_fee'], 2); ?></td>
                                <td class="px-6 py-4 text-right">₱<?php echo number_format($applicant['savings_fund'], 2); ?></td>
                                <td class="px-6 py-4">
                                    <?php 
                                        $status = htmlspecialchars($applicant['application_status']);
                                        $statusClass = '';
                                        if ($status == 'approved') {
                                            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                        } elseif ($status == 'pending') {
                                            $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                        } elseif ($status == 'rejected') {
                                            $statusClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                                        }
                                    ?>
                                    <span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full <?php echo $statusClass; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <a href="moreapplicationdetails.php?id=<?= $applicant['applicant_id'] ?>&user_id=<?= $applicant['user_id'] ?>"
                                        class="inline-flex items-center px-3 py-2 text-xs font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        View Details
                                    </a>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                                echo "<tr class='bg-white dark:bg-gray-800'><td colspan='14' class='px-6 py-4 text-center text-gray-500 dark:text-gray-400'>No applications found.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
// Removed the commented out example data at the end of the file
?>

<script>
$(document).ready(function() {
    if (!$.fn.DataTable.isDataTable('#myTable')) {
        $('#myTable').DataTable({
            responsive: true,
            scrollX: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
            dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"<"mb-4 md:mb-0"l><"flex items-center"f>>rtip',
            language: {
                search: "",
                searchPlaceholder: "Search applications..."
            }
        });
    }
});
</script>