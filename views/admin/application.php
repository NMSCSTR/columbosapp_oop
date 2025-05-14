<?php
session_start();
include '../../includes/config.php';
include '../../includes/header.php';
include '../../includes/db.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/userModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../includes/alert2.php';
?>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">



<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php' ?>

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
                                <th class="px-4 py-3">Council Number</th>
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
                </section>

            </div>
        </div>
    </main>
</div>


<?php
include '../../includes/footer.php';
?>