<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/userModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';
?>

<!-- Add this right after the header include -->
<script>
    const BASE_URL = '<?php echo rtrim(dirname(dirname(dirname($_SERVER['PHP_SELF']))), '/') . '/'; ?>';
</script>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    .user-card {
        transition: all 0.3s ease;
    }
    .user-card:hover {
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
        background-color: #f8fafc;
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
    .badge-success {
        @apply bg-green-100 text-green-800;
    }
    .badge-warning {
        @apply bg-yellow-100 text-yellow-800;
    }
    .badge-danger {
        @apply bg-red-100 text-red-800;
    }
    .badge-info {
        @apply bg-blue-100 text-blue-800;
    }
</style>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <?php
            $breadcrumbItems = [
                ['title' => 'Admin', 'url' => 'dashboard.php'],
                ['title' => 'User Management']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>

            <section class="user-card bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage and monitor user accounts</p>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto p-6">
                    <table id="myTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400 stripe hover" style="width:100%">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Name</th>
                                <th scope="col" class="px-6 py-3 font-semibold">KCFAPI Code</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Contact Info</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Role</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Status</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $userModel = new UserModel($conn);
                            $users = $userModel->getAllUser();
                            if ($users) {
                            foreach ($users as $user) {?>
                            <tr class="table-row bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                <span class="text-sm font-medium text-gray-600">
                                                    <?= strtoupper(substr($user['firstname'], 0, 1) . substr($user['lastname'], 0, 1)) ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($user['kcfapicode']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></div>
                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($user['phone_number']) ?></div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge <?php
                                        switch(strtolower($user['role'])) {
                                            case 'admin':
                                                echo 'badge-danger';
                                                break;
                                            case 'manager':
                                                echo 'badge-info';
                                                break;
                                            default:
                                                echo 'badge-success';
                                        }
                                    ?>">
                                        <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge <?php
                                        switch(strtolower($user['status'])) {
                                            case 'approved':
                                                echo 'badge-success';
                                                break;
                                            case 'pending':
                                                echo 'badge-warning';
                                                break;
                                            case 'disabled':
                                                echo 'badge-danger';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                    ?>">
                                        <?= htmlspecialchars(ucfirst($user['status'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        <button class="action-button view-user-btn inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-700 bg-blue-100 rounded-lg hover:bg-blue-200 focus:ring-2 focus:ring-blue-300 transition-colors duration-200"
                                            data-id="<?= $user['id'] ?>"
                                            data-firstname="<?= htmlspecialchars($user['firstname']) ?>"
                                            data-lastname="<?= htmlspecialchars($user['lastname']) ?>"
                                            data-email="<?= htmlspecialchars($user['email']) ?>"
                                            data-phone="<?= htmlspecialchars($user['phone_number']) ?>"
                                            data-kcfapi="<?= htmlspecialchars($user['kcfapicode']) ?>"
                                            data-role="<?= htmlspecialchars($user['role']) ?>"
                                            data-status="<?= htmlspecialchars($user['status']) ?>">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            View
                                        </button>

                                        <button class="action-button reset-password-btn inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-700 bg-yellow-100 rounded-lg hover:bg-yellow-200 focus:ring-2 focus:ring-yellow-300 transition-colors duration-200"
                                            data-id="<?= $user['id'] ?>"
                                            data-name="<?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>"
                                            data-phone="<?= htmlspecialchars($user['phone_number']) ?>">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                            Reset
                                        </button>

                                        <?php if ($user['status'] === 'pending' || $user['status'] === 'disabled'): ?>
                                            <button class="action-button status-btn approve-btn inline-flex items-center px-3 py-1.5 text-xs font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 focus:ring-2 focus:ring-green-300 transition-colors duration-200"
                                                data-id="<?= $user['id'] ?>"
                                                data-action="approve"
                                                data-name="<?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Approve
                                            </button>
                                        <?php elseif ($user['status'] === 'approved'): ?>
                                            <button class="action-button status-btn disable-btn inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 focus:ring-2 focus:ring-red-300 transition-colors duration-200"
                                                data-id="<?= $user['id'] ?>"
                                                data-action="disable"
                                                data-name="<?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                Disable
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php }
                                } else { ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center py-8">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                            <p class="text-lg font-medium">No users found</p>
                                            <p class="text-sm text-gray-500 mt-1">Start by adding new users to the system</p>
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

<!-- User Details Modal -->
<div id="userDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-[480px] shadow-xl rounded-2xl bg-white modal-transition modal-enter">
        <div class="mt-3">
            <div class="flex items-center justify-between border-b pb-4">
                <h3 class="text-xl font-bold text-gray-900">User Profile</h3>
                <button id="closeUserModal" class="text-gray-400 hover:text-gray-500 transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-6">
                <div class="user-info">
                    <!-- User details will be populated here by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize DataTable with custom configuration
    if ($.fn.DataTable.isDataTable('#myTable')) {
        $('#myTable').DataTable().destroy();
    }
    
    $('.dt-buttons').remove();
    
    $('#myTable').DataTable({
        responsive: true,
        pageLength: 10,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                className: 'action-button bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-excel mr-2"></i>Export to Excel',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4] // Export all columns except Actions
                }
            },
            {
                extend: 'pdf',
                className: 'action-button bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-file-pdf mr-2"></i>Export to PDF',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4] // Export all columns except Actions
                }
            },
            {
                extend: 'print',
                className: 'action-button bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm',
                text: '<i class="fas fa-print mr-2"></i>Print',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4] // Export all columns except Actions
                },
                customize: function(win) {
                    // Customize the print view
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

    // Status button click handler
    document.querySelectorAll('.status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            
            let title, text, icon, confirmButtonText, confirmButtonColor;
            
            if (action === 'approve') {
                title = 'Approve User?';
                text = `Are you sure you want to approve ${userName}?`;
                icon = 'question';
                confirmButtonText = 'Yes, approve';
                confirmButtonColor = '#10B981';
            } else {
                title = 'Disable User?';
                text = `Are you sure you want to disable ${userName}?`;
                icon = 'warning';
                confirmButtonText = 'Yes, disable';
                confirmButtonColor = '#EF4444';
            }
            
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#6B7280',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = BASE_URL + `controllers/adminController/userStatusController.php?id=${userId}&action=${action}`;
                }
            });
        });
    });

    // Reset password button click handler
    document.querySelectorAll('.reset-password-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            const userPhone = this.dataset.phone;
            
            Swal.fire({
                title: 'Reset Password?',
                text: `Are you sure you want to reset the password for ${userName}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EAB308',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, reset password',
                cancelButtonText: 'Cancel',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = BASE_URL + `controllers/adminController/userController.php?reset=${userId}`;
                }
            });
        });
    });

    // View user button click handler
    const userModal = document.getElementById('userDetailsModal');
    const userInfo = document.querySelector('.user-info');
    
    document.querySelectorAll('.view-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userData = this.dataset;
            
            userInfo.innerHTML = `
                <div class="flex flex-col items-center mb-8">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg">
                        <span class="text-3xl font-semibold text-white">
                            ${userData.firstname.charAt(0)}${userData.lastname.charAt(0)}
                        </span>
                    </div>
                    <h4 class="mt-4 text-xl font-semibold text-gray-900">${userData.firstname} ${userData.lastname}</h4>
                    <div class="mt-2 flex items-center space-x-2">
                        <span class="badge ${
                            userData.role === 'admin' ? 'badge-danger' :
                            userData.role === 'manager' ? 'badge-info' :
                            'badge-success'
                        }">
                            ${userData.role}
                        </span>
                        <span class="badge ${
                            userData.status === 'approved' ? 'badge-success' :
                            userData.status === 'pending' ? 'badge-warning' :
                            'badge-danger'
                        }">
                            ${userData.status}
                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-6">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h5 class="text-sm font-medium text-gray-500">KCFAPI Code</h5>
                                <p class="mt-1 text-sm text-gray-900">${userData.kcfapi}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h5 class="text-sm font-medium text-gray-500">Email Address</h5>
                                <p class="mt-1 text-sm text-gray-900">${userData.email}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h5 class="text-sm font-medium text-gray-500">Phone Number</h5>
                                <p class="mt-1 text-sm text-gray-900">${userData.phone}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-2">
                        <button class="inline-flex items-center px-4 py-2 text-sm font-medium text-yellow-700 bg-yellow-100 rounded-lg hover:bg-yellow-200 focus:ring-2 focus:ring-yellow-300 transition-colors duration-200"
                            onclick="handleUserAction('reset', '${userData.id}')">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            Reset Password
                        </button>
                        ${userData.status === 'pending' || userData.status === 'disabled' ?
                            `<button class="inline-flex items-center px-4 py-2 text-sm font-medium text-green-700 bg-green-100 rounded-lg hover:bg-green-200 focus:ring-2 focus:ring-green-300 transition-colors duration-200"
                                onclick="handleUserAction('approve', '${userData.id}')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Approve User
                            </button>` :
                            userData.status === 'approved' ?
                            `<button class="inline-flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-100 rounded-lg hover:bg-red-200 focus:ring-2 focus:ring-red-300 transition-colors duration-200"
                                onclick="handleUserAction('disable', '${userData.id}')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Disable User
                            </button>` : ''
                        }
                    </div>
                </div>
            `;
            
            userModal.classList.remove('hidden');
            setTimeout(() => {
                userModal.querySelector('.modal-transition').classList.remove('modal-enter');
                userModal.querySelector('.modal-transition').classList.add('modal-enter-active');
            }, 10);
        });
    });

    // Close modal handler
    document.getElementById('closeUserModal').addEventListener('click', function() {
        const modalContent = userModal.querySelector('.modal-transition');
        modalContent.classList.remove('modal-enter-active');
        modalContent.classList.add('modal-enter');
        setTimeout(() => {
            userModal.classList.add('hidden');
        }, 300);
    });

    // Close modal when clicking outside
    userModal.addEventListener('click', function(e) {
        if (e.target === userModal) {
            const modalContent = userModal.querySelector('.modal-transition');
            modalContent.classList.remove('modal-enter-active');
            modalContent.classList.add('modal-enter');
            setTimeout(() => {
                userModal.classList.add('hidden');
            }, 300);
        }
    });

    // Define handleUserAction function for the modal
    function handleUserAction(action, userId) {
        if (action === 'reset') {
            window.location.href = BASE_URL + `controllers/adminController/userController.php?reset=${userId}`;
        } else {
            window.location.href = BASE_URL + `controllers/adminController/userStatusController.php?id=${userId}&action=${action}`;
        }
    }
});
</script>

<?php include '../../includes/footer.php'; ?>