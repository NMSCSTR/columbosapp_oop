<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
    include '../../includes/alert2.php';
?>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    .benefits-card {
        transition: all 0.3s ease;
    }
    .benefits-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
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
    .table-row {
        transition: all 0.2s ease;
    }
    .table-row:hover {
        background-color: #f8fafc !important;
        transform: scale(1.01);
    }
    .action-button {
        transition: all 0.2s ease;
    }
    .action-button:hover {
        transform: translateY(-1px);
    }
    .badge {
        @apply px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .badge-investment {
        @apply bg-blue-100 text-blue-800;
    }
    .badge-retirement {
        @apply bg-purple-100 text-purple-800;
    }
    .badge-educational {
        @apply bg-green-100 text-green-800;
    }
    .badge-protection {
        @apply bg-red-100 text-red-800;
    }
    .badge-term {
        @apply bg-orange-100 text-orange-800;
    }
    .dropzone {
        transition: all 0.3s ease;
        border: 2px dashed #e2e8f0;
    }
    .dropzone:hover, .dropzone.dragover {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }
</style>

<!-- Add Plan Modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="true"
    class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[600px] shadow-xl rounded-2xl bg-white modal-transition modal-enter">
        <!-- Modal header -->
        <div class="flex items-center justify-between border-b pb-4">
            <h3 class="text-xl font-bold text-gray-900">Add New Insurance Plan</h3>
            <button type="button" data-modal-toggle="crud-modal"
                class="text-gray-400 hover:text-gray-500 transition-colors duration-200">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal body -->
        <form class="mt-6" method="POST" action="<?php echo BASE_URL?>controllers/adminController/addFraternalBenefitsController.php"
            enctype="multipart/form-data">
            <div class="grid gap-6">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Plan Type</label>
                    <select id="type" name="type"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <option value="">Select plan type</option>
                        <option value="Investment Plan">Investment Plan</option>
                        <option value="Retirement Plan">Retirement Plan</option>
                        <option value="Educational Plan">Educational Plan</option>
                        <option value="Protection Plan">Protection Plan</option>
                        <option value="Term Plan">Term Plan</option>
                    </select>
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Plan Name</label>
                    <input type="text" name="name" id="name"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        placeholder="Enter plan name" required>
                </div>

                <div>
                    <label for="about" class="block text-sm font-medium text-gray-700">About Plan</label>
                    <textarea name="about" id="about" rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        placeholder="Enter plan description" required></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="face_value" class="block text-sm font-medium text-gray-700">Face Value</label>
                        <div class="mt-1 relative rounded-lg shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">₱</span>
                            </div>
                            <input type="number" name="face_value" id="face_value"
                                class="block w-full pl-7 rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                placeholder="0.00" required>
                        </div>
                    </div>

                    <div>
                        <label for="contribution_period" class="block text-sm font-medium text-gray-700">Contribution Period</label>
                        <input type="text" name="contribution_period" id="contribution_period"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            placeholder="e.g., 5 years" required>
                    </div>

                    <div>
                        <label for="years_to_maturity" class="block text-sm font-medium text-gray-700">Years to Maturity</label>
                        <input type="number" name="years_to_maturity" id="years_to_maturity"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            placeholder="Enter number of years" required>
                    </div>

                    <div>
                        <label for="years_of_protection" class="block text-sm font-medium text-gray-700">Years of Protection</label>
                        <input type="number" name="years_of_protection" id="years_of_protection"
                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            placeholder="Enter number of years" required>
                    </div>
                </div>

                <div>
                    <label for="benefits" class="block text-sm font-medium text-gray-700">Plan Benefits</label>
                    <textarea name="benefits" id="benefits" rows="3"
                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                        placeholder="Enter plan benefits" required></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Plan Image</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg dropzone">
                        <div class="space-y-1 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="imageInput"
                                        class="relative cursor-pointer rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a file</span>
                                        <input id="imageInput" name="image" type="file" class="sr-only" accept="image/*" required>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                            </div>
                            <img id="imagePreview" class="hidden max-h-48 rounded-lg mx-auto" alt="Image preview" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" data-modal-toggle="crud-modal"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-100">
                    Cancel
                </button>
                <button type="submit" name="upload"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Add Plan
                </button>
            </div>
        </form>
    </div>
</div>

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
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="#" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Admin</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Fraternal Benefits</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <section class="benefits-card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Fraternal Benefits</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage and monitor insurance plans and benefits</p>
                        </div>
                        <button type="button" data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                            class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Add New Plan
                        </button>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto p-6">
                    <table id="myFbTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 stripe hover" style="width:100%">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Plan Type</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Plan Name</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Face Value</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Contribution Period</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
                            $fraternals = $fraternalBenefitsModel->getAllFraternalBenefits();
                            
                            if ($fraternals) {
                                foreach ($fraternals as $fraternal) {?>
                                    <tr class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            <span class="badge <?php
                                                switch(strtolower(str_replace(' ', '', $fraternal['type']))) {
                                                    case 'investmentplan':
                                                        echo 'badge-investment';
                                                        break;
                                                    case 'retirementplan':
                                                        echo 'badge-retirement';
                                                        break;
                                                    case 'educationalplan':
                                                        echo 'badge-educational';
                                                        break;
                                                    case 'protectionplan':
                                                        echo 'badge-protection';
                                                        break;
                                                    case 'termplan':
                                                        echo 'badge-term';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                            ?>">
                                                <?= htmlspecialchars($fraternal['type']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900"><?= htmlspecialchars($fraternal['name']) ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            ₱<?= number_format($fraternal['face_value'], 2) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?= htmlspecialchars($fraternal['contribution_period']) ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <a href="moreplandetails.php?id=<?= $fraternal['id'] ?>"
                                                    class="action-button inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View Details
                                                </a>

                                                <button type="button"
                                                    class="action-button delete-plan inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 focus:ring-2 focus:ring-red-300 transition-colors duration-200"
                                                    data-id="<?= $fraternal['id'] ?>"
                                                    data-name="<?= htmlspecialchars($fraternal['name']) ?>">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Delete
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php }
                            } else { ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            <p class="text-lg font-medium">No plans found</p>
                                            <p class="text-sm text-gray-500 mt-1">Start by adding a new insurance plan</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable
    if ($.fn.DataTable.isDataTable('#myFbTable')) {
        $('#myFbTable').DataTable().destroy();
    }
    
    $('.dt-buttons').remove();
    
    $('#myFbTable').DataTable({
        responsive: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'action-button bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-excel mr-2"></i>Export to Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Export all columns except Actions
                }
            },
            {
                extend: 'pdf',
                className: 'action-button bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-pdf mr-2"></i>Export to PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Export all columns except Actions
                }
            },
            {
                extend: 'print',
                className: 'action-button bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-print mr-2"></i>Print',
                exportOptions: {
                    columns: [0, 1, 2, 3] // Export all columns except Actions
                },
                customize: function(win) {
                    $(win.document.body).css('font-size', '10pt');
                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit');
                }
            }
        ],
        autoWidth: false,
        destroy: true,
        deferRender: true,
        processing: true,
        language: {
            processing: '<div class="flex justify-center items-center space-x-2"><div class="animate-spin h-5 w-5 border-2 border-blue-500 rounded-full border-t-transparent"></div><span>Processing...</span></div>'
        }
    });

    // Image upload preview
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const dropzone = document.querySelector('.dropzone');

    imageInput.addEventListener('change', updateImagePreview);
    
    function updateImagePreview() {
        const file = imageInput.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });

    dropzone.addEventListener('drop', handleDrop, false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight(e) {
        dropzone.classList.add('dragover');
    }

    function unhighlight(e) {
        dropzone.classList.remove('dragover');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        imageInput.files = files;
        updateImagePreview();
    }

    // Delete plan functionality
    document.querySelectorAll('.delete-plan').forEach(button => {
        button.addEventListener('click', function() {
            const planId = this.dataset.id;
            const planName = this.dataset.name;
            
            Swal.fire({
                title: 'Delete Plan?',
                text: `Are you sure you want to delete ${planName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete plan',
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `${BASE_URL}controllers/adminController/deleteFraternalBenefitsController.php?id=${planId}`;
                }
            });
        });
    });

    // Modal toggle functionality
    const modal = document.getElementById('crud-modal');
    const modalContent = modal.querySelector('.modal-transition');

    document.querySelectorAll('[data-modal-toggle="crud-modal"]').forEach(button => {
        button.addEventListener('click', () => {
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalContent.classList.remove('modal-enter');
                    modalContent.classList.add('modal-enter-active');
                }, 10);
            } else {
                modalContent.classList.remove('modal-enter-active');
                modalContent.classList.add('modal-enter');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        });
    });
});
</script>

<?php
include '../../includes/footer.php';
?>