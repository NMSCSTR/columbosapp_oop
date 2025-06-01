<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'unit-manager', 'fraternal-counselor']);
include '../../includes/config.php';
include '../../includes/header.php';
include '../../includes/db.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/userModel.php';
include '../../models/memberModel/memberApplicationModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../includes/alert2.php';

if (! isset($_GET['id'])) {
    die("ID is required.");
}

$id                     = intval($_GET['id']);
$user_id                = intval($_GET['user_id']);
$councilModel           = new CouncilModel($conn);
$applicationModel       = new MemberApplicationModel($conn);
$fraternalBenefitsModel = new fraternalBenefitsModel($conn);
$fraternals             = $fraternalBenefitsModel->getAllFraternalBenefits();
$councils               = $councilModel->getAllCouncil();
$applicantData          = $applicationModel->fetchAllApplicantById($user_id);
?>

<!-- TailwindCSS CDN for additional components -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="fraternalcounselor.php" class="inline-flex items-center text-gray-700 hover:text-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                        Home
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                        <span class="text-gray-500 ml-1 md:ml-2 font-medium">Application Details</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Back Button -->
        <div class="mb-6">
            <a href="fraternalcounselor.php" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
        </div>

        <!-- Main Content -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">Applicant Details</h2>
                <p class="mt-1 text-sm text-gray-600">Complete information about the applicant</p>
            </div>

            <div class="divide-y divide-gray-200">
                <!-- Personal Information Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Personal Information</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Full Name</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['firstname'] . ' ' . $applicantData['middlename'] . ' ' . $applicantData['lastname'] ?></p>
                        </div>
                        
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Age</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['age'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Birthdate</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['birthdate'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Gender</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['gender'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Marital Status</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['marital_status'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Nationality</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['nationality'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Contact Information</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Mobile Number</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['mobile_number'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Email Address</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['email_address'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Complete Address</label>
                            <p class="text-base text-gray-900">
                                <?php echo $applicantData['street'] . ', ' . $applicantData['barangay'] . ', ' . $applicantData['city_province'] ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Employment Details Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Employment Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Occupation</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['occupation'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Employment Status</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['employment_status'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Monthly Income</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['monthly_income'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Employer</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['employer'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Nature of Business</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['nature_business'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Duties</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['duties'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Plan Details Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Plan Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Fraternal Benefits</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['fraternal_benefits_id'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Council</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['council_id'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Payment Mode</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['payment_mode'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Contribution Amount</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['contribution_amount'] ?> <?php echo $applicantData['currency'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Medical History Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Medical History</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Past Illness</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['past_illness'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Current Medication</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['current_medication'] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Family Health History Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                        <h3 class="text-lg font-medium text-gray-900">Family Health History</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Father's Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Father's Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Living Age</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['father_living_age'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Health Status</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['father_health'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Death Age</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['father_death_age'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Cause of Death</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['father_cause'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Mother's Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Mother's Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Living Age</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['mother_living_age'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Health Status</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['mother_health'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Death Age</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['mother_death_age'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Cause of Death</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['mother_cause'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Siblings Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Siblings Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Living</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['siblings_living'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Deceased</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['siblings_deceased'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Health Status</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['siblings_health'] ?></p>
                                </div>
                            </div>
                        </div>

                        <!-- Children Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium text-gray-900 mb-2">Children Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Living</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['children_living'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Deceased</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['children_deceased'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Health Status</label>
                                    <p class="text-base text-gray-900"><?php echo $applicantData['children_health'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Physician Details Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                        <h3 class="text-lg font-medium text-gray-900">Physician Details</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Physician Name</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['physician_name'] ?></p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Contact Number</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['contact_number'] ?></p>
        </div>

                        <div class="space-y-1">
                            <label class="text-sm font-medium text-gray-500">Address</label>
                            <p class="text-base text-gray-900"><?php echo $applicantData['physician_address'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

<?php
include '../../includes/footer.php';
?>