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

<!-- Add DataTables Buttons and JSZip -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<!-- Add SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .application-card {
        transition: all 0.3s ease;
    }
    .application-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
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
    .badge-approved {
        @apply bg-green-100 text-green-800;
    }
    .badge-pending {
        @apply bg-yellow-100 text-yellow-800;
    }
    .badge-rejected {
        @apply bg-red-100 text-red-800;
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
    .dataTables_wrapper {
        @apply w-full overflow-x-auto;
        min-width: 100%;
        margin: 0;
        padding: 1rem;
    }
    #myTable2s {
        @apply w-full !important;
        margin: 0 !important;
    }
    .dataTables_filter input,
    .dataTables_length select {
        @apply rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50;
        max-width: 200px;
    }
    .dataTables_processing {
        @apply bg-white bg-opacity-80 backdrop-blur-sm !important;
    }
    .table-container {
        @apply w-full overflow-x-auto;
        -webkit-overflow-scrolling: touch;
        max-width: 100%;
        margin: 0;
        padding: 0;
    }
    .table-cell-wrap {
        white-space: normal;
        min-width: 120px;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .table-cell-number {
        white-space: nowrap;
        text-align: right;
        min-width: 100px;
    }
    .table-cell-status {
        white-space: nowrap;
        min-width: 90px;
    }
    .table-cell-actions {
        white-space: nowrap;
        min-width: 110px;
    }
    /* Ensure the table header stays fixed */
    .dataTables_scrollHead {
        overflow: visible !important;
    }
    /* Adjust padding for better mobile view */
    @media (max-width: 768px) {
        .table-container {
            margin: 0;
            padding: 0;
        }
        .dataTables_wrapper {
            padding: 0.5rem;
        }
        .table-cell-wrap {
            min-width: 100px;
            max-width: 150px;
        }
    }

    /* Add horizontal scroll indicator */
    .table-scroll-indicator {
        position: relative;
        width: 100%;
        height: 2px;
        background-color: #e5e7eb;
        margin-top: -2px;
        display: none;
    }
    
    .table-scroll-indicator::after {
        content: '';
        position: absolute;
        height: 100%;
        width: 33.33%;
        background-color: #3b82f6;
        left: 0;
        transform-origin: left;
        transition: transform 0.3s ease;
    }
    
    @media (max-width: 1024px) {
        .table-scroll-indicator {
            display: block;
        }
    }

    /* Hide the default DataTables Buttons */
    .dt-buttons {
        display: none !important;
    }
</style>

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
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Member Applications</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <section class="application-card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Member Applications</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Review and manage member insurance applications</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" id="exportExcel"
                                class="flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export to Excel
                            </button>
                            <button type="button" id="printTable"
                                class="flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Print Table
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-hidden">
                    <div class="table-container">
                        <div class="table-scroll-indicator"></div>
                        <table id="myTable2s" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 stripe hover">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-4 py-3 font-semibold table-cell-wrap">Applicant</th>
                                    <th scope="col" class="px-4 py-3 font-semibold">Plan Type</th>
                                    <th scope="col" class="px-4 py-3 font-semibold table-cell-wrap">Plan</th>
                                    <th scope="col" class="px-4 py-3 font-semibold table-cell-number">Face Value</th>
                                    <th scope="col" class="px-4 py-3 font-semibold table-cell-status">Status</th>
                                    <th scope="col" class="px-4 py-3 font-semibold table-cell-actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $applicationModel = new MemberApplicationModel($conn);
                                $applicants = $applicationModel->getAllApplicants();

                                if ($applicants && is_array($applicants) && count($applicants) > 0) {
                                    foreach ($applicants as $applicant) {
                                    ?>
                                    <tr class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900"><?= htmlspecialchars($applicant['applicant_name']) ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="badge <?php
                                                switch(strtolower(str_replace(' ', '', $applicant['plan_type']))) {
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
                                                <?= htmlspecialchars($applicant['plan_type']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4"><?= htmlspecialchars($applicant['plan_name']) ?></td>
                                        <td class="px-6 py-4 text-right">₱<?= number_format($applicant['face_value'], 2) ?></td>
                                        <td class="px-6 py-4">
                                            <span class="badge <?php
                                                switch(strtolower($applicant['application_status'])) {
                                                    case 'approved':
                                                        echo 'badge-approved';
                                                        break;
                                                    case 'pending':
                                                        echo 'badge-pending';
                                                        break;
                                                    case 'rejected':
                                                        echo 'badge-rejected';
                                                        break;
                                                    default:
                                                        echo 'bg-gray-100 text-gray-800';
                                                }
                                            ?>">
                                                <?= ucfirst(htmlspecialchars($applicant['application_status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <a href="moreapplicationdetails.php?id=<?= $applicant['applicant_id'] ?>&user_id=<?= $applicant['user_id'] ?>"
                                                    class="action-button inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 focus:ring-2 focus:ring-blue-300 transition-colors duration-200">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    View Details
                                                </a>
                                                <?php if (strtolower($applicant['application_status']) === 'pending'): ?>
                                                    <button onclick="updateStatus(<?= $applicant['applicant_id'] ?>, 'Approved')"
                                                        class="action-button inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 focus:ring-2 focus:ring-green-300 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Approve
                                                    </button>
                                                    <button onclick="updateStatus(<?= $applicant['applicant_id'] ?>, 'Rejected')"
                                                        class="action-button inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 focus:ring-2 focus:ring-red-300 transition-colors duration-200">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        Reject
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                                    }
                                } else { ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            <div class="flex flex-col items-center justify-center py-8">
                                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <p class="text-lg font-medium">No applications found</p>
                                                <p class="text-sm text-gray-500 mt-1">There are no member applications at the moment</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </main>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    if ($.fn.DataTable.isDataTable('#myTable2s')) {
        $('#myTable2s').DataTable().destroy();
    }
    
    var table = $('#myTable2s').DataTable({
        responsive: true,
        scrollX: true,
        scrollCollapse: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        dom: '<"flex flex-col md:flex-row justify-between items-start md:items-center mb-4"<"flex items-center space-x-2"l<"ml-2">><"flex items-center space-x-2"f>>rtip',
        language: {
            search: "",
            searchPlaceholder: "Search applications...",
            processing: '<div class="flex justify-center items-center space-x-2"><div class="animate-spin h-5 w-5 border-2 border-blue-500 rounded-full border-t-transparent"></div><span>Processing...</span></div>'
        },
        buttons: [
            {
                extend: 'excel',
                className: 'hidden',
                title: 'Member Applications',
                filename: 'Member_Applications_' + new Date().toISOString().slice(0,10),
                exportOptions: {
                    columns: [0, 1, 2, 3, 4] // Export all columns except Actions
                }
            },
            {
                extend: 'print',
                className: 'hidden',
                title: 'Member Applications',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4] // Print all columns except Actions
                },
                customize: function(win) {
                    $(win.document.body)
                        .css('font-size', '10pt')
                        .prepend(
                            '<div class="text-center mb-4">' +
                                '<h1 style="font-size: 18pt; font-weight: bold; margin-bottom: 10px;">Member Applications</h1>' +
                                '<p style="font-size: 10pt; margin-bottom: 20px;">Generated on: ' + new Date().toLocaleDateString() + '</p>' +
                            '</div>'
                        );

                    $(win.document.body).find('table')
                        .addClass('compact')
                        .css('font-size', 'inherit')
                        .css('border-collapse', 'collapse')
                        .css('width', '100%');

                    $(win.document.body).find('table th, table td')
                        .css('border', '1px solid #ddd')
                        .css('padding', '8px')
                        .css('text-align', 'left');

                    $(win.document.body).find('table th')
                        .css('background-color', '#f8f9fa');
                }
            }
        ],
        columnDefs: [
            { className: "table-cell-wrap", targets: [0, 2] },
            { className: "table-cell-number", targets: [3] },
            { className: "table-cell-status", targets: [4] },
            { className: "table-cell-actions", targets: [5] }
        ],
        drawCallback: function() {
            // Update scroll indicator position
            var tableWrapper = $('.table-container');
            var scrollPercentage = (tableWrapper.scrollLeft() / (tableWrapper[0].scrollWidth - tableWrapper.width())) * 100;
            $('.table-scroll-indicator::after').css('transform', `translateX(${scrollPercentage}%)`);
        }
    });

    // Handle custom export buttons
    $('#exportExcel').on('click', function() {
        table.button('.buttons-excel').trigger();
    });

    $('#printTable').on('click', function() {
        table.button('.buttons-print').trigger();
    });

    // Handle horizontal scroll indicator
    $('.table-container').on('scroll', function() {
        var scrollPercentage = ($(this).scrollLeft() / (this[0].scrollWidth - $(this).width())) * 100;
        $('.table-scroll-indicator::after').css('transform', `translateX(${scrollPercentage}%)`);
    });

    // Make the table responsive to window resizing
    $(window).on('resize', function() {
        table.columns.adjust().draw();
    });

    // Initial adjustment
    table.columns.adjust().draw();
});

function updateStatus(applicantId, status) {
    // Show loading state
    Swal.fire({
        title: 'Processing...',
        text: 'Please wait while we update the application status.',
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });

    // Prepare the form data
    const formData = new FormData();
    formData.append('applicant_id', applicantId);
    formData.append('status', status);

    // Make the AJAX request
    $.ajax({
        url: '../../controllers/admin/updateApplicationStatus.php',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            try {
                const result = (typeof response === 'string') ? JSON.parse(response) : response;
                
                if (result.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: `Application has been ${status.toLowerCase()} successfully.`,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.message || 'Failed to update application status. Please try again.',
                    });
                }
            } catch (error) {
                console.error('Error parsing response:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred while processing the response.',
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX Error:', {xhr, status, error});
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'An error occurred while updating the status. Please try again.',
                footer: `Error details: ${error}`
            });
        }
    });
}
</script>

<?php include '../../includes/footer.php'; ?>