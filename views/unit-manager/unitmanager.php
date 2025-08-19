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

    // Debug session data
    $userModel = new UserModel($conn);
    $currentUser = $userModel->getUserById($_SESSION['user_id']);
    if (!isset($_SESSION['email']) && $currentUser) {
        $_SESSION['email'] = $currentUser['email'];
    }

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

<!-- Add custom styles -->
<style>
    .dashboard-card {
        @apply bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 ease-in-out transform hover:-translate-y-1 border border-gray-100;
    }
    .dashboard-card-title {
        @apply text-lg font-semibold text-gray-700 mb-2;
    }
    .dashboard-card-value {
        @apply text-3xl font-bold;
    }
    .table-container {
        @apply bg-white p-6 rounded-xl shadow-lg border border-gray-100;
    }
    .announcement-card {
        @apply bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 border border-gray-100 mb-4;
    }
    .modal-content {
        @apply bg-white rounded-xl shadow-2xl max-w-3xl w-full max-h-[80vh] overflow-y-auto p-8 relative border border-gray-200;
    }
    /* Custom DataTables styling */
    .dataTables_wrapper .dataTables_filter input {
        @apply rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
    }
    .dataTables_wrapper .dataTables_length select {
        @apply rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        @apply bg-blue-500 text-white rounded-lg border-0 !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        @apply bg-blue-100 text-blue-700 rounded-lg border-0 !important;
    }
</style>

<?php include '../../partials/umsidebar.php'?>

<div id="viewModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center backdrop-blur-sm transition-all duration-300">
    <div class="modal-content">
        <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Applicant Details</h2>
        <div id="modalContent" class="space-y-3"></div>
    </div>
</div>

<!-- Profile Edit Modal -->
<div id="profileModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center backdrop-blur-sm transition-all duration-300">
    <div class="modal-content max-w-xl">
        <button onclick="closeProfileModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition-colors duration-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Profile</h2>
        
        <form id="profileForm" class="space-y-6" onsubmit="updateProfile(event)">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="firstname">First Name</label>
                    <input type="text" id="firstname" name="firstname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" value="<?php echo $_SESSION['firstname'] ?>" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="lastname">Last Name</label>
                    <input type="text" id="lastname" name="lastname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" value="<?php echo $_SESSION['lastname'] ?>" required>
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="email">Email</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : '' ?>" required>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Enter current password to make changes" required>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="new_password">New Password (Optional)</label>
                    <input type="password" id="new_password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Leave blank to keep current">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="Leave blank to keep current">
                </div>
            </div>

            <div class="flex justify-end space-x-4">
                <button type="button" onclick="closeProfileModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 focus:ring-4 focus:ring-gray-200 transition-colors duration-200">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-colors duration-200">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Content -->
