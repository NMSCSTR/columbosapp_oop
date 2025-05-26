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

<div class="flex flex-col items-center justify-center min-h-screen p-4 bg-gray-100">
    <div class="w-full max-w-7xl">
        <!-- Back Button -->
        <div class="mb-4">
            <a href="unitmanager.php" class="inline-block px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                ← Back
            </a>
        </div>

        <!-- Scrollable Table Container -->
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <!-- table contents here -->
                <table class="min-w-[1200px] text-sm text-left text-gray-700">
                    <!-- table contents here -->
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-800 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Field</th>
                                <th scope="col" class="px-6 py-3">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Personal Info -->
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 11h2v5m-2 0h4m-2.592-8.5h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    <span>Personal Info</span>
                                </div>
                            </th>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    Firstname</th>
                                <td class="px-6 py-4"><?php echo $applicantData['firstname'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Lastname
                                </th>
                                <td class="px-6 py-4"><?php echo $applicantData['lastname'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    Middlename</th>
                                <td class="px-6 py-4"><?php echo $applicantData['middlename'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Age
                                </th>
                                <td class="px-6 py-4"><?php echo $applicantData['age'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    Birthdate</th>
                                <td class="px-6 py-4"><?php echo $applicantData['birthdate'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Gender
                                </th>
                                <td class="px-6 py-4"><?php echo $applicantData['gender'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    Marital Status</th>
                                <td class="px-6 py-4"><?php echo $applicantData['marital_status'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">TIN/SSS
                                </th>
                                <td class="px-6 py-4"><?php echo $applicantData['tin_sss'] ?></td>
                            </tr>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                    Nationality
                                </th>
                                <td class="px-6 py-4"><?php echo $applicantData['nationality'] ?></td>
                            </tr>
                            <!-- Emergency Contact Details -->
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z" />
                                    </svg>

                                    <span>Emergency Contact Details</span>
                            </th>
        </div>

        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Mobile
                Number
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mobile_number'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Email
                Address
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['email_address'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Barangay
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['barangay'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                City/Province
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['city_province'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Street
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['street'] ?></td>
        </tr>
        <!-- Employment details -->
        <th scope="col" class="px-6 py-3">
            <div class="flex items-center"><svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" />
                </svg>
                <span>Employment details</span>
            </div>
        </th>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Occupation
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['occupation'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Employment Status
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['employment_status'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Duties
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['duties'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Employer
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['employer'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Nature
                of businness
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['nature_business'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Employer
                Mobile Number
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['employer_mobile_number'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Employer
                Email Address
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['employer_email_address'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Monthlly
                Income
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['monthly_income'] ?></td>
        </tr>
        <!-- Plan details -->
        <th scope="col" class="px-6 py-3">Plan details</th>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Fraternal Benefits
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['fraternal_benefits_id'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Council
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['council_id'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Payment
                Mode
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['payment_mode'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Contribution Amount
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['contribution_amount'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Currency
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['currency'] ?></td>
        </tr>
        <!-- Beneficiaries -->
        <th scope="col" class="px-6 py-3">Beneficiaries</th>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Beneficiaries Type
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['benefit_type'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Beneficiary Name
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['benefit_name'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Beneficiary Birthday
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['benefit_birthdate'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Relationship
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['benefit_relationship'] ?></td>
        </tr>
        <!-- Family Background -->
        <th scope="col" class="px-6 py-3">Family Background</th>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Father lastname
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['father_lastname'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Father firstname
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['father_firstname'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Father MI
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['father_mi'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Mother lastname
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mother_lastname'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                >Mother firstname
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mother_firstname'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Mother MI
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mother_mi'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Siblings Living
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['siblings_living'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Sibling Deceased
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['siblings_deceased'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Children living
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['children_living'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Children Deceased
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['children_deceased'] ?></td>
        </tr>
        <!-- Medical and Family health history -->
        <th scope="col" class="px-6 py-3">Medical history</th>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Past Illness
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['past_illness'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Current Medication
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['current_medication'] ?></td>
        </tr>
        <!-- Family health history -->
        <th scope="col" class="px-6 py-3">Family health history</th>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Father living age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['father_living_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Father health
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['father_health'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Mother living age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mother_living_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Mother health
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mother_health'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Siblings living age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['siblings_living_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Siblings health
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['siblings_health'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Children living age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['children_living_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Children health
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['children_health'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Father death age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['father_death_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Father cause of death
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['father_cause'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Mother death age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mother_death_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Mother cause of death
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['mother_cause'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Sbiling death age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['siblings_death_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Sibling cause of death
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['siblings_cause'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Children death age
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['children_death_age'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Children cause of death
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['children_cause'] ?></td>
        </tr>
        <!--  Physician Details -->
        <th scope="col" class="px-6 py-3">Physician Details</th>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Physician Name
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['physician_name'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Contact Number
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['contact_number'] ?></td>
        </tr>
        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
            <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                Address
            </th>
            <td class="px-6 py-4"><?php echo $applicantData['physician_address'] ?></td>
        </tr>
        </tbody>
        </table>
        </table>
        </table>
    </div>
</div>
</div>


<?php
include '../../includes/footer.php';
?>