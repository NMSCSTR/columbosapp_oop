<?php
session_start();
include '../../includes/config.php';
include '../../includes/header.php';
include '../../includes/db.php';
include '../../models/adminModel/councilModel.php';
include '../../includes/alert2.php';
?>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    .modal-transition {
        transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    }
    .modal-enter {
        opacity: 0;
        transform: scale(0.95);
    }
    .modal-enter-active {
        opacity: 1;
        transform: scale(1);
    }
    .council-card {
        transition: all 0.3s ease;
    }
    .council-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .input-group label {
        position: absolute;
        top: -0.5rem;
        left: 0.75rem;
        background: white;
        padding: 0 0.5rem;
        font-size: 0.875rem;
        color: #4B5563;
        transition: all 0.2s ease;
    }
    .input-group input:focus + label,
    .input-group select:focus + label {
        color: #2563EB;
    }
    .input-group input,
    .input-group select {
        transition: all 0.2s ease;
    }
    .input-group input:focus,
    .input-group select:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 1px #2563EB;
    }
    .table-row {
        transition: all 0.2s ease;
    }
    .table-row:hover {
        background-color: #F3F4F6;
        transform: scale(1.01);
    }
    .action-button {
        transition: all 0.2s ease;
    }
    .action-button:hover {
        transform: translateY(-1px);
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    .animate-fadeIn {
        animation: fadeIn 0.5s ease-in-out;
    }
</style>

<!-- Modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-700 modal-transition modal-enter">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-6 border-b rounded-t dark:border-gray-600 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Council
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white transition-colors duration-200"
                    data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-6" method="POST" action="../../controllers/adminController/addCouncilController.php">
                <div class="grid gap-6 mb-6">
                    <input type="hidden" name="date_created" value="<?php echo date('Y-m-d H:i:s'); ?>">
                    
                    <div class="input-group">
                        <input type="text" name="council_number" id="council_number"
                            class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none"
                            placeholder=" " required>
                        <label for="council_number" class="text-sm font-medium text-gray-900 dark:text-white">Council Number</label>
                    </div>

                    <div class="input-group">
                        <input type="text" name="council_name" id="council_name"
                            class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none"
                            placeholder=" " required>
                        <label for="council_name" class="text-sm font-medium text-gray-900 dark:text-white">Council Name</label>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="input-group">
                            <select name="unit_manager_id" id="unit_manager_id"
                                class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none"
                                required>
                                <option selected disabled value="">Select Unit Manager</option>
                                <?php
                                $fetch_unit_managers = mysqli_query($conn, "SELECT * FROM users WHERE role = 'unit-manager' AND status = 'approved'");
                                while ($manager = mysqli_fetch_assoc($fetch_unit_managers)) { ?>
                                <option value="<?php echo $manager['id']; ?>">
                                    <?php echo htmlspecialchars($manager['firstname'] . ' ' . $manager['lastname']); ?>
                                </option>
                                <?php } ?>
                            </select>
                            <label for="unit_manager_id" class="text-sm font-medium text-gray-900 dark:text-white">Unit Manager</label>
                        </div>

                        <div class="input-group">
                            <select name="fraternal_counselor_id" id="fraternal_counselor_id"
                                class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none"
                                required>
                                <option selected disabled value="">Select Counselor</option>
                                <?php
                                $fetch_fraternal_counselor = mysqli_query($conn, "SELECT * FROM users WHERE role = 'fraternal-counselor' AND status = 'approved'");
                                while ($fraternal = mysqli_fetch_assoc($fetch_fraternal_counselor)) { ?>
                                <option value="<?php echo $fraternal['id']; ?>">
                                    <?php echo htmlspecialchars($fraternal['firstname'] . ' ' . $fraternal['lastname']); ?>
                                </option>
                                <?php } ?>
                            </select>
                            <label for="fraternal_counselor_id" class="text-sm font-medium text-gray-900 dark:text-white">Fraternal Counselor</label>
                        </div>
                    </div>

                    <div class="input-group">
                        <input type="date" name="date_established" id="date_established"
                            class="bg-gray-50 border-2 border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white focus:outline-none"
                            required>
                        <label for="date_established" class="text-sm font-medium text-gray-900 dark:text-white">Date Established</label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-200 dark:border-gray-600">
                    <button type="button" data-modal-toggle="crud-modal" 
                        class="action-button px-4 py-2 text-sm font-medium text-gray-500 bg-white border-2 border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                        class="action-button text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Council
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php' ?>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="<?php echo BASE_URL?>views/admin/dashboard.php"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors duration-200">
                            <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white transition-colors duration-200">Admin</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Council Management</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <section class="council-card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 animate-fadeIn">
                <!-- Header with Add Button -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Council List</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage and organize your councils</p>
                        </div>
                        <button type="button" data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                            class="action-button flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition-all duration-200 shadow-md hover:shadow-lg">
                            <svg class="h-4 w-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"/>
                            </svg>
                            Add New Council
                        </button>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto p-6">
                    <table id="myTables" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 stripe hover" style="width:100%">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Council Number</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Name</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Unit Manager</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Fraternal Counselor</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Council Established</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $councilModel = new CouncilModel($conn);
                            $councils     = $councilModel->getAllCouncil();

                            if ($councils) {
                                foreach ($councils as $council) {
                                    $um_name = $councilModel->getUserNameById($council['unit_manager_id'], 'unit-manager');
                                    $fc_name = $councilModel->getUserNameById($council['fraternal_counselor_id'], 'fraternal-counselor');
                            ?>
                            <tr class='table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700'>
                                <td class='px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'>
                                    <?php echo $council['council_number'] ?>
                                </td>
                                <td class='px-6 py-4'><?php echo $council['council_name'] ?></td>
                                <td class='px-6 py-4'>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <?php echo $um_name ?>
                                    </span>
                                </td>
                                <td class='px-6 py-4'>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <?php echo $fc_name ?>
                                    </span>
                                </td>
                                <td class='px-6 py-4'>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <?php echo date("M d, Y", strtotime($council['date_established'])); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 space-x-2 whitespace-nowrap">
                                    <a href="membership_roster.php?council_id=<?= $council['council_id'] ?>"
                                        class="action-button inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-700">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        View Members
                                    </a>
                                    <a href="updateCouncilForm.php?id=<?= $council['council_id'] ?>"
                                        class="action-button inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 focus:ring-4 focus:ring-yellow-300 dark:bg-yellow-500 dark:hover:bg-yellow-600 dark:focus:ring-yellow-700">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Update
                                    </a>
                                    <button type="button"
                                        class="action-button inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 delete-council"
                                        data-id="<?= $council['council_id'] ?>">
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <?php
                                }
                            } else {
                            ?>
                            <tr class="bg-white dark:bg-gray-800">
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <p class="text-lg font-medium">No councils found</p>
                                        <p class="text-sm text-gray-500 mt-1">Start by adding a new council</p>
                                    </div>
                                </td>
                            </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
// Initialize DataTable with custom configuration
$(document).ready(function() {
    // Destroy existing DataTable if it exists
    if ($.fn.DataTable.isDataTable('#myTable')) {
        $('#myTable').DataTable().destroy();
    }
    
    // Remove any existing buttons to prevent duplication
    $('.dt-buttons').remove();
    
    // Initialize DataTable
    $('#myTable').DataTable({
        responsive: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'action-button bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-excel mr-2"></i>Export to Excel'
            },
            {
                extend: 'pdf',
                className: 'action-button bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-pdf mr-2"></i>Export to PDF'
            },
            {
                extend: 'print',
                className: 'action-button bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-print mr-2"></i>Print'
            }
        ],
        // Prevent DataTables from automatically adding a width style to the table
        autoWidth: false,
        // Ensure proper cleanup on destroy
        destroy: true,
        // Improve loading performance
        deferRender: true,
        // Enable processing indicator
        processing: true,
        language: {
            processing: '<div class="flex justify-center items-center space-x-2"><div class="animate-spin h-5 w-5 border-2 border-blue-500 rounded-full border-t-transparent"></div><span>Processing...</span></div>'
        }
    });
});

// Delete council confirmation
$(document).on('click', '.delete-council', function(e) {
    e.preventDefault();
    const councilId = $(this).data('id');
    
    Swal.fire({
        title: 'Are you sure?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#EF4444',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../../controllers/adminController/deleteCouncil.php?id=' + councilId;
        }
    });
});

// Modal animation
const modal = document.getElementById('crud-modal');
const modalContent = modal.querySelector('.modal-transition');

document.querySelectorAll('[data-modal-toggle="crud-modal"]').forEach(button => {
    button.addEventListener('click', () => {
        if (modal.classList.contains('hidden')) {
            // Show modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modalContent.classList.remove('modal-enter');
                modalContent.classList.add('modal-enter-active');
            }, 10);
        } else {
            // Hide modal
            modalContent.classList.remove('modal-enter-active');
            modalContent.classList.add('modal-enter');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 300);
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>