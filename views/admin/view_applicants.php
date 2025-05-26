<?php
    session_start();
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/councilModel.php';
    include '../../includes/alert2.php';
    include '../../models/adminModel/FormsModel.php';
    include '../../models/adminModel/TransactionModel.php';
    $searchResults = $_SESSION['search_results'] ?? [];
    $transactions = [];

    // var_dump($searchResults);
    // exit;
    $user_id = $_SESSION['user_id'] ?? null;

    if ($user_id) {
        $transactionModel = new TransactionModel($conn);
        $transactions = $transactionModel->getPaymentTransactionsByApplicant($user_id);
        // var_dump($transactions);
        // exit;
    } else {
        echo "User ID not set in session.";
        exit;
    }


?>



<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php' ?>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">

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
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">View
                                Applicant</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <section class="bg-gray-50 p-5 rounded shadow">

                    <table id="myTable" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                                <th class="px-4 py-3">First Name</th>
                                <th class="px-4 py-3">Last Name</th>
                                <th class="px-4 py-3">Gender</th>
                                <th class="px-4 py-3">Birthdate</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Application Status</th>
                                <th class="px-4 py-3">Created At</th>
                                <th class="px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            <?php foreach ($searchResults as $result): ?>
                            <?php $a = $result['fullDetails']['applicantData']; ?>
                            <tr class='border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'>
                                <td class='px-4 py-3'><?= htmlspecialchars($a['firstname']) ?></td>
                                <td class='px-4 py-3'><?= htmlspecialchars($a['lastname']) ?></td>
                                <td class='px-4 py-3'><?= htmlspecialchars($a['gender']) ?></td>
                                <td class='px-4 py-3'><?= htmlspecialchars($a['birthdate']) ?></td>
                                <td class='px-4 py-3'><?= htmlspecialchars($a['status']) ?></td>
                                <td class='px-4 py-3'><?= htmlspecialchars($a['application_status']) ?></td>
                                <td class='px-4 py-3'>
                                    <?= $a['created_at'] != '0000-00-00 00:00:00' ? date("F j, Y", strtotime($a['created_at'])) : 'N/A' ?>
                                </td>
                                <td class='px-4 py-3'>
                                    <a href="">Make payment</a>
                                    <!-- Alpine component wrapper -->
                                    <div x-data="{ open: false, activeTab: 'basic' }">
                                        <!-- Trigger Button -->
                                        <button @click="open = true"
                                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                            View Applicant Details
                                        </button>

                                        <!-- Modal -->
                                        <div x-show="open"
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                                            <!-- Modal Container -->
                                            <div class="bg-white w-full max-w-6xl rounded-lg shadow-lg overflow-hidden"
                                                @click.away="open = false">
                                                <!-- Modal Header -->
                                                <div class="flex justify-between items-center p-4 border-b">
                                                    <h2 class="text-xl font-semibold">
                                                        Applicant Details -
                                                        <?= $searchResults[0]['basicInfo']['firstname'] . ' ' . $searchResults[0]['basicInfo']['lastname']; ?>
                                                    </h2>
                                                    <button @click="open = false"
                                                        class="text-gray-600 hover:text-red-500 text-2xl">&times;</button>
                                                </div>

                                                <!-- Tabs -->
                                                <div class="flex border-b overflow-x-auto">
                                                    <template
                                                        x-for="tab in ['basic', 'contact', 'employment', 'plans', 'beneficiaries', 'family', 'medical', 'familyHealth', 'physician', 'transactions']">
                                                        <button @click="activeTab = tab"
                                                            :class="activeTab === tab ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-600 hover:text-blue-600 hover:border-blue-300'"
                                                            class="whitespace-nowrap px-4 py-2 border-b-2 font-medium focus:outline-none">
                                                            <span
                                                                x-text="tab.replace(/([A-Z])/g, ' $1').replace(/^./, str => str.toUpperCase())"></span>
                                                        </button>
                                                    </template>
                                                </div>

                                                <!-- Modal Body -->
                                                <div class="p-6 max-h-[70vh] overflow-y-auto">
                                                    <!-- Basic Info -->
                                                    <div x-show="activeTab === 'basic'">
                                                        <?php $b = $searchResults[0]['basicInfo']; ?>
                                                        <p><strong>Name:</strong>
                                                            <?= "$b[firstname] $b[middlename] $b[lastname]" ?></p>
                                                        <p><strong>Gender:</strong> <?= $b['gender']; ?></p>
                                                        <p><strong>Age:</strong> <?= $b['age']; ?></p>
                                                        <p><strong>Birthdate:</strong> <?= $b['birthdate']; ?></p>
                                                        <p><strong>Status:</strong> <?= $b['application_status']; ?></p>
                                                    </div>

                                                    <!-- Contact -->
                                                    <div x-show="activeTab === 'contact'">
                                                        <?php $c = $searchResults[0]['fullDetails']['contactInfo']; ?>
                                                        <p><strong>Email:</strong> <?= $c['email_address']; ?></p>
                                                        <p><strong>Mobile:</strong> <?= $c['mobile_number']; ?></p>
                                                        <p><strong>Address:</strong>
                                                            <?= "$c[street], $c[barangay], $c[city_province]" ?></p>
                                                    </div>

                                                    <!-- Add other tabs here (employment, plans, beneficiaries, etc.) just like in your original code -->
                                                    <!-- ... -->
                                                    <!-- Basic Info -->
                                                    <div x-show="activeTab === 'basic'">
                                                        <?php $b = $searchResults[0]['basicInfo']; ?>
                                                        <p><strong>Name:</strong>
                                                            <?= "$b[firstname] $b[middlename] $b[lastname]" ?>
                                                        </p>
                                                        <p><strong>Gender:</strong> <?= $b['gender']; ?></p>
                                                        <p><strong>Age:</strong> <?= $b['age']; ?></p>
                                                        <p><strong>Birthdate:</strong> <?= $b['birthdate']; ?>
                                                        </p>
                                                        <p><strong>Status:</strong>
                                                            <?= $b['application_status']; ?></p>
                                                    </div>

                                                    <!-- Contact -->
                                                    <div x-show="activeTab === 'contact'">
                                                        <?php $c = $searchResults[0]['fullDetails']['contactInfo']; ?>
                                                        <p><strong>Email:</strong> <?= $c['email_address']; ?>
                                                        </p>
                                                        <p><strong>Mobile:</strong> <?= $c['mobile_number']; ?>
                                                        </p>
                                                        <p><strong>Address:</strong>
                                                            <?= "$c[street], $c[barangay], $c[city_province]" ?>
                                                        </p>
                                                    </div>

                                                    <!-- Employment -->
                                                    <div x-show="activeTab === 'employment'">
                                                        <?php $e = $searchResults[0]['fullDetails']['employment']; ?>
                                                        <p><strong>Occupation:</strong> <?= $e['occupation']; ?>
                                                        </p>
                                                        <p><strong>Employer:</strong> <?= $e['employer']; ?></p>
                                                        <p><strong>Income:</strong> <?= $e['monthly_income']; ?>
                                                        </p>
                                                        <p><strong>Duties:</strong> <?= $e['duties']; ?></p>
                                                    </div>

                                                    <!-- Plans -->
                                                    <div x-show="activeTab === 'plans'">
                                                        <?php $p = $searchResults[0]['fullDetails']['plans']; ?>
                                                        <p><strong>Fraternal Benefit ID:</strong>
                                                            <?= $p['fraternal_benefits_id']; ?></p>
                                                        <p><strong>Council ID:</strong> <?= $p['council_id']; ?>
                                                        </p>
                                                        <p><strong>Payment Mode:</strong>
                                                            <?= $p['payment_mode']; ?></p>
                                                        <p><strong>Contribution:</strong>
                                                            <?= $p['contribution_amount'] . ' ' . $p['currency']; ?>
                                                        </p>
                                                    </div>

                                                    <!-- Beneficiaries -->
                                                    <div x-show="activeTab === 'beneficiaries'">
                                                        <?php $b = $searchResults[0]['fullDetails']['beneficiaries']; ?>
                                                        <p><strong>Name:</strong> <?= $b['benefit_name']; ?></p>
                                                        <p><strong>Birthdate:</strong>
                                                            <?= $b['benefit_birthdate']; ?></p>
                                                        <p><strong>Relationship:</strong>
                                                            <?= $b['benefit_relationship']; ?></p>
                                                    </div>

                                                    <!-- Family -->
                                                    <div x-show="activeTab === 'family'">
                                                        <?php $f = $searchResults[0]['fullDetails']['familyBackground']; ?>
                                                        <p><strong>Father:</strong>
                                                            <?= "$f[father_firstname] $f[father_lastname]" ?>
                                                        </p>
                                                        <p><strong>Mother:</strong>
                                                            <?= "$f[mother_firstname] $f[mother_lastname]" ?>
                                                        </p>
                                                        <p><strong>Siblings:</strong> Living -
                                                            <?= $f['siblings_living']; ?>, Deceased -
                                                            <?= $f['siblings_deceased']; ?></p>
                                                        <p><strong>Children:</strong> Living -
                                                            <?= $f['children_living']; ?>, Deceased -
                                                            <?= $f['children_deceased']; ?></p>
                                                    </div>

                                                    <!-- Medical -->
                                                    <div x-show="activeTab === 'medical'">
                                                        <?php $m = $searchResults[0]['fullDetails']['medicalHistory']; ?>
                                                        <p><strong>Past Illness:</strong>
                                                            <?= $m['past_illness']; ?></p>
                                                        <p><strong>Current Medication:</strong>
                                                            <?= $m['current_medication']; ?></p>
                                                    </div>

                                                    <!-- Family Health -->
                                                    <div x-show="activeTab === 'familyHealth'">
                                                        <?php $h = $searchResults[0]['fullDetails']['familyHealth']; ?>
                                                        <p><strong>Father:</strong> <?= $h['father_health']; ?>
                                                            (Age: <?= $h['father_living_age']; ?> / Death:
                                                            <?= $h['father_death_age']; ?>, Cause:
                                                            <?= $h['father_cause']; ?>)</p>
                                                        <p><strong>Mother:</strong> <?= $h['mother_health']; ?>
                                                            (Age: <?= $h['mother_living_age']; ?> / Death:
                                                            <?= $h['mother_death_age']; ?>, Cause:
                                                            <?= $h['mother_cause']; ?>)</p>
                                                        <p><strong>Siblings:</strong>
                                                            <?= $h['siblings_health']; ?> (Ages:
                                                            <?= $h['siblings_living_age']; ?> /
                                                            Deaths: <?= $h['siblings_death_age']; ?>)</p>
                                                        <p><strong>Children:</strong>
                                                            <?= $h['children_health']; ?> (Ages:
                                                            <?= $h['children_living_age']; ?> /
                                                            Deaths: <?= $h['children_death_age']; ?>)</p>
                                                    </div>

                                                    <!-- Physician -->
                                                    <div x-show="activeTab === 'physician'">
                                                        <?php $ph = $searchResults[0]['fullDetails']['physician']; ?>
                                                        <p><strong>Name:</strong> <?= $ph['physician_name']; ?>
                                                        </p>
                                                        <p><strong>Contact:</strong>
                                                            <?= $ph['contact_number']; ?></p>
                                                        <p><strong>Address:</strong>
                                                            <?= $ph['physician_address']; ?></p>
                                                    </div>

                                                    <!-- Transactions -->
                                                    <div x-show="activeTab === 'transactions'">
                                                        <?php $t = $searchResults[0]['fullDetails']['transactions']; ?>
                                                        <p><strong>Payment Date:</strong>
                                                            <?= $t['payment_date']; ?></p>
                                                        <p><strong>Amount Paid:</strong>
                                                            <?= $t['amount_paid']; ?> <?= $t['currency']; ?></p>
                                                        <p><strong>Next Due:</strong>
                                                            <?= $t['next_due_date']; ?></p>
                                                        <p><strong>Status:</strong> <?= $t['status']; ?>
                                                            (<?= $t['payment_timing_status']; ?>)</p>
                                                    </div>

                                                </div>



                                                <!-- Modal Footer -->
                                                <div class="flex justify-end p-4 border-t">
                                                    <button @click="open = false"
                                                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="container mx-auto px-4 py-8 max-w-7xl" x-data="orders">
                        <!-- Page Header -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-gray-900">Payment Transaction History</h1>
                            <p class="text-gray-600 mt-2">View recent transaction</p>
                        </div>

                        <?php if (!empty($transactions)): ?>
                        <div class="space-y-6">
                            <?php foreach ($transactions as $txn): ?>
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                                <div class="p-6">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                                        <div class="mb-4 md:mb-0">
                                            <div class="flex items-center space-x-4">
                                                <span class="text-sm font-medium text-gray-900">Transaction
                                                    #<?= $txn['transaction_id'] ?></span>
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-medium 
                                    <?= $txn['status'] === 'Paid' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                    <?= htmlspecialchars($txn['status']) ?>
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 mt-1">Payment Date:
                                                <?= date("F j, Y", strtotime($txn['payment_date'])) ?></p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-semibold text-gray-900">
                                                ₱<?= number_format($txn['amount_paid'], 2) ?></p>
                                            <p class="text-sm text-gray-500">Currency:
                                                <?= htmlspecialchars($txn['currency']) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t px-6 py-4 bg-gray-50 text-sm text-gray-700">
                                    Next Due:
                                    <?= $txn['next_due_date'] ? date("F j, Y", strtotime($txn['next_due_date'])) : 'N/A' ?>
                                    |
                                    Payment Status: <?= $txn['status'] === 'Paid' ? 'Ontime' : 'Late' ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="text-gray-500 text-sm">No payment transactions found.</p>
                        <?php endif; ?>

                </section>
            </div>
        </div>
    </main>


    <?php
include '../../includes/footer.php';
?>

    <!--AP 17 ui 13 fb 5 ci 6 -->