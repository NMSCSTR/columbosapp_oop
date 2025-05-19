<?php
    session_start();
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/councilModel.php';
    include '../../models/adminModel/userModel.php';
    include '../../models/memberModel/memberApplicationModel.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
    include '../../includes/alert2.php';
?>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">



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
                            <span
                                class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Application</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <section class="bg-gray-50 p-5 rounded shadow">
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
                            $applicants             = $applicationModel->getAllApplicantsWithPlans();

                            if ($applicants && is_array($applicants) && count($applicants) > 0) {
                                foreach ($applicants as $applicant) {

                                ?>
                            <tr>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['applicant_name'])?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['plan_type'])?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['plan_name'])?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars(number_format($applicant['face_value']))?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['years_to_maturity'])?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['years_of_protection'])?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['payment_mode'])?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['contribution_amount'])?></td>
                                <td class="px-4 py-3"><?php echo htmlspecialchars($applicant['application_status'])?></td>
                                <td>
                                    <button>delete</button>
                                </td>
                            </tr>
                            <?php
                                }
                                } else {
                                    // Check for related data availability (optional)
                                    if (empty($councils) || empty($fraternals)) {
                                        echo "<tr><td colspan='8' class='px-4 py-3 text-center'>No councils or fraternal benefits found.</td></tr>";
                                    } else {
                                        echo "<tr><td colspan='8' class='px-4 py-3 text-center'>No data available.</td></tr>";
                                    }
                                }
                            ?>
                        </tbody>
                    </table>
                </section>

            </div>
        </div>
    </main>
</div>


<?php
include '../../includes/footer.php';
?>