<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/councilModel.php';
    include '../../models/adminModel/userModel.php';
    include '../../models/memberModel/memberApplicationModel.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
    include '../../includes/alert2.php';


    if (!isset($_GET['id'])) {
        die("ID is required.");
    }
    
    $id = intval($_GET['id']);
    $user_id = intval($_GET['user_id']);
    $councilModel           = new CouncilModel($conn);
    $applicationModel       = new MemberApplicationModel($conn);
    $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
    $fraternals             = $fraternalBenefitsModel->getAllFraternalBenefits();
    $councils               = $councilModel->getAllCouncil();
    $applicantData             = $applicationModel->fetchAllApplicantById($user_id);
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <div class="p-4 rounded-lg dark:border-gray-700">
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
                <div class="min-h-screen">
                    <div class="grid gap-6 mb-6">
                        <!-- Personal Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Firstname</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['firstname'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Lastname</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['lastname'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Middlename</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['middlename'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Age</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['age'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Birthdate</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['birthdate'] ?></span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Gender</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['gender'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Marital Status</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['marital_status'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">TIN/SSS</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['tin_sss'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nationality</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['nationality'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile Number</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['mobile_number'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['email_address'] ?></span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            <?= $applicantData['street'] ?>, <?= $applicantData['barangay'] ?>, <?= $applicantData['city_province'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employment Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Occupation</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['occupation'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Employment Status</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['employment_status'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Duties</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['duties'] ?></span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Employer</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['employer'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Nature of Business</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['nature_business'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Income</span>
                                        <span class="text-base text-gray-900 dark:text-white">₱<?= number_format($applicantData['monthly_income'], 2) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Plan Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Plan Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Fraternal Benefits</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['fraternal_benefits_id'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Council</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['council_id'] ?></span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Mode</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['payment_mode'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Contribution Amount</span>
                                        <span class="text-base text-gray-900 dark:text-white">₱<?= number_format($applicantData['contribution_amount'], 2) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Beneficiary Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Beneficiary Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Beneficiary Type</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['benefit_type'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Beneficiary Name</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['benefit_name'] ?></span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Birthday</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['benefit_birthdate'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Relationship</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['benefit_relationship'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Background Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Family Background</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Father's Name</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            <?= $applicantData['father_lastname'] ?>, <?= $applicantData['father_firstname'] ?> <?= $applicantData['father_mi'] ?>
                                        </span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mother's Name</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            <?= $applicantData['mother_lastname'] ?>, <?= $applicantData['mother_firstname'] ?> <?= $applicantData['mother_mi'] ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Siblings</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            Living: <?= $applicantData['siblings_living'] ?>, Deceased: <?= $applicantData['siblings_deceased'] ?>
                                        </span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Children</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            Living: <?= $applicantData['children_living'] ?>, Deceased: <?= $applicantData['children_deceased'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical History Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Medical History</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Past Illness</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['past_illness'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Medication</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['current_medication'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Health History Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Family Health History</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Father's Health</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            <?= $applicantData['father_living_age'] ? 'Living (Age: ' . $applicantData['father_living_age'] . ')' : 'Deceased (Age: ' . $applicantData['father_death_age'] . ')' ?>
                                            <?= $applicantData['father_health'] ? ' - ' . $applicantData['father_health'] : '' ?>
                                            <?= $applicantData['father_cause'] ? ' - Cause: ' . $applicantData['father_cause'] : '' ?>
                                        </span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Mother's Health</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            <?= $applicantData['mother_living_age'] ? 'Living (Age: ' . $applicantData['mother_living_age'] . ')' : 'Deceased (Age: ' . $applicantData['mother_death_age'] . ')' ?>
                                            <?= $applicantData['mother_health'] ? ' - ' . $applicantData['mother_health'] : '' ?>
                                            <?= $applicantData['mother_cause'] ? ' - Cause: ' . $applicantData['mother_cause'] : '' ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Siblings' Health</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            <?= $applicantData['siblings_living_age'] ? 'Living (Age: ' . $applicantData['siblings_living_age'] . ')' : 'Deceased (Age: ' . $applicantData['siblings_death_age'] . ')' ?>
                                            <?= $applicantData['siblings_health'] ? ' - ' . $applicantData['siblings_health'] : '' ?>
                                            <?= $applicantData['siblings_cause'] ? ' - Cause: ' . $applicantData['siblings_cause'] : '' ?>
                                        </span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Children's Health</span>
                                        <span class="text-base text-gray-900 dark:text-white">
                                            <?= $applicantData['children_living_age'] ? 'Living (Age: ' . $applicantData['children_living_age'] . ')' : 'Deceased (Age: ' . $applicantData['children_death_age'] . ')' ?>
                                            <?= $applicantData['children_health'] ? ' - ' . $applicantData['children_health'] : '' ?>
                                            <?= $applicantData['children_cause'] ? ' - Cause: ' . $applicantData['children_cause'] : '' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Physician Information Card -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Physician Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Physician Name</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['physician_name'] ?></span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Contact Number</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['contact_number'] ?></span>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</span>
                                        <span class="text-base text-gray-900 dark:text-white"><?= $applicantData['physician_address'] ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>