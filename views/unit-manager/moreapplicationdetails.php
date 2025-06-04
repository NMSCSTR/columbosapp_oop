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

if (!isset($_GET['id'])) {
    die("ID is required.");
}

$id = intval($_GET['id']);
$user_id = intval($_GET['user_id']);
$councilModel = new CouncilModel($conn);
$applicationModel = new MemberApplicationModel($conn);
$fraternalBenefitsModel = new fraternalBenefitsModel($conn);
$fraternals = $fraternalBenefitsModel->getAllFraternalBenefits();
$councils = $councilModel->getAllCouncil();
$applicantData = $applicationModel->fetchAllApplicantById($user_id);
?>

<style>
    .section-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    .section-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .hero-section {
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        color: white;
        padding: 4rem 2rem;
        margin-bottom: 2rem;
    }
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .info-item {
        padding: 1rem;
        border-bottom: 1px solid #e5e7eb;
    }
    .info-label {
        font-size: 0.875rem;
        color: #6b7280;
        margin-bottom: 0.5rem;
    }
    .info-value {
        font-size: 1rem;
        color: #1f2937;
        font-weight: 500;
    }
    .section-title {
        color: #4F46E5;
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .section-icon {
        width: 1.5rem;
        height: 1.5rem;
    }
</style>

<div class="min-h-screen bg-gray-50 pb-12">
    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container mx-auto max-w-7xl">
            <a href="unitmanager.php" class="inline-flex items-center text-white hover:text-gray-200 mb-6">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
            <h1 class="text-4xl font-bold mb-2"><?php echo htmlspecialchars($applicantData['firstname'] . ' ' . $applicantData['lastname']); ?></h1>
            <p class="text-xl opacity-90">Application Details</p>
        </div>
    </div>

    <!-- Content Container -->
    <div class="container mx-auto max-w-7xl px-4">
        <!-- Personal Information -->
        <div class="section-card p-6 mb-8">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Personal Information
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Full Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['firstname'] . ' ' . $applicantData['middlename'] . ' ' . $applicantData['lastname']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Age</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['age']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Birthdate</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['birthdate']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Gender</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['gender']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Marital Status</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['marital_status']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nationality</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['nationality']); ?></div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="section-card p-6 mb-8">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                Contact Information
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Mobile Number</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['mobile_number']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Email Address</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['email_address']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Complete Address</div>
                    <div class="info-value">
                        <?php echo htmlspecialchars($applicantData['street'] . ', ' . $applicantData['barangay'] . ', ' . $applicantData['city_province']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employment Details -->
        <div class="section-card p-6 mb-8">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Employment Information
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Occupation</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['occupation']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Employment Status</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['employment_status']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Employer</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['employer']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Nature of Business</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['nature_business']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Monthly Income</div>
                    <div class="info-value">₱<?php echo number_format($applicantData['monthly_income'], 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Plan Details -->
        <div class="section-card p-6 mb-8">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                Plan Details
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Fraternal Benefits</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['fraternal_benefits_id']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Council</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['council_id']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Payment Mode</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['payment_mode']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Contribution Amount</div>
                    <div class="info-value">₱<?php echo number_format($applicantData['contribution_amount'], 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Beneficiary Information -->
        <div class="section-card p-6 mb-8">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Beneficiary Information
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Beneficiary Type</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['benefit_type']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Beneficiary Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['benefit_name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Beneficiary Birthday</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['benefit_birthdate']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Relationship</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['benefit_relationship']); ?></div>
                </div>
            </div>
        </div>

        <!-- Medical History -->
        <div class="section-card p-6 mb-8">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Medical History
            </h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Past Illness</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['past_illness']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Current Medication</div>
                    <div class="info-value"><?php echo htmlspecialchars($applicantData['current_medication']); ?></div>
                </div>
            </div>
        </div>

        <!-- Family Health History -->
        <div class="section-card p-6">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Family Health History
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Parents -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Parents</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium mb-2">Father</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Living Age</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['father_living_age']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Health Status</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['father_health']); ?></div>
                                </div>
                                <?php if ($applicantData['father_death_age']): ?>
                                <div class="info-item">
                                    <div class="info-label">Death Age</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['father_death_age']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Cause of Death</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['father_cause']); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium mb-2">Mother</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Living Age</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['mother_living_age']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Health Status</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['mother_health']); ?></div>
                                </div>
                                <?php if ($applicantData['mother_death_age']): ?>
                                <div class="info-item">
                                    <div class="info-label">Death Age</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['mother_death_age']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Cause of Death</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['mother_cause']); ?></div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Siblings and Children -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Siblings and Children</h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium mb-2">Siblings</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Living</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['siblings_living']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Deceased</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['siblings_deceased']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Health Status</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['siblings_health']); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-medium mb-2">Children</h4>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Living</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['children_living']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Deceased</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['children_deceased']); ?></div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Health Status</div>
                                    <div class="info-value"><?php echo htmlspecialchars($applicantData['children_health']); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Signature Section -->
    <div class="container mx-auto max-w-7xl px-4 mb-8">
        <div class="section-card p-6">
            <h2 class="section-title">
                <svg class="section-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                Applicant's Signature
            </h2>
            <div class="flex flex-col items-center justify-center p-4">
                <?php if (!empty($applicantData['signature_file'])): ?>
                    <img src="<?php echo $applicantData['signature_file']; ?>" 
                         alt="Applicant's Signature" 
                         class="max-w-md w-full h-auto border rounded-lg shadow-sm"
                         onerror="this.src='../../assets/img/default-signature.png'">
                    <p class="text-sm text-gray-500 mt-2">Digital Signature</p>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50">
                        <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                        </svg>
                        <p class="text-gray-500">No signature available</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>