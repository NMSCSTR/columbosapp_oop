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
    // print_r($searchResults);
    // exit;
    if ($user_id) {
        $transactionModel = new TransactionModel($conn);
        $transactions = $transactionModel->getPaymentTransactionsByApplicant($user_id);

    } else {
        echo "User ID not set in session.";
        exit;
    }


?>

<?php $c = $searchResults[0]['basicInfo']; ?>
<?php $d = $searchResults[0]['fullDetails']; ?>


<!-- Main modal -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Make Payment
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form class="space-y-4" action="../../controllers/adminController/transactionController.php" method="POST">
                    <div>
                        <!-- <label for="applicant_id "
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">APPLICANT ID</label> -->
                        <input type="hidden" value="<?= $d['applicantData']['applicant_id']; ?>" name="applicant_id" id="applicant_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required />
                    </div>
                    <div>
                        <!-- <label for="user_id " class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">USER
                            ID</label> -->
                        <input type="hidden" value="<?= $c['user_id']; ?>" name="user_id" id="user_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required />
                    </div>
                    <div>
                        <!-- <label for="hidden " class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PLAN
                            ID</label> -->
                        <input type="hidden" value="<?= $d['plans']['plan_id'] ?>" name="plan_id" id="plan_id"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required />
                    </div>
                    <div>
                        <label for="payment_date "
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PAYMENT DATE</label>
                        <input type="date" name="payment_date" id="payment_date"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required />
                    </div>
                    <div>
                        <label for=" "
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">AMOUNT</label>
                        <input type="number" name="amount_paid" id="amount_paid"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required />
                    </div>
                    <div>
                        <label for="currency"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">CURRENCY</label>
                        <input type="text" value="<?= $d['plans']['currency'] ?>"  name="currency" id="currency"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required />
                    </div>
                    <div>
                        <label for="payment_timing_status"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">PAYMENT TIMING STATUS</label>
                        <select id="payment_timing_status" name="payment_timing_status"
                            class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-200 appearance-none dark:text-gray-400 dark:border-gray-700 focus:outline-none focus:ring-0 focus:border-gray-200 peer">
                            <option selected disabled>Choose an option</option>
                            <option value="On-Time">On-Time</option>
                            <option value="Late">Late</option>
                        </select>
                    </div>


                    <button type="submit" name="submit_transaction"
                        class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">PAY</button>
                </form>
            </div>
        </div>
    </div>
</div>


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

                    <table id="myTable" class="min-w-full divide-y divide-gray-200 shadow-sm rounded-lg overflow-hidden">
                        <thead class="bg-white text-gray-500">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">First Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Last Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Birthdate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Application Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Created At</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($searchResults as $result): ?>
                                <?php $a = $result['fullDetails']['applicantData']; ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($a['firstname']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($a['lastname']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($a['gender']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($a['birthdate']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($a['status']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($a['application_status']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $a['created_at'] != '0000-00-00 00:00:00' ? date("F j, Y", strtotime($a['created_at'])) : 'N/A' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                                        <a data-modal-target="authentication-modal" data-modal-toggle="authentication-modal" href="#" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-money-bill-wave"></i> PAY
                                        </a>
                                        <!-- <span class="text-gray-400">|</span>
                                        <button @click="open = true" class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-eye"></i> VIEW
                                        </button> -->
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
                                    <!-- Add allocation details -->
                                    <div class="mt-4 grid grid-cols-3 gap-4">
                                        <div class="bg-blue-50 rounded-lg p-3">
                                            <p class="text-xs text-blue-600 font-medium">Insurance Cost (10%)</p>
                                            <p class="text-sm font-semibold text-blue-900">₱<?= number_format($txn['amount_paid'] * 0.10, 2) ?></p>
                                        </div>
                                        <div class="bg-green-50 rounded-lg p-3">
                                            <p class="text-xs text-green-600 font-medium">Admin Fee (5%)</p>
                                            <p class="text-sm font-semibold text-green-900">₱<?= number_format($txn['amount_paid'] * 0.05, 2) ?></p>
                                        </div>
                                        <div class="bg-purple-50 rounded-lg p-3">
                                            <p class="text-xs text-purple-600 font-medium">Savings Fund (85%)</p>
                                            <p class="text-sm font-semibold text-purple-900">₱<?= number_format($txn['amount_paid'] * 0.85, 2) ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="border-t px-6 py-4 bg-gray-50 text-sm text-gray-700">
                                    Next Due:
                                    <?= $txn['next_due_date'] ? date("F j, Y", strtotime($txn['next_due_date'])) : 'N/A' ?>
                                    |
                                    Payment Status: <span
                                        class="font-semibold <?= $txn['payment_timing_status'] === 'On-Time' ? 'text-green-100 text-green-800' : 'text-red-100 text-red-800' ?>"><?= $txn['payment_timing_status'] ?></span>
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

