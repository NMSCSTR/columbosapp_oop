<?php
require_once '../../middleware/auth.php';
authorize(['member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/memberModel/memberApplicationModel.php';
include '../../includes/functions.php';

$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user_id']);
$fraternalCounselor = $userModel->getUserWhereRoleFraternalCounselor();

$applicationModel = new MemberApplicationModel($conn);
$applicantData = $applicationModel->fetchAllApplicantById($_SESSION['user_id']);

// Calculate form progress
$totalFields = 40; // Total number of form fields
$filledFields = 0;
foreach ($applicantData as $field => $value) {
    if (!empty($value) && $value !== '0') {
        $filledFields++;
    }
}
$progress = ($filledFields / $totalFields) * 100;

// Add this after fetching applicant data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['field_name'])) {
    // Handle AJAX auto-save request
    $field_name = $_POST['field_name'];
    $field_value = $_POST['field_value'];
    
    // Update the specific field in the database
    $result = $applicationModel->updateField($_SESSION['user_id'], $field_name, $field_value);
    
    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode(['success' => $result]);
    exit;
}

?>

<!-- Progress Bar -->
<div class="fixed top-0 left-0 w-full h-2 bg-gray-200 z-50">
    <div class="h-full bg-blue-600 transition-all duration-500" style="width: <?= $progress ?>%"></div>
</div>

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg border border-gray-200 my-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold mb-4 text-gray-800">
            KCFAPI Personal Information Sheet
        </h1>
        <p class="text-gray-600 text-lg mb-2">
            Complete your application form below
        </p>
        <div class="flex items-center justify-center gap-2 text-sm text-gray-500">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fill-rule="evenodd"
                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                    clip-rule="evenodd" />
            </svg>
            <span>All fields marked with <span class="text-red-500">*</span> are required</span>
        </div>
    </div>

    <form action="" method="post" class="space-y-12">
        <!-- Applicant Information -->
        <section
            class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg">
            <header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <div>
                    <h2 class="text-xl text-gray-900 font-semibold">Applicant Information</h2>
                    <p class="text-sm text-gray-400">Basic personal details</p>
                </div>
            </header>

            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">First Name <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="firstname" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['firstname']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Last Name <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="lastname" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['lastname']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Middle Name</span>
                        <input type="text" name="middlename" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['middlename']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Age <span class="text-red-500">*</span></span>
                        <input type="number" name="age" min="0" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['age']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Birthdate <span
                                class="text-red-500">*</span></span>
                        <input type="date" name="birthdate" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['birthdate']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Birthplace <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="birthplace" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['birthplace']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Gender <span
                                class="text-red-500">*</span></span>
                        <select name="gender" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
                            <option value="">Select gender</option>
                            <option value="Male" <?= $applicantData['gender'] === 'Male' ? 'selected' : '' ?>>Male
                            </option>
                            <option value="Female" <?= $applicantData['gender'] === 'Female' ? 'selected' : '' ?>>Female
                            </option>
                            <option value="Other" <?= $applicantData['gender'] === 'Other' ? 'selected' : '' ?>>Other
                            </option>
                        </select>
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Marital Status</span>
                        <input type="text" name="marital_status" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['marital_status']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">TIN/SSS</span>
                        <input type="text" name="tin_sss" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['tin_sss']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Nationality</span>
                        <input type="text" name="nationality" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['nationality']) ?>" />
                    </label>
                </div>
            </div>
        </section>

        <!-- Contact Details with similar enhanced styling -->
        <section
            class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg">
            <header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <div>
                    <h2 class="text-xl text-gray-900 font-semibold">Contact Details</h2>
                    <p class="text-sm text-gray-400">How we can reach you</p>
                </div>
            </header>

            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Mobile Number <span
                                class="text-red-500">*</span></span>
                        <input type="tel" name="mobile_number" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['mobile_number']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Email Address <span
                                class="text-red-500">*</span></span>
                        <input type="email" name="email_address" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['email_address']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Barangay <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="barangay" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['barangay']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">City/Province <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="city_province" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['city_province']) ?>" />
                    </label>

                    <label class="block md:col-span-2">
                        <span class="text-gray-700 text-sm font-medium">Street <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="street" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['street']) ?>" />
                    </label>
                </div>
            </div>
        </section>

        <!-- Employment Details -->
        <section
            class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg">
            <header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <div>
                    <h2 class="text-xl text-gray-900 font-semibold">Employment Details</h2>
                    <p class="text-sm text-gray-400">Your current job details</p>
                </div>
            </header>

            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Occupation <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="occupation" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['occupation']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Employment Status <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="employment_status" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['employment_status']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Duties</span>
                        <input type="text" name="duties" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['duties']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Employer <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="employer" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['employer']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Nature of Business</span>
                        <input type="text" name="nature_business" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['nature_business']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Employer Mobile Number</span>
                        <input type="tel" name="employer_mobile_number" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['employer_mobile_number']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Employer Email Address</span>
                        <input type="email" name="employer_email_address" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['employer_email_address']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Monthly Income</span>
                        <input type="number" step="0.01" name="monthly_income" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['monthly_income']) ?>" />
                    </label>
                </div>
            </div>
        </section>

        <!-- Plan Details -->
        <section
            class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg">
            <header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <div>
                    <h2 class="text-xl text-gray-900 font-semibold">Plan Details</h2>
                    <p class="text-sm text-gray-400">Your fraternal benefits details</p>
                </div>
            </header>

            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Fraternal Benefits ID</span>
                        <input type="text" name="fraternal_benefits_id" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['fraternal_benefits_id']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Council ID</span>
                        <input type="text" name="council_id" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['council_id']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Payment Mode</span>
                        <input type="text" name="payment_mode" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['payment_mode']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Contribution Amount</span>
                        <input type="number" step="0.01" name="contribution_amount" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['contribution_amount']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Currency</span>
                        <input type="text" name="currency" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['currency']) ?>" />
                    </label>
                </div>
            </div>
        </section>

        <!-- Beneficiary Details -->
        <section
            class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg">
            <header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <div>
                    <h2 class="text-xl text-gray-900 font-semibold">Beneficiary Details</h2>
                    <p class="text-sm text-gray-400">Details of the person you're nominating</p>
                </div>
            </header>

            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Beneficiary Type <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="benefit_type" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['benefit_type']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Beneficiary Name <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="benefit_name" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['benefit_name']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Beneficiary Birthday <span
                                class="text-red-500">*</span></span>
                        <input type="date" name="benefit_birthdate" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['benefit_birthdate']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Relationship <span
                                class="text-red-500">*</span></span>
                        <input type="text" name="benefit_relationship" required class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['benefit_relationship']) ?>" />
                    </label>
                </div>
            </div>
        </section>

        <!-- Family Background -->
        <section
            class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg">
            <header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <div>
                    <h2 class="text-xl text-gray-900 font-semibold text-dark">Family Background Details</h2>
                    <p class="text-sm text-gray-400">Details about your family</p>
                </div>
            </header>

            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Father Lastname</span>
                        <input type="text" name="father_lastname" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['father_lastname']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Father Firstname</span>
                        <input type="text" name="father_firstname" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['father_firstname']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Father MI</span>
                        <input type="text" name="father_mi" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['father_mi']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Mother Lastname</span>
                        <input type="text" name="mother_lastname" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['mother_lastname']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Mother Firstname</span>
                        <input type="text" name="mother_firstname" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['mother_firstname']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Mother MI</span>
                        <input type="text" name="mother_mi" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['mother_mi']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Siblings Living</span>
                        <input type="number" name="siblings_living" min="0" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['siblings_living']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Siblings Deceased</span>
                        <input type="number" name="siblings_deceased" min="0" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['siblings_deceased']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Children Living</span>
                        <input type="number" name="children_living" min="0" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['children_living']) ?>" />
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Children Deceased</span>
                        <input type="number" name="children_deceased" min="0" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                            value="<?= htmlspecialchars($applicantData['children_deceased']) ?>" />
                    </label>
                </div>
            </div>
        </section>

        <!-- Medical and Family Health History -->
        <section
            class="bg-white rounded-xl shadow-md overflow-hidden transform transition-all duration-300 hover:shadow-lg">
            <header class="bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-4 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <div>
                    <h2 class="text-xl text-gray-900 font-semibold">Medical and Family Health History</h2>
                    <p class="text-sm text-gray-400">Details about your health</p>
                </div>
            </header>

            <div class="p-6 bg-gray-50">
                <div class="grid grid-cols-1 gap-6">
                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Medical History</span>
                        <textarea name="medical_history" rows="3"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"><?= htmlspecialchars($applicantData['medical_history'] ?? '') ?></textarea>
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Family Health History</span>
                        <textarea name="family_health_history" rows="3"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"><?= htmlspecialchars($applicantData['family_health_history'] ?? '') ?></textarea>
                    </label>

                    <label class="block">
                        <span class="text-gray-700 text-sm font-medium">Other Details</span>
                        <textarea name="other_details" rows="3"
                            class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md text-sm shadow-sm placeholder-gray-400
                            focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"><?= htmlspecialchars($applicantData['other_details'] ?? '') ?></textarea>
                    </label>
                </div>
            </div>
        </section>

        <!-- Submit Button -->
        <div class="flex justify-center pt-6">
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-lg font-semibold rounded-lg
                shadow-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2
                transform transition-all duration-300 hover:scale-105">
                Save Application
            </button>
        </div>
    </form>
