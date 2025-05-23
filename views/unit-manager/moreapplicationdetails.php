<?php
    require_once '../../middleware/auth.php';
    authorize(['admin','unit-manager','fraternal-counselor']);
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
                    <div class="relative overflow-x-auto mt-4">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Field</th>
                                    <th scope="col" class="px-6 py-3">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Personal Info -->
                                <th scope="col" class="px-6 py-3">Personal Info</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Firstname</th>
                                    <td class="px-6 py-4"><?= $applicantData['firstname'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Lastname
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['lastname'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Middlename</th>
                                    <td class="px-6 py-4"><?= $applicantData['middlename'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Birthdate</th>
                                    <td class="px-6 py-4"><?= $applicantData['birthdate'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Gender
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['gender'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Marital Status</th>
                                    <td class="px-6 py-4"><?= $applicantData['marital_status'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">TIN/SSS
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['tin_sss'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Nationality
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['nationality'] ?></td>
                                </tr>
                                <!-- Emergency Contact Details -->
                                <th scope="col" class="px-6 py-3">Emergency Contact Details</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Mobile
                                        Number
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mobile_number'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Email
                                        Address
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['email_address'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Barangay
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['barangay'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        City/Province
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['city_province'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Street
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['street'] ?></td>
                                </tr>
                                <!-- Employment details -->
                                <th scope="col" class="px-6 py-3">Employment details</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Occupation
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['occupation'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Employment Status
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['employment_status'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Duties
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['duties'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Employer
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['employer'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Nature
                                        of businness
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['nature_business'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Employer
                                        Mobile Number
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['employer_mobile_number'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Employer
                                        Email Address
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['employer_email_address'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Monthlly
                                        Income
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['monthly_income'] ?></td>
                                </tr>
                                <!-- Plan details -->
                                <th scope="col" class="px-6 py-3">Plan details</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Fraternal Benefits
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['fraternal_benefits_id'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Council
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['council_id'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Payment
                                        Mode
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['payment_mode'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Contribution Amount
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['contribution_amount'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">Currency
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['currency'] ?></td>
                                </tr>
                                <!-- Beneficiaries -->
                                <th scope="col" class="px-6 py-3">Beneficiaries</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Beneficiaries Type
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['benefit_type'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Beneficiary Name
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['benefit_name'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Beneficiary Birthday
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['benefit_birthdate'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Relationship
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['benefit_relationship'] ?></td>
                                </tr>
                                <!-- Family Background -->
                                <th scope="col" class="px-6 py-3">Family Background</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Father lastname
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['father_lastname'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Father firstname
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['father_firstname'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Father MI
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['father_mi'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Mother lastname
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mother_lastname'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        >Mother firstname
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mother_firstname'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Mother MI
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mother_mi'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Siblings Living
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['siblings_living'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Sibling Deceased
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['siblings_deceased'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Children living
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['children_living'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Children Deceased
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['children_deceased'] ?></td>
                                </tr>
                                <!-- Medical and Family health history -->
                                <th scope="col" class="px-6 py-3">Medical history</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Past Illness
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['past_illness'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Current Medication
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['current_medication'] ?></td>
                                </tr>
                                <!-- Family health history -->
                                <th scope="col" class="px-6 py-3">Family health history</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Father living age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['father_living_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Father health
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['father_health'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Mother living age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mother_living_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Mother health
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mother_health'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Siblings living age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['siblings_living_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Siblings health
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['siblings_health'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Children living age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['children_living_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Children health
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['children_health'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Father death age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['father_death_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Father cause of death
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['father_cause'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Mother death age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mother_death_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Mother cause of death
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['mother_cause'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Sbiling death age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['siblings_death_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Sibling cause of death
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['siblings_cause'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Children death age
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['children_death_age'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Children cause of death
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['children_cause'] ?></td>
                                </tr>
                                <!--  Physician Details -->
                                <th scope="col" class="px-6 py-3">Physician Details</th>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Physician Name
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['physician_name'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Contact Number
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['contact_number'] ?></td>
                                </tr>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        Address
                                    </th>
                                    <td class="px-6 py-4"><?= $applicantData['physician_address'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>