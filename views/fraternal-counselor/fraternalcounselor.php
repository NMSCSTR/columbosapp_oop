<?php
    require_once '../../middleware/auth.php';
    authorize(['admin', 'fraternal-counselor']);
    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/usersModel.php';
    include '../../models/adminModel/councilModel.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
    include '../../models/memberModel/memberApplicationModel.php';
    include '../../models/adminModel/announcementModel.php';
    include '../../models/adminModel/FormsModel.php';
    include '../../models/adminModel/setQoutaModel.php';

    // Debug session data
    $userModel   = new UserModel($conn);
    $currentUser = $userModel->getUserById($_SESSION['user_id']);
     // echo $currentUser['firstname'];
    if (! isset($_SESSION['email']) && $currentUser) {
        $_SESSION['email'] = $currentUser['email'];
    }

    $currentYear   = date('Y');
    $currentMonth  = date('m');
    $lastMonth     = date('m', strtotime("-1 month"));
    $lastMonthYear = date('Y', strtotime("-1 month"));

    $councilModel           = new CouncilModel($conn);
    $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
    $applicationModel       = new MemberApplicationModel($conn);
    $formsModel             = new FormsModel($conn);
    $announcementModel      = new announcementModel($conn);
    $quotaModel             = new setQoutaModel($conn);
    $thisMonthTotal         = $applicationModel->calculateMonthlyAllocationsByCouncil($_SESSION['user_id'], $currentYear, $currentMonth);
    $lastMonthTotal         = $applicationModel->calculateMonthlyAllocationsByCouncil($_SESSION['user_id'], $lastMonthYear, $lastMonth);
    $pending_application    = $applicationModel->fetchPendingApplicantByCouncil($_SESSION['user_id']);
    $fraternalCounselors    = $quotaModel->getAllFraternalCounselorWithQuota();
    $announcements          = $announcementModel->getAllAnnouncement();
    $totalApplicants        = $applicationModel->countAllApplicants($_SESSION['user_id']);
    $totals                 = $applicationModel->calculateTotalAllocationsForAllApplicants();
    $totalsByFraternalCounselor = $applicationModel->calculateAllTotalAllocationsByFraternalCounselor($_SESSION['user_id']);
    $files                  = $formsModel->viewAllForms();
    
    // Get quota data for current fraternal counselor
    $currentUserQuota = $quotaModel->checkExistingQuota($_SESSION['user_id']);
    $currentFaceValue = $quotaModel->calculateAllApplicantsFaceValueByFraternalCounselor($_SESSION['user_id']);

    if ($lastMonthTotal > 0) {
        $growth = (($thisMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100;
    } else {
        $growth = 0;
    }

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
            <h1 class="text-4xl font-bold mb-2 text-green-700">Welcome Back,                                                                                                                                                         <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?>! 👋</h1>
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
            <p class="text-3xl font-bold text-green-600"><?php echo $totalApplicants ?></p>
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
                        <p class="text-3xl font-bold text-yellow-600"><?php echo $pending_application; ?></p>
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
                        <p class="text-3xl font-bold text-blue-600">₱<?php echo number_format($totalsByFraternalCounselor   ['total_contribution'] ?? 0, 2) ?></p>
                    </div>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
                <div class="mt-4 flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4                                                                               <?php echo($growth >= 0) ? 'text-green-500' : 'text-red-500'; ?> mr-1"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="<?php echo($growth >= 0) ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M11 17h-8m0 0v-8m0 8l8-8 4 4 6-6'; ?>" />
                    </svg>
                    <span>
                        <?php echo number_format(abs($growth), 2); ?>%
                        <?php echo($growth >= 0) ? 'increase' : 'decrease'; ?> from last month
                    </span>
                </div>
        </div>
    </div>

    <!-- Financial Overview Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Financial Overview</h2>
            <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>All Time Records</span>
            </div>
        </div>
        
        <!-- Financial Metrics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Applicants Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-blue-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-3xl font-bold text-blue-700"><?php echo $totalsByFraternalCounselor['total_applicants'] ?></p>
                        <p class="text-sm text-blue-600 font-medium">Total Applicants</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-blue-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Active members</span>
                </div>
            </div>

            <!-- Total Face Value Card -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-green-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-700">₱<?php echo number_format($totalsByFraternalCounselor['total_face_value'], 0) ?></p>
                        <p class="text-sm text-green-600 font-medium">Total Face Value</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-green-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Insurance coverage</span>
                </div>
            </div>

            <!-- Total Contributions Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-3 bg-purple-500 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-purple-700">₱<?php echo number_format($totalsByFraternalCounselor['total_contribution'], 0) ?></p>
                        <p class="text-sm text-purple-600 font-medium">Total Contributions</p>
                    </div>
                </div>
                <div class="flex items-center text-sm text-purple-600">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span>Member payments</span>
                </div>
            </div>
        </div>

        <!-- Financial Breakdown Section -->
        <div class="mt-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Financial Breakdown</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Insurance Cost -->
                <div class="bg-gradient-to-r from-red-50 to-red-100 rounded-lg p-4 border border-red-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-red-600 mb-1">Insurance Cost</p>
                            <p class="text-xl font-bold text-red-700">₱<?php echo number_format($totalsByFraternalCounselor['insurance_cost'], 0) ?></p>
                        </div>
                        <div class="p-2 bg-red-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Admin Fee -->
                <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-lg p-4 border border-yellow-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-yellow-600 mb-1">Admin Fee</p>
                            <p class="text-xl font-bold text-yellow-700">₱<?php echo number_format($totalsByFraternalCounselor['admin_fee'], 0) ?></p>
                        </div>
                        <div class="p-2 bg-yellow-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Savings Fund -->
                <div class="bg-gradient-to-r from-emerald-50 to-emerald-100 rounded-lg p-4 border border-emerald-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-emerald-600 mb-1">Savings Fund</p>
                            <p class="text-xl font-bold text-emerald-700">₱<?php echo number_format($totalsByFraternalCounselor['savings_fund'], 0) ?></p>
                        </div>
                        <div class="p-2 bg-emerald-500 rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="mt-6 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="p-3 bg-gray-600 rounded-xl mr-4">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800">Financial Summary</h4>
                        <p class="text-sm text-gray-600">Complete overview of your financial records</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Last updated</p>
                    <p class="text-sm font-medium text-gray-700"><?php echo date('M d, Y') ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quota Progress Section -->
    <?php if ($currentUserQuota): ?>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Quota Progress</h2>
            <div class="flex items-center text-sm text-gray-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span>Target Achievement</span>
            </div>
        </div>

        <!-- Quota Progress Card -->
        <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl p-6 border border-indigo-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-indigo-800 mb-2">Your Quota Target</h3>
                    <p class="text-sm text-indigo-600">Target: ₱<?php echo number_format($currentUserQuota['qouta'], 0) ?> | Current: ₱<?php echo number_format($currentFaceValue, 0) ?></p>
                </div>
                <div class="text-right">
                    <div class="p-3 bg-indigo-500 rounded-xl">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <?php 
                $progressPercentage = $currentUserQuota['qouta'] > 0 ? min(($currentFaceValue / $currentUserQuota['qouta']) * 100, 100) : 0;
                $isCompleted = $currentFaceValue >= $currentUserQuota['qouta'];
                $isExpired = strtotime($currentUserQuota['duration']) < time();
            ?>
            <div class="mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-indigo-700">Progress</span>
                    <span class="text-sm font-bold text-indigo-800"><?php echo number_format($progressPercentage, 1) ?>%</span>
                </div>
                <div class="w-full bg-indigo-200 rounded-full h-3">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 h-3 rounded-full transition-all duration-500 ease-out" 
                         style="width: <?php echo $progressPercentage ?>%"></div>
                </div>
            </div>

            <!-- Quota Details Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Target Amount -->
                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Target Amount</p>
                            <p class="text-xl font-bold text-indigo-700">₱<?php echo number_format($currentUserQuota['qouta'], 0) ?></p>
                        </div>
                        <div class="p-2 bg-indigo-100 rounded-lg">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Current Achievement -->
                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Current Achievement</p>
                            <p class="text-xl font-bold text-green-700">₱<?php echo number_format($currentFaceValue, 0) ?></p>
                        </div>
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Remaining Amount -->
                <div class="bg-white rounded-lg p-4 border border-indigo-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 mb-1">Remaining</p>
                            <p class="text-xl font-bold <?php echo ($currentUserQuota['qouta'] - $currentFaceValue) <= 0 ? 'text-green-700' : 'text-orange-700' ?>">
                                ₱<?php echo number_format(max(0, $currentUserQuota['qouta'] - $currentFaceValue), 0) ?>
                            </p>
                        </div>
                        <div class="p-2 <?php echo ($currentUserQuota['qouta'] - $currentFaceValue) <= 0 ? 'bg-green-100' : 'bg-orange-100' ?> rounded-lg">
                            <svg class="w-5 h-5 <?php echo ($currentUserQuota['qouta'] - $currentFaceValue) <= 0 ? 'text-green-600' : 'text-orange-600' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status and Deadline -->
            <div class="mt-6 flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-4">
                    <!-- Status Badge -->
                    <div class="flex items-center">
                        <span class="px-3 py-1 text-xs font-medium rounded-full <?php 
                            if ($isCompleted) {
                                echo 'bg-green-100 text-green-700';
                            } elseif ($isExpired) {
                                echo 'bg-red-100 text-red-700';
                            } else {
                                echo 'bg-yellow-100 text-yellow-700';
                            }
                        ?>">
                            <?php 
                                if ($isCompleted) {
                                    echo 'Completed';
                                } elseif ($isExpired) {
                                    echo 'Expired';
                                } else {
                                    echo 'In Progress';
                                }
                            ?>
                        </span>
                    </div>
                    
                    <!-- Duration Info -->
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Deadline: <?php echo date('M d, Y', strtotime($currentUserQuota['duration'])) ?></span>
                    </div>
                </div>

                <!-- Progress Indicator -->
                <div class="flex items-center text-sm <?php echo $isCompleted ? 'text-green-600' : 'text-indigo-600' ?>">
                    <?php if ($isCompleted): ?>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Quota Achieved!</span>
                    <?php else: ?>
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="font-medium">Keep going!</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <!-- No Quota Set -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mt-8">
        <div class="text-center py-8">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Quota Set</h3>
            <p class="text-gray-600 mb-4">You don't have an active quota target assigned yet.</p>
            <p class="text-sm text-gray-500">Contact your administrator to set up your quota target.</p>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Orders Section -->
<div x-show="activeSection === 'orders'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
    <!-- Section Header -->
    <div class="relative overflow-hidden bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-8 text-white mb-8">
        <div class="relative z-10">
            <h1 class="text-4xl font-bold mb-2 text-green-700">List of Applicants 👥</h1>
            <p class="text-gray-500 text-lg">View your applicants.</p>
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
                                            if ($status === 'approved') {
                                                echo 'bg-green-100 text-green-700';
                                            } elseif ($status === 'pending') {
                                                echo 'bg-yellow-100 text-yellow-700';
                                            } else {
                                                echo 'bg-gray-100 text-gray-700';
                                            }

                                        ?>">
                                <?php echo htmlspecialchars($applicant['application_status']) ?>
                            </span>
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                        <a href="moreapplicationdetails.php?id=<?php echo $applicant['applicant_id'] ?>&user_id=<?php echo $applicant['user_id'] ?>"
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
                    <td class="px-4 py-3 text-gray-700"><?php echo htmlspecialchars($row['filename']) ?></td>
                    <td class="px-4 py-3 text-gray-700"><?php echo $row['description'] ?></td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 text-xs rounded-full font-medium                                                                                                                                                               <?php echo strtolower($row['file_type']) === 'pdf' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' ?>">
                            <?php echo htmlspecialchars($row['file_type']) ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700"><?php echo date("F j, Y", strtotime($row['uploaded_on'])) ?></td>
                    <td class="px-4 py-3 space-x-2">
                                <a href="<?php echo BASE_URL ?>controllers/adminController/view_docx.php?path=uploads/forms/<?php echo basename($row['file_located']) ?>"
                            target="_blank"
                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg text-green-700 bg-green-100 hover:bg-green-200 transition-colors duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View
                        </a>
                                <a href="<?php echo BASE_URL ?>controllers/adminController/formControllers.php?download=<?php echo $row['id'] ?>"
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
            <p class="text-purple-500 text-lg">View council information.</p>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/2 bg-gradient-to-l from-purple-400 opacity-20 transform rotate-12"></div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <table id="myTable3" class="stripe hover w-full" style="width:100%">
                    <thead class="bg-gray-800 text-white text-xs uppercase">
                        <tr>
                            <th class="px-4 py-3 whitespace-nowrap">COUNCIL NUMBER</th>
                            <th class="px-4 py-3 whitespace-nowrap">NAME</th>
                            <th class="px-4 py-3 whitespace-nowrap">UNIT MANAGER</th>
                            <th class="px-4 py-3 whitespace-nowrap">FRATERNAL COUNSELOR</th>
                            <th class="px-4 py-3 whitespace-nowrap">ESTABLISHED</th>
                            <th class="px-4 py-3 whitespace-nowrap">ACTIONS</th>
                        </tr>
                    </thead>
                     <tbody class="text-sm">
                        <?php
                            $councilModel = new CouncilModel($conn);
                            $councils     = $councilModel->getAllCouncilByFc($_SESSION['user_id']);

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
                                <td class="px-4 py-3">
                                    <a href="roasters.php?council_id=<?php echo $council['council_id'] ?>"
                                       class="inline-flex items-center px-3 py-2 text-xs font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 transition-colors duration-200 shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        View Roasters
                                    </a>
                                </td>
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
                            $fraternals             = $fraternalBenefitsModel->getAllFraternalBenefits();

                            if ($fraternals) {
                                foreach ($fraternals as $fraternal) {
                                ?>
                <tr class="border-b hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 text-xs rounded-full font-medium                                                                                                                                                               <?php echo strtolower($fraternal['type']) === 'premium' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                            <?php echo $fraternal['type']; ?>
                        </span>
                    </td>
                    <td class="px-4 py-3 text-gray-700 font-medium"><?php echo $fraternal['name']; ?></td>
                    <td class="px-4 py-3 text-gray-700"><?php echo $fraternal['contribution_period']; ?></td>
                    <td class="px-4 py-3">
                                <a href="moreplandetails.php?id=<?php echo $fraternal['id'] ?>"
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
            <p class="text-yellow-500 text-lg">Stay updated with the latest announcements and updates.</p>
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
                        <h3 class="text-lg font-semibold text-gray-900"><?php echo htmlspecialchars($announcement['subject']) ?></h3>
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            <?php echo date("M d, Y", strtotime($announcement['date_posted'])) ?>
                        </span>
                    </div>
                    <p class="text-gray-700 leading-relaxed mb-4"><?php echo nl2br(htmlspecialchars($announcement['content'])) ?></p>
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
                            <span><?php echo date("h:i A", strtotime($announcement['date_posted'])) ?></span>
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