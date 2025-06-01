<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'unit-manager', 'fraternal-counselor']);
include '../../includes/config.php';
include '../../includes/header.php';
include '../../includes/db.php';
include '../../models/adminModel/membershipRosterModel.php';
include '../../includes/alert2.php';

if (!isset($_GET['council_id'])) {
    die("Council ID is required.");
}

$council_id = intval($_GET['council_id']);
$rosterModel = new MembershipRosterModel($conn);

// Get council details using the model
$council = $rosterModel->getCouncilDetails($council_id);
if (!$council) {
    die("Council not found.");
}

// Get council members using the model
$members = $rosterModel->getCouncilMembers($council_id);
?>

<!-- Add Moment.js for date formatting -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

<!-- Custom Styles -->
<style>
    .page-header {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .stat-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(229, 231, 235, 1);
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    .action-button {
        transition: all 0.2s ease;
    }
    .action-button:hover {
        transform: translateY(-1px);
    }
    .table-container {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border-radius: 0.75rem;
    }
    .table-header {
        background: linear-gradient(to right, #f9fafb, #f3f4f6);
    }
    .member-row {
        transition: all 0.2s ease;
    }
    .member-row:hover {
        background-color: #f8fafc;
        transform: scale(1.005);
    }
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .dt-buttons {
        margin-bottom: 1rem;
        gap: 0.5rem;
        display: flex;
    }
</style>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php' ?>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <!-- Page Header -->
            <div class="page-header rounded-xl text-white p-6 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($council['council_name']); ?></h1>
                        <p class="text-blue-100">Council #<?php echo htmlspecialchars($council['council_number']); ?></p>
                    </div>
                    <button type="button" data-modal-target="addMemberModal" data-modal-toggle="addMemberModal" 
                        class="mt-4 md:mt-0 action-button inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-lg hover:bg-blue-50 focus:ring-4 focus:ring-blue-300">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Member
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="stat-card bg-white rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Total Members</p>
                            <p class="text-2xl font-bold text-gray-900"><?php echo count($members); ?></p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Unit Manager</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($council['um_firstname'] . ' ' . $council['um_lastname']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Fraternal Counselor</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($council['fc_firstname'] . ' ' . $council['fc_lastname']); ?></p>
                        </div>
                    </div>
                </div>

                <div class="stat-card bg-white rounded-xl p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Date Established</p>
                            <p class="text-lg font-semibold text-gray-900"><?php echo date('M d, Y', strtotime($council['date_established'])); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membership Table -->
            <div class="table-container bg-white overflow-hidden">
                <div class="table-header p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Membership Roster</h2>
                    <p class="mt-1 text-sm text-gray-600">Manage and view all members of this council</p>
                </div>

                <div class="p-6">
                    <table id="rosterTable" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 font-semibold">Member Number</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Name</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Address</th>
                                <th scope="col" class="px-6 py-3 font-semibold">First Degree</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Years of Service</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Birth Date</th>
                                <th scope="col" class="px-6 py-3 font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($members)): ?>
                                <?php foreach ($members as $member): ?>
                                    <tr class="member-row border-b">
                                        <td class="px-6 py-4 font-medium text-gray-900">
                                            <?php echo htmlspecialchars($member['member_number']); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($member['member_name']); ?></div>
                                            <div class="text-sm text-gray-500">
                                                <?php echo htmlspecialchars($member['mbr_type'] ?? ''); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div><?php echo htmlspecialchars($member['street_address']); ?></div>
                                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($member['city_state_zip']); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge bg-green-100 text-green-800">
                                                <?php echo date('M d, Y', strtotime($member['first_degree'])); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="status-badge bg-blue-100 text-blue-800">
                                                <?php echo htmlspecialchars($member['yrs_svc']); ?> years
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php echo date('M d, Y', strtotime($member['birth_date'])); ?>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-3">
                                                <button class="action-button text-blue-600 hover:text-blue-900 view-member" 
                                                        data-member-number="<?php echo htmlspecialchars($member['member_number']); ?>"
                                                        data-council-id="<?php echo htmlspecialchars($council_id); ?>">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </button>
                                                <button class="action-button text-yellow-600 hover:text-yellow-900 edit-member"
                                                        data-member-number="<?php echo htmlspecialchars($member['member_number']); ?>"
                                                        data-council-id="<?php echo htmlspecialchars($council_id); ?>">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                                    </svg>
                                                </button>
                                                <button class="action-button text-red-600 hover:text-red-900 delete-member"
                                                        data-member-number="<?php echo htmlspecialchars($member['member_number']); ?>"
                                                        data-council-id="<?php echo htmlspecialchars($council_id); ?>">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-3 mb-4">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-1">No Members Found</h3>
                                            <p class="text-gray-500 mb-4">This council doesn't have any members yet.</p>
                                            <button type="button" data-modal-target="addMemberModal" data-modal-toggle="addMemberModal" 
                                                class="action-button inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Add First Member
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with custom styling
    $('#rosterTable').DataTable({
        responsive: true,
        pageLength: 10,
        dom: 'Bfrtip',
        processing: true,
        columnDefs: [
            {
                targets: 3, // First Degree column
                render: function(data, type, row) {
                    if (type === 'display') {
                        return data; // Return as is since PHP already formats it
                    }
                    return data;
                }
            },
            {
                targets: 4, // Years of Service column
                render: function(data, type, row) {
                    if (type === 'display') {
                        return data; // Return as is since PHP already formats it
                    }
                    return data;
                }
            },
            {
                targets: 5, // Birth Date column
                render: function(data, type, row) {
                    if (type === 'display') {
                        return data; // Return as is since PHP already formats it
                    }
                    return data;
                }
            },
            {
                targets: 6, // Actions column
                orderable: false,
                searchable: false
            }
        ],
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
        initComplete: function() {
            // Add custom styling to the DataTable elements
            $('.dataTables_wrapper').addClass('bg-white rounded-lg shadow-sm p-4');
            $('.dataTables_filter input').addClass('border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500');
            $('.dataTables_length select').addClass('border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500');
            
            // Reattach event handlers for action buttons
            $(document).on('click', '.view-member', function() {
                const memberNumber = $(this).data('member-number');
                const councilId = $(this).data('council-id');
                handleViewMember(memberNumber, councilId);
            });

            $(document).on('click', '.edit-member', function() {
                const memberNumber = $(this).data('member-number');
                const councilId = $(this).data('council-id');
                handleEditMember(memberNumber, councilId);
            });

            $(document).on('click', '.delete-member', function() {
                const memberNumber = $(this).data('member-number');
                const councilId = $(this).data('council-id');
                handleDeleteMember(memberNumber, councilId);
            });
        }
    });

    // Handler functions for member actions
    function handleViewMember(memberNumber, councilId) {
        $.get('../../controllers/adminController/membershipRosterController.php', {
            action: 'get_details',
            member_number: memberNumber,
            council_id: councilId
        }, function(response) {
            if (response.status === 'success') {
                const member = response.data;
                Swal.fire({
                    title: '<h3 class="text-xl font-semibold">' + member.member_name + '</h3>',
                    html: `
                        <div class="text-left p-4 bg-gray-50 rounded-lg">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Member Number</p>
                                    <p class="font-medium">${member.member_number}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Member Type</p>
                                    <p class="font-medium">${member.mbr_type || 'N/A'}</p>
                                </div>
                                <div class="col-span-2">
                                    <p class="text-sm text-gray-600">Address</p>
                                    <p class="font-medium">${member.street_address || 'N/A'}</p>
                                    <p class="text-sm text-gray-500">${member.city_state_zip || ''}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">First Degree</p>
                                    <p class="font-medium">${member.first_degree || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Years of Service</p>
                                    <p class="font-medium">${member.yrs_svc || 0} years</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Birth Date</p>
                                    <p class="font-medium">${member.birth_date || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Member Class</p>
                                    <p class="font-medium">${member.mbr_cls || 'N/A'}</p>
                                </div>
                            </div>
                        </div>
                    `,
                    width: '600px',
                    showCloseButton: true,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.message,
                    icon: 'error',
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            }
        });
    }

    function handleEditMember(memberNumber, councilId) {
        // Fetch member details first
        $.get('../../controllers/adminController/membershipRosterController.php', {
            action: 'get_details',
            member_number: memberNumber,
            council_id: councilId
        }, function(response) {
            if (response.status === 'success') {
                const member = response.data;
                Swal.fire({
                    title: 'Edit Member',
                    html: `
                        <form id="editMemberForm" class="text-left">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="member_number" value="${member.member_number}">
                            <input type="hidden" name="council_id" value="${councilId}">
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Member Name</label>
                                    <input type="text" name="member_name" class="w-full p-2 border rounded-lg" 
                                           value="${member.member_name}" required>
                                </div>
                                
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                                    <input type="text" name="street_address" class="w-full p-2 border rounded-lg" 
                                           value="${member.street_address}" required>
                                </div>
                                
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">City, State, ZIP</label>
                                    <input type="text" name="city_state_zip" class="w-full p-2 border rounded-lg" 
                                           value="${member.city_state_zip}" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Degree Date</label>
                                    <input type="date" name="first_degree" class="w-full p-2 border rounded-lg" 
                                           value="${member.first_degree}" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Birth Date</label>
                                    <input type="date" name="birth_date" class="w-full p-2 border rounded-lg" 
                                           value="${member.birth_date}" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Second Degree Date</label>
                                    <input type="date" name="second_degree" class="w-full p-2 border rounded-lg" 
                                           value="${member.second_degree || ''}">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Third Degree Date</label>
                                    <input type="date" name="third_degree" class="w-full p-2 border rounded-lg" 
                                           value="${member.third_degree || ''}">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Years of Service</label>
                                    <input type="number" name="yrs_svc" class="w-full p-2 border rounded-lg" 
                                           value="${member.yrs_svc}" min="0" required>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Member Type</label>
                                    <select name="mbr_type" class="w-full p-2 border rounded-lg" required>
                                        <option value="Regular" ${member.mbr_type === 'Regular' ? 'selected' : ''}>Regular</option>
                                        <option value="Associate" ${member.mbr_type === 'Associate' ? 'selected' : ''}>Associate</option>
                                        <option value="Honorary" ${member.mbr_type === 'Honorary' ? 'selected' : ''}>Honorary</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Member Class</label>
                                    <select name="mbr_cls" class="w-full p-2 border rounded-lg">
                                        <option value="">Select Class</option>
                                        <option value="A" ${member.mbr_cls === 'A' ? 'selected' : ''}>Class A</option>
                                        <option value="B" ${member.mbr_cls === 'B' ? 'selected' : ''}>Class B</option>
                                        <option value="C" ${member.mbr_cls === 'C' ? 'selected' : ''}>Class C</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Assembly</label>
                                    <input type="text" name="assy" class="w-full p-2 border rounded-lg" 
                                           value="${member.assy || ''}">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">EXM</label>
                                    <input type="text" name="exm" class="w-full p-2 border rounded-lg" 
                                           value="${member.exm || ''}">
                                </div>
                            </div>
                        </form>
                    `,
                    width: '800px',
                    showCancelButton: true,
                    confirmButtonText: 'Update Member',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#2563eb',
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'px-4 py-2 rounded-lg',
                        cancelButton: 'px-4 py-2 rounded-lg'
                    },
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        // Validate form
                        const form = document.getElementById('editMemberForm');
                        if (!form.checkValidity()) {
                            form.reportValidity();
                            return false;
                        }
                        
                        // Get form data
                        const formData = new FormData(form);
                        const data = {};
                        formData.forEach((value, key) => {
                            data[key] = value;
                        });
                        
                        // Send update request
                        return $.ajax({
                            url: '../../controllers/adminController/membershipRosterController.php',
                            method: 'POST',
                            data: data,
                            dataType: 'json'
                        }).then(response => {
                            if (response.status === 'success') {
                                return response;
                            }
                            throw new Error(response.message || 'Failed to update member');
                        }).catch(error => {
                            Swal.showValidationMessage(`Error: ${error.message || 'Failed to update member. Please try again.'}`);
                        });
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed && result.value.status === 'success') {
                        Swal.fire({
                            title: 'Success!',
                            text: result.value.message || 'Member updated successfully',
                            icon: 'success',
                            customClass: {
                                popup: 'rounded-xl'
                            }
                        }).then(() => {
                            // Reload the page to show updated data
                            window.location.reload();
                        });
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error',
                    text: response.message || 'Failed to fetch member details',
                    icon: 'error',
                    customClass: {
                        popup: 'rounded-xl'
                    }
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            Swal.fire({
                title: 'Error',
                text: 'Failed to fetch member details. Please try again.',
                icon: 'error',
                customClass: {
                    popup: 'rounded-xl'
                }
            });
        });
    }

    function handleDeleteMember(memberNumber, councilId) {
        Swal.fire({
            title: 'Delete Member?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel',
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg',
                cancelButton: 'rounded-lg'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../../controllers/adminController/membershipRosterController.php?action=delete&member_number=${memberNumber}&council_id=${councilId}`;
            }
        });
    }
});
</script>

<?php include '../../includes/footer.php'; ?>

<!-- Add Member Modal -->
<div id="addMemberModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-xl shadow-2xl dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-6 border-b rounded-t dark:border-gray-600 bg-gray-50">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Add New Member
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="addMemberModal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <!-- Modal body -->
            <form class="p-6" action="../../controllers/adminController/membershipRosterController.php" method="POST">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="council_id" value="<?php echo $council_id; ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label for="member_number" class="block mb-2 text-sm font-medium text-gray-900">Member Number <span class="text-red-500">*</span></label>
                        <input type="text" name="member_number" id="member_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <div class="col-span-2">
                        <label for="member_name" class="block mb-2 text-sm font-medium text-gray-900">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="member_name" id="member_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <div class="col-span-2">
                        <label for="street_address" class="block mb-2 text-sm font-medium text-gray-900">Street Address <span class="text-red-500">*</span></label>
                        <input type="text" name="street_address" id="street_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <div class="col-span-2">
                        <label for="city_state_zip" class="block mb-2 text-sm font-medium text-gray-900">City, State, ZIP <span class="text-red-500">*</span></label>
                        <input type="text" name="city_state_zip" id="city_state_zip" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <div>
                        <label for="first_degree" class="block mb-2 text-sm font-medium text-gray-900">First Degree Date <span class="text-red-500">*</span></label>
                        <input type="date" name="first_degree" id="first_degree" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <div>
                        <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">Birth Date <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" id="birth_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <div>
                        <label for="second_degree" class="block mb-2 text-sm font-medium text-gray-900">Second Degree Date</label>
                        <input type="date" name="second_degree" id="second_degree" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="third_degree" class="block mb-2 text-sm font-medium text-gray-900">Third Degree Date</label>
                        <input type="date" name="third_degree" id="third_degree" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="yrs_svc" class="block mb-2 text-sm font-medium text-gray-900">Years of Service <span class="text-red-500">*</span></label>
                        <input type="number" name="yrs_svc" id="yrs_svc" min="0" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                    </div>

                    <div>
                        <label for="mbr_type" class="block mb-2 text-sm font-medium text-gray-900">Member Type <span class="text-red-500">*</span></label>
                        <select name="mbr_type" id="mbr_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            <option value="">Select Type</option>
                            <option value="Regular">Regular</option>
                            <option value="Associate">Associate</option>
                            <option value="Honorary">Honorary</option>
                        </select>
                    </div>

                    <div>
                        <label for="mbr_cls" class="block mb-2 text-sm font-medium text-gray-900">Member Class</label>
                        <select name="mbr_cls" id="mbr_cls" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Select Class</option>
                            <option value="A">Class A</option>
                            <option value="B">Class B</option>
                            <option value="C">Class C</option>
                        </select>
                    </div>

                    <div>
                        <label for="assy" class="block mb-2 text-sm font-medium text-gray-900">Assembly</label>
                        <input type="text" name="assy" id="assy" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>

                    <div>
                        <label for="exm" class="block mb-2 text-sm font-medium text-gray-900">EXM</label>
                        <input type="text" name="exm" id="exm" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
                    <button type="button" data-modal-hide="addMemberModal" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                        Cancel
                    </button>
                    <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Add Member
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Add this to your existing script
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        const requiredFields = $(this).find('[required]');
        let isValid = true;

        requiredFields.each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('border-red-500');
                // Add error message below the field
                if (!$(this).next('.error-message').length) {
                    $(this).after(`<p class="text-red-500 text-xs mt-1 error-message">This field is required</p>`);
                }
            } else {
                $(this).removeClass('border-red-500');
                $(this).next('.error-message').remove();
            }
        });

        if (!isValid) {
            e.preventDefault();
            // Show error message at top of form
            if (!$('.form-error-message').length) {
                $(this).prepend(`
                    <div class="mb-4 p-4 text-sm text-red-800 rounded-lg bg-red-50 form-error-message">
                        <p>Please fill in all required fields</p>
                    </div>
                `);
            }
        }
    });

    // Clear validation styling on input
    $('input, select').on('input change', function() {
        $(this).removeClass('border-red-500');
        $(this).next('.error-message').remove();
        $('.form-error-message').remove();
    });

    // Initialize date pickers with max date as today
    const today = new Date().toISOString().split('T')[0];
    $('input[type="date"]').attr('max', today);

    // Calculate years of service based on first degree date
    $('#first_degree').on('change', function() {
        const firstDegreeDate = new Date($(this).val());
        const today = new Date();
        const yearsDiff = today.getFullYear() - firstDegreeDate.getFullYear();
        $('#yrs_svc').val(yearsDiff);
    });
});
</script>