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

<!-- Add CSS for animations and transitions -->
<style>
.card-hover {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.375rem 0.75rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
}

.status-badge.pending {
    background-color: #FEF3C7;
    color: #92400E;
}

.status-badge.approved {
    background-color: #DEF7EC;
    color: #03543F;
}

.status-badge.rejected {
    background-color: #FDE8E8;
    color: #9B1C1C;
}

.info-section {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease forwards;
}

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <!-- Breadcrumb -->
            <nav class="flex mb-6" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2">Applications</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Application Details</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Application Status Banner -->
            <div class="mb-6 bg-white rounded-lg shadow-sm p-4 border-l-4 <?php 
                echo match($applicantData['application_status']) {
                    'Pending' => 'border-yellow-400',
                    'Approved' => 'border-green-400',
                    'Rejected' => 'border-red-400',
                    default => 'border-gray-400'
                };
            ?>">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Application #<?= $applicantData['applicant_id'] ?></h2>
                        <p class="text-sm text-gray-600">Submitted on <?= date('F j, Y', strtotime($applicantData['created_at'])) ?></p>
                    </div>
                    <div class="status-badge <?= strtolower($applicantData['application_status']) ?>">
                        <?= $applicantData['application_status'] ?>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Personal Information Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 card-hover info-section" style="animation-delay: 0.1s">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Full Name</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['firstname'] . ' ' . $applicantData['middlename'] . ' ' . $applicantData['lastname'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Age</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['age'] ?> years old</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Birthdate</label>
                                    <p class="text-base text-gray-900"><?= date('F j, Y', strtotime($applicantData['birthdate'])) ?></p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Gender</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['gender'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Marital Status</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['marital_status'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nationality</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['nationality'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 card-hover info-section" style="animation-delay: 0.2s">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Mobile Number</label>
                                <p class="text-base text-gray-900"><?= $applicantData['mobile_number'] ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Email Address</label>
                                <p class="text-base text-gray-900"><?= $applicantData['email_address'] ?></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Complete Address</label>
                                <p class="text-base text-gray-900">
                                    <?= $applicantData['street'] ?>,<br>
                                    <?= $applicantData['barangay'] ?>,<br>
                                    <?= $applicantData['city_province'] ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Employment Information Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 card-hover info-section" style="animation-delay: 0.3s">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Employment Information</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Occupation</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['occupation'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Employment Status</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['employment_status'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Monthly Income</label>
                                    <p class="text-base text-gray-900">₱<?= number_format($applicantData['monthly_income'], 2) ?></p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Employer</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['employer'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Nature of Business</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['nature_business'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Duties</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['duties'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Plan Information Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 card-hover info-section" style="animation-delay: 0.4s">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Plan Information</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Plan Type</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['plan_type'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Plan Name</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['plan_name'] ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Face Value</label>
                                    <p class="text-base text-gray-900">₱<?= number_format($applicantData['face_value'], 2) ?></p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Payment Mode</label>
                                    <p class="text-base text-gray-900"><?= ucfirst($applicantData['payment_mode']) ?></p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Years to Maturity</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['years_to_maturity'] ?> years</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Protection Period</label>
                                    <p class="text-base text-gray-900"><?= $applicantData['years_of_protection'] ?> years</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Details Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 card-hover info-section" style="animation-delay: 0.5s">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Financial Details</h3>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <div class="p-4 bg-gray-50 rounded-lg mb-4">
                                    <label class="text-sm font-medium text-gray-500">Contribution Amount</label>
                                    <p class="text-xl font-bold text-gray-900">₱<?= number_format($applicantData['contribution_amount'], 2) ?></p>
                                    <p class="text-sm text-gray-500"><?= ucfirst($applicantData['payment_mode']) ?> payment</p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <label class="text-sm font-medium text-gray-500">Total Contribution</label>
                                    <p class="text-xl font-bold text-gray-900">₱<?= number_format($applicantData['total_contribution'], 2) ?></p>
                                    <p class="text-sm text-gray-500">Over <?= $applicantData['contribution_period'] ?> years</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <div class="p-4 bg-blue-50 rounded-lg">
                                    <label class="text-sm font-medium text-blue-700">Insurance Cost (10%)</label>
                                    <p class="text-lg font-semibold text-blue-900">₱<?= number_format($applicantData['insurance_cost'], 2) ?></p>
                                </div>
                                <div class="p-4 bg-green-50 rounded-lg">
                                    <label class="text-sm font-medium text-green-700">Admin Fee (5%)</label>
                                    <p class="text-lg font-semibold text-green-900">₱<?= number_format($applicantData['admin_fee'], 2) ?></p>
                                </div>
                                <div class="p-4 bg-purple-50 rounded-lg">
                                    <label class="text-sm font-medium text-purple-700">Savings Fund (85%)</label>
                                    <p class="text-lg font-semibold text-purple-900">₱<?= number_format($applicantData['savings_fund'], 2) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Beneficiary Information Card -->
                    <div class="bg-white rounded-lg shadow-sm p-6 card-hover info-section" style="animation-delay: 0.6s">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">Beneficiary Information</h3>
                        </div>
                        <div class="space-y-4">
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Beneficiary Name</label>
                                        <p class="text-base text-gray-900"><?= $applicantData['benefit_name'] ?></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Relationship</label>
                                        <p class="text-base text-gray-900"><?= $applicantData['benefit_relationship'] ?></p>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label class="text-sm font-medium text-gray-500">Birthdate</label>
                                    <p class="text-base text-gray-900"><?= date('F j, Y', strtotime($applicantData['benefit_birthdate'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex justify-end space-x-4 info-section" style="animation-delay: 0.7s">
                <button onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </button>
                <?php if ($applicantData['application_status'] === 'Pending'): ?>
                <button onclick="updateStatus('Approved')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Approve
                </button>
                <button onclick="updateStatus('Rejected')" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reject
                </button>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<script>
function updateStatus(status) {
    if (confirm(`Are you sure you want to ${status.toLowerCase()} this application?`)) {
        const applicantId = <?= json_encode($applicantData['applicant_id']) ?>;
        fetch('../../controllers/admin/updateApplicationStatus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                applicant_id: applicantId,
                status: status
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Application successfully ${status.toLowerCase()}`);
                location.reload();
            } else {
                alert('Error updating application status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating application status');
        });
    }
}

// Add smooth scroll animation when clicking on navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth'
        });
    });
});
</script>

<?php
include '../../includes/footer.php';
?>