<main class="flex-1 p-6 md:p-8 overflow-y-auto bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Mobile Menu Toggle -->
    <div class="md:hidden flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">COLUMBOS</h1>
        <button @click="openSidebar = !openSidebar" class="p-2 rounded-lg hover:bg-gray-200 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>

    <!-- Dashboard Section -->
    <div x-show="activeSection === 'dashboard'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <!-- Welcome Header with Gradient Background -->
        <div class="relative overflow-hidden bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-8 text-white mb-8">
            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-2 text-green-700">Welcome Back, <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?>! 👋</h1>
                <p class="text-gray-500 text-lg">Here's what's happening with your account today.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-green-400 opacity-20 transform rotate-12"></div>
        </div>
        
        <!-- Profile Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-start space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-24 h-24 rounded-2xl flex items-center justify-center shadow-lg">
                        <span class="text-4xl font-bold text-green-700"><?php echo strtoupper(substr($_SESSION['firstname'], 0, 1) . substr($_SESSION['lastname'], 0, 1)) ?></span>
                    </div>
                </div>
                <div class="flex-grow">
                    <div class="flex justify-between items-start">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-1"><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?></h2>
                            <p class="text-gray-600 mb-3"><?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'Email not set' ?></p>
                        </div>
                        <button onclick="openProfileModal()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-xl hover:bg-green-700 focus:ring-4 focus:ring-green-200 transition-all duration-200 shadow-sm hover:shadow-md">
                            <span class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                                <span>Edit Profile</span>
                            </span>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-600 mb-1">Role</p>
                            <p class="font-semibold text-gray-800"><?php echo ucwords(str_replace('-', ' ', $_SESSION['role'])) ?></p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-600 mb-1">Member Since</p>
                            <p class="font-semibold text-gray-800"><?php echo date('F Y') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Applicants Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Applicants</h2>
                        <div class="flex items-baseline">
                <p class="text-3xl font-bold text-green-600"><?= $totalApplicants ?></p>
                            <p class="ml-2 text-sm text-gray-500">members</p>
                        </div>
                    </div>
                    <div class="p-3 bg-green-50 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>12% increase from last month</span>
                </div>
            </div>
            
            <!-- Pending Applications Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Pending Applications</h2>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-yellow-600">3</p>
                            <p class="ml-2 text-sm text-gray-500">pending</p>
                        </div>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded-xl">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 text-yellow-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Requires attention</span>
                </div>
            </div>
            
            <!-- Allocations Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Allocations</h2>
                        <div class="flex items-baseline">
                            <p class="text-3xl font-bold text-blue-600">₱<?php echo number_format($totals['total_contribution'] ?? 0, 2) ?></p>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>8% increase from last month</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Activity</h2>
            <div class="space-y-4">
                <!-- Activity Item -->
                <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                    <div class="flex-shrink-0 mr-4">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">New application submitted</p>
                        <p class="text-xs text-gray-500">2 hours ago</p>
                    </div>
                </div>
                <!-- Add more activity items as needed -->
            </div>
        </div>
    </div>

    <!-- Orders Section -->
    <div x-show="activeSection === 'orders'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <!-- Section Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-8 text-white mb-8">
            <div class="relative z-10">
                <h1 class="text-4xl font-bold mb-2 text-green-700">List of Applicants 👥</h1>
                <p class="text-gray-500 text-lg">View and manage your applicants.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-green-400 opacity-20 transform rotate-12"></div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="myTable" class="stripe hover w-full border-collapse" style="width:100%; font-size: 0.875rem;">
                <thead class="bg-gray-800 text-white text-xs uppercase">
                    <tr>
                            <th class="px-4 py-3 whitespace-nowrap">APPLICANT NAME</th>
                            <th class="px-4 py-3 whitespace-nowrap">PLAN TYPE</th>
                            <th class="px-4 py-3 whitespace-nowrap">PLAN NAME</th>
                            <th class="px-4 py-3 whitespace-nowrap">FACE VALUE</th>
                            <th class="px-4 py-3 whitespace-nowrap">YEARS TO MATURE</th>
                            <th class="px-4 py-3 whitespace-nowrap">YEARS PROTECT</th>
                            <th class="px-4 py-3 whitespace-nowrap">PAYMENT MODE</th>
                            <th class="px-4 py-3 whitespace-nowrap">CONTRIBUTION</th>
                            <th class="px-4 py-3 whitespace-nowrap">TOTAL</th>
                            <th class="px-4 py-3 whitespace-nowrap">INSURANCE COST</th>
                            <th class="px-4 py-3 whitespace-nowrap">ADMIN FEE</th>
                            <th class="px-4 py-3 whitespace-nowrap">SAVINGS FUND</th>
                            <th class="px-4 py-3 whitespace-nowrap">STATUS</th>
                            <th class="px-4 py-3 whitespace-nowrap">ACTIONS</th>
                    </tr>
                </thead>
                    <tbody class="text-sm">
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
                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-4 py-3 text-gray-700 font-medium whitespace-nowrap"><?php echo htmlspecialchars($applicant['applicant_name']) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap"><?php echo htmlspecialchars($applicant['plan_type']) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap"><?php echo htmlspecialchars($applicant['plan_name']) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">₱<?php echo htmlspecialchars(number_format($applicant['face_value'], 2)) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap"><?php echo htmlspecialchars($applicant['years_to_maturity']) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap"><?php echo htmlspecialchars($applicant['years_of_protection']) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap"><?php echo htmlspecialchars($applicant['payment_mode']) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">₱<?php echo number_format($applicant['contribution_amount'], 2) ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">₱<?php echo number_format($applicant['total_contribution'], 2); ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">₱<?php echo number_format($applicant['insurance_cost'], 2); ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">₱<?php echo number_format($applicant['admin_fee'], 2); ?></td>
                            <td class="px-4 py-3 text-gray-700 whitespace-nowrap">₱<?php echo number_format($applicant['savings_fund'], 2); ?></td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 text-xs rounded-full font-medium 
                                    <?php 
                                        $status = strtolower($applicant['application_status']);
                                        if ($status === 'approved') echo 'bg-green-100 text-green-700';
                                        elseif ($status === 'pending') echo 'bg-yellow-100 text-yellow-700';
                                        else echo 'bg-gray-100 text-gray-700';
                                    ?>">
                                    <?php echo htmlspecialchars($applicant['application_status']) ?>
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                            <a href="moreapplicationdetails.php?id=<?php echo $applicant['applicant_id']?>&user_id=<?php echo $applicant['user_id']?>"
                                    class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                    Details
                            </a>
                        </td>
                    </tr>
                    <?php
                        }
                        } else {
                            if (empty($councils) || empty($fraternals)) {
                                    echo "<tr><td colspan='14' class='px-4 py-3 text-center'>No councils or fraternal benefits found.</td></tr>";
                            } else {
                                    echo "<tr><td colspan='14' class='px-4 py-3 text-center'>No data available.</td></tr>";
                            }
                        }
                    ?>
                </tbody>
            </table>
            </div>
        </div>
    </div>

    <!-- Forms Section -->
    <div x-show="activeSection === 'forms'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <!-- Section Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-8 text-white mb-8">
            <div class="relative z-10">
                <h1 class="text-4xl text-green-700 font-bold mb-2">Forms Library 📚</h1>
                <p class="text-blue-700 text-lg">Access and manage all your important documents.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-blue-400 opacity-20 transform rotate-12"></div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <table id="myTable2" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                        <th class="px-4 py-3 rounded-l-lg">FILENAME</th>
                                <th class="px-4 py-3">DESCRIPTION</th>
                                <th class="px-4 py-3">TYPE</th>
                                <th class="px-4 py-3">UPLOADED</th>
                        <th class="px-4 py-3 rounded-r-lg">ACTIONS</th>
                            </tr>
                        </thead>
                <tbody class="text-sm">
                            <?php while ($row = mysqli_fetch_assoc($files)): ?>
                    <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($row['filename'])?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo $row['description'] ?></td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 text-xs rounded-full font-medium <?php echo strtolower($row['file_type']) === 'pdf' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' ?>">
                                <?php echo htmlspecialchars($row['file_type'])?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700"><?php echo date("F j, Y", strtotime($row['uploaded_on']))?></td>
                        <td class="px-4 py-3 space-x-2">
                                    <a href="<?php echo BASE_URL?>controllers/adminController/view_docx.php?path=uploads/forms/<?php echo basename($row['file_located'])?>"
                                target="_blank" 
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                                    <a href="<?php echo BASE_URL?>controllers/adminController/formControllers.php?download=<?php echo $row['id']?>"
                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-blue-700 bg-blue-100 hover:bg-blue-200 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                Download
                            </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
        </div>
    </div>

    <!-- Councils Section -->
    <div x-show="activeSection === 'council'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <!-- Section Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-8 text-white mb-8">
            <div class="relative z-10">
                <h1 class="text-4xl text-green-700 font-bold mb-2">Council Directory 🏛️</h1>
                <p class="text-purple-500 text-lg">View and manage council information.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-purple-400 opacity-20 transform rotate-12"></div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <table id="myTable3" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                        <th class="px-4 py-3 rounded-l-lg">Council Number</th>
                                <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Unit Manager</th>
                        <th class="px-4 py-3">Fraternal Counselor</th>
                        <th class="px-4 py-3 rounded-r-lg">Established</th>
                            </tr>
                        </thead>
                <tbody class="text-sm">
                            <?php
                                $councilModel = new CouncilModel($conn);
                                $councils     = $councilModel->getAllCouncil();

                                if ($councils) {
                                    foreach ($councils as $council) {
                                        $um_name = $councilModel->getUserNameById($council['unit_manager_id'], 'unit-manager');
                                $fc_name = $councilModel->getUserNameById($council['fraternal_counselor_id'], 'fraternal-counselor');
                    ?>
                    <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-3 text-gray-700 font-medium"><?php echo $council['council_number'] ?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo $council['council_name'] ?></td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center mr-3">
                                    <span class="text-blue-700 font-medium"><?php echo substr($um_name, 0, 1) ?></span>
                                </div>
                                <span class="text-gray-700"><?php echo $um_name ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 rounded-xl flex items-center justify-center mr-3">
                                    <span class="text-green-700 font-medium"><?php echo substr($fc_name, 0, 1) ?></span>
                                </div>
                                <span class="text-gray-700"><?php echo $fc_name ?></span>
                            </div>
                                </td>
                        <td class="px-4 py-3 text-gray-700"><?php echo date("F j, Y", strtotime($council['date_established'])); ?></td>
                            </tr>
                            <?php
                                }
                                } else {
                            echo "<tr><td colspan='5' class='px-4 py-3 text-center text-gray-500'>No councils found.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
        </div>
    </div>

    <!-- Fraternal Benefits Section -->
    <div x-show="activeSection === 'fraternalbenefits'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <!-- Section Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-indigo-500 to-indigo-600 rounded-2xl p-8 text-white mb-8">
            <div class="relative z-10">
                <h1 class="text-4xl text-green-700 font-bold mb-2">Fraternal Benefits 🎯</h1>
                <p class="text-indigo-500 text-lg">Explore available benefit plans and packages.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-indigo-400 opacity-20 transform rotate-12"></div>
    </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <table id="myTable4" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                        <th class="px-4 py-3 rounded-l-lg">Type</th>
                        <th class="px-4 py-3">Name</th>
                        <th class="px-4 py-3">Contribution Period</th>
                        <th class="px-4 py-3 rounded-r-lg">Actions</th>
                            </tr>
                        </thead>
                <tbody class="text-sm">
                            <?php
                                $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
                                $fraternals = $fraternalBenefitsModel->getAllFraternalBenefits();

                                if ($fraternals) {
                            foreach ($fraternals as $fraternal) {
                    ?>
                    <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 text-xs rounded-full font-medium <?php echo strtolower($fraternal['type']) === 'premium' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                                <?php echo $fraternal['type']; ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 text-gray-700 font-medium"><?php echo $fraternal['name']; ?></td>
                        <td class="px-4 py-3 text-gray-700"><?php echo $fraternal['contribution_period']; ?></td>
                        <td class="px-4 py-3">
                                    <a href="moreplandetails.php?id=<?php echo $fraternal['id']?>"
                                class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                View Details
                                    </a>
                                </td>
                            </tr>
                    <?php
                            }
                                } else {
                            echo "<tr><td colspan='4' class='px-4 py-3 text-center text-gray-500'>No fraternal benefits found.</td></tr>";
                                }
                            ?>
                        </tbody>
                    </table>
        </div>
    </div>

    <!-- Announcement Section -->
    <div x-show="activeSection === 'announcement'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
        <!-- Section Header -->
        <div class="relative overflow-hidden bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-8 text-white mb-8">
            <div class="relative z-10">
                <h1 class="text-4xl text-green-700 font-bold mb-2">Announcements 📢</h1>
                <p class="text-yellow-500 text-lg">Stay updated with the latest news and updates.</p>
            </div>
            <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-yellow-400 opacity-20 transform rotate-12"></div>
        </div>

        <div class="space-y-6">
            <?php foreach ($announcements as $index => $announcement): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($announcement['subject']) ?></h3>
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                <?= date("M d, Y", strtotime($announcement['date_posted'])) ?>
                            </span>
                        </div>
                        <p class="text-gray-700 leading-relaxed mb-4"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center text-green-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <span>Delivered</span>
                            </div>
                            <div class="flex items-center text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span><?= date("h:i A", strtotime($announcement['date_posted'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
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
            <div class="flex justify-between py-3 border-b border-gray-100">
                <span class="font-medium text-gray-600">${label}:</span>
                <span class="text-gray-800">${data[key]}</span>
            </div>`;
    }

    modalContent.innerHTML = html;
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    // Add fade-in animation
    modalContent.style.opacity = '0';
    setTimeout(() => {
        modalContent.style.transition = 'opacity 300ms ease-out';
        modalContent.style.opacity = '1';
    }, 50);
}

function closeModal() {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('modalContent');

    // Add fade-out animation
    modalContent.style.opacity = '0';
    setTimeout(() => {
    modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 300);
}
</script>

<script>
$(document).ready(function() {
    const tableConfig = {
        responsive: true,
        dom: '<"flex justify-between items-center mb-4"Bf>rt<"flex justify-between items-center mt-4"lip>',
        buttons: [
            {
                extend: 'copy',
                className: 'px-3 py-2 text-xs font-medium text-white bg-gray-600 rounded hover:bg-gray-700 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500',
            },
            {
                extend: 'csv',
                className: 'px-3 py-2 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500',
            },
            {
                extend: 'excel',
                className: 'px-3 py-2 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500',
            },
            {
                extend: 'pdf',
                className: 'px-3 py-2 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:ring-2 focus:ring-offset-2 focus:ring-red-500',
            },
            {
                extend: 'print',
                className: 'px-3 py-2 text-xs font-medium text-white bg-purple-600 rounded hover:bg-purple-700 focus:ring-2 focus:ring-offset-2 focus:ring-purple-500',
            }
        ],
        pageLength: 10,
        language: {
            search: "Search:",
            lengthMenu: "Show _MENU_ entries",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    };

    $('#myTable2').DataTable(tableConfig);
    $('#myTable3').DataTable(tableConfig);
    $('#myTable4').DataTable(tableConfig);
});
</script>

<script>
function openProfileModal() {
    const modal = document.getElementById('profileModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeProfileModal() {
    const modal = document.getElementById('profileModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function updateProfile(event) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    
    // Validate passwords if new password is provided
    if (formData.get('new_password')) {
        if (formData.get('new_password') !== formData.get('confirm_password')) {
            alert('New passwords do not match!');
            return;
        }
    }
    
    // Send update request
    fetch('../../controllers/userController/update_profile.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Profile updated successfully!');
            location.reload(); // Reload page to reflect changes
        } else {
            alert(data.message || 'Error updating profile');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating your profile');
    });
}

// Close modal when clicking outside
document.getElementById('profileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProfileModal();
    }
});
</script>

<?php
include '../../includes/footer.php';
?>