</div>

<!-- Modify the toast notification -->
<div id="autoSaveToast"
    class="fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-xl hidden transition-all duration-300">
    <div class="flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span class="toast-message">Changes saved automatically</span>
    </div>
</div>

<script>
// Add auto-save functionality
const form = document.querySelector('form');
const inputs = form.querySelectorAll('input, select, textarea');
const toast = document.getElementById('autoSaveToast');
let saveTimeout;

function showToast(message, isError = false) {
    // Clear existing classes
    toast.classList.remove('bg-gray-800', 'bg-red-600', 'bg-green-600', 'text-white', 'hidden');

    // Add appropriate classes
    if (isError) {
        toast.classList.add('bg-red-600', 'text-white');
    } else {
        toast.classList.add('bg-green-600', 'text-white');
    }

    // Update message
    toast.querySelector('.toast-message').textContent = message;

    // Show toast
    toast.classList.remove('hidden');

    // Hide after 3 seconds
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

function saveField(input) {
    const fieldName = input.name;
    const fieldValue = input.value;

    // Show saving indicator
    showToast('Saving changes...');

    // Send AJAX request
    fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `field_name=${encodeURIComponent(fieldName)}&field_value=${encodeURIComponent(fieldValue)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Changes saved successfully');
            } else {
                showToast('Failed to save changes', true);
            }
        })

}

// Add change event listeners to all form fields
inputs.forEach(input => {
    input.addEventListener('change', () => {
        // Clear any existing timeout
        if (saveTimeout) {
            clearTimeout(saveTimeout);
        }

        // Set a new timeout to save after 500ms of no changes
        saveTimeout = setTimeout(() => {
            saveField(input);
        }, 500);
    });
});
</script>

<?php include '../../includes/footer.php'; ?>