<?php
    require_once '../../middleware/auth.php';
    authorize(['admin', 'member']);
    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/usersModel.php';
    include '../../models/adminModel/councilModel.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
    $userModel          = new UserModel($conn);
    $user               = $userModel->getUserById($_SESSION['user_id']);
    $fraternalCounselor = $userModel->getUserWhereRoleFraternalCounselor();
?>

<link rel="stylesheet" href="stylesheet/member.css">
<style>
    /* Smooth Transitions for Steps */
    .step-content { display: none; transition: all 0.3s ease-in-out; }
    .step-content.active { display: block; animation: slideIn 0.4s ease-out; }
    @keyframes slideIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    
    /* Custom Scrollbar for Main Content */
    .main-container::-webkit-scrollbar { width: 6px; }
    .main-container::-webkit-scrollbar-track { background: #f1f1f1; }
    .main-container::-webkit-scrollbar-thumb { background: #06b6d4; border-radius: 10px; }

    /* Input Focus Styles */
    .form-input { @apply border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 outline-none transition-all duration-200 bg-white; }
    .form-label { @apply text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 ml-1 block; }
</style>

<?php include '../../partials/memberSideBar.php'?>

<main class="flex-1 p-4 md:p-10 overflow-y-auto w-full min-w-0 bg-slate-50 main-container">
    <div class="max-w-12xl mx-auto">
        
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h3 class="text-4xl font-extrabold text-slate-800 tracking-tight">Application Form</h3>
                <p class="text-slate-500 mt-1">Fill out the details below to proceed with your membership application.</p>
            </div>
            <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-slate-100">
                <span class="text-xs font-bold text-slate-400 uppercase block">Applicant Name</span>
                <span class="text-lg font-bold text-cyan-600"><?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?></span>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
            <div class="bg-slate-50/50 border-b border-slate-100 px-4">
                <div class="grid grid-cols-4 md:grid-cols-11 gap-2 py-8">
                    <template id="step-header-template">
                        <div class="flex flex-col items-center relative group">
                            <div class="step-circle w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 ring-4 ring-white shadow-sm z-10">
                            </div>
                            <span class="step-label hidden md:block mt-2 text-[10px] font-bold uppercase tracking-tighter text-slate-400"></span>
                        </div>
                    </template>
                </div>
            </div>

            <form id="multiStepForm" method="POST" action="<?php echo BASE_URL ?>controllers/memberController/addMemberApplicant.php" enctype="multipart/form-data" class="p-6 md:p-10">
                
                <div id="stepContents">
                    <div class="step-content active">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Personal Information</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>">
                            <div>
                                <label class="form-label">First Name</label>
                                <input type="text" value="<?php echo htmlspecialchars($user['firstname']); ?>" name="firstname" class="w-full border-slate-200 rounded-xl p-3 bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="form-label">Last Name</label>
                                <input type="text" value="<?php echo htmlspecialchars($user['lastname']); ?>" name="lastname" class="w-full border-slate-200 rounded-xl p-3 bg-slate-50 text-slate-500 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="form-label">Middle Name</label>
                                <input type="text" placeholder="Enter Middle Name" name="middlename" class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" required>
                            </div>
                            <div>
                                <label class="form-label">Birthdate</label>
                                <input type="date" id="birthdate" name="birthdate" class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" required>
                            </div>
                            <div>
                                <label class="form-label">Age</label>
                                <input type="number" id="age" name="age" class="w-full border-slate-200 rounded-xl p-3 bg-slate-50 text-slate-500" readonly>
                            </div>
                            <div>
                                <label class="form-label">Birthplace</label>
                                <input type="text" placeholder="Place of Birth" name="birthplace" class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" required>
                            </div>
                            <div>
                                <label class="form-label">Gender</label>
                                <select class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" name="gender">
                                    <option>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Fraternal Counselor</label>
                                <select class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" name="fraternal_counselor_id" required>
                                    <option selected disabled>Select Counselor</option>
                                    <?php if ($fraternalCounselor) { foreach ($fraternalCounselor as $fc) {?>
                                        <option value="<?php echo htmlspecialchars($fc['id']); ?>"><?php echo htmlspecialchars($fc['firstname'] . ' ' . $fc['lastname']); ?></option>
                                    <?php }} ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Marital Status</label>
                                <select class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" name="marital_status">
                                    <option disabled>Select Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">TIN/SSS</label>
                                <input type="text" placeholder="Number" name="tin_sss" class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" required>
                            </div>
                            <div>
                                <label class="form-label">Nationality</label>
                                <input type="text" placeholder="Nationality" class="w-full border-slate-200 rounded-xl p-3 focus:ring-2 focus:ring-cyan-500 outline-none border" name="nationality" required>
                            </div>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Contact Details</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="form-label">Street</label><input type="text" placeholder="Street Address" name="street" class="w-full border-slate-200 rounded-xl p-3 border"></div>
                            <div><label class="form-label">Barangay</label><input type="text" placeholder="Barangay" name="barangay" class="w-full border-slate-200 rounded-xl p-3 border" required></div>
                            <div><label class="form-label">City/Province</label><input type="text" placeholder="City/Province" name="city_province" class="w-full border-slate-200 rounded-xl p-3 border" required></div>
                            <div><label class="form-label">Mobile Number</label><input type="text" placeholder="09XX XXX XXXX" name="mobile_number" class="w-full border-slate-200 rounded-xl p-3 border" required></div>
                            <div class="md:col-span-2"><label class="form-label">Email Address</label><input type="email" placeholder="email@example.com" name="email_address" class="w-full border-slate-200 rounded-xl p-3 border" required></div>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Employment Details</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="text" placeholder="Occupation" name="occupation" class="border-slate-200 rounded-xl p-3 border">
                            <select class="border-slate-200 rounded-xl p-3 border" name="employment_status">
                                <option selected>Employment Status</option>
                                <option value="employed">Employed</option>
                                <option value="self_employed">Self Employed</option>
                            </select>
                            <input type="text" placeholder="Specific duties" name="duties" class="border-slate-200 rounded-xl p-3 border">
                            <input type="text" placeholder="Employer Name" name="employer" class="border-slate-200 rounded-xl p-3 border">
                            <input type="text" placeholder="Work Department" name="work" class="border-slate-200 rounded-xl p-3 border">
                            <input type="text" placeholder="Nature of business" name="nature_business" class="border-slate-200 rounded-xl p-3 border">
                            <input type="text" placeholder="Employer Email" name="employer_email_address" class="border-slate-200 rounded-xl p-3 border">
                            <input type="text" placeholder="Employer Mobile" name="employer_mobile_number" class="border-slate-200 rounded-xl p-3 border">
                            <input type="number" placeholder="Monthly Income" name="monthly_income" class="border-slate-200 rounded-xl p-3 border">
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Plan Selection</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <select class="border-slate-200 rounded-xl p-3 border" name="fraternal_benefits_id">
                                <option>Select Plan</option>
                                <?php
                                    $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
                                    $fraternalBenefits      = $fraternalBenefitsModel->getAllFraternalBenefits();
                                    if ($fraternalBenefits) {
                                        foreach ($fraternalBenefits as $benefit) {?>
                                            <option value="<?php echo $benefit['id'] ?>"><?php echo $benefit['name'] ?></option>
                                <?php } } ?>
                            </select>
                            <select class="border-slate-200 rounded-xl p-3 border" name="payment_mode">
                                <option selected disabled>Mode of payment</option>
                                <option value="monthly">Monthly</option>
                                <option value="semi-annually">Semi-Annually</option>
                                <option value="quarterly">Quarterly</option>
                            </select>
                            <select class="border-slate-200 rounded-xl p-3 border" name="currency">
                                <option selected disabled>Currency</option>
                                <option name="PHP">PHP</option>
                            </select>
                            <select class="border-slate-200 rounded-xl p-3 border" name="council_id">
                                <option selected disabled>Select Council</option>
                                <?php $councilModel = new CouncilModel($conn);
                                    $councils = $councilModel->getAllCouncil();
                                    if ($councils) {
                                        foreach ($councils as $council) {?>
                                            <option value="<?php echo $council['council_id'] ?>"><?php echo $council['council_name'] ?></option>
                                <?php } } ?>
                            </select>
                            <input type="number" placeholder="Payment Amount" name="contribution_amount" class="border-slate-200 rounded-xl p-3 border md:col-span-2" required>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                                <h2 class="text-2xl font-bold text-slate-800">Beneficiaries</h2>
                            </div>
                            <button type="button" onclick="addBeneficiary()" class="bg-cyan-50 text-cyan-600 px-5 py-2 rounded-xl font-bold text-sm hover:bg-cyan-600 hover:text-white transition-all shadow-sm border border-cyan-100">
                                + Add Beneficiary
                            </button>
                        </div>
                        <div id="beneficiaries-container" class="space-y-4">
                            <div class="beneficiary-group grid grid-cols-1 md:grid-cols-4 gap-4 p-6 rounded-2xl border border-slate-100 bg-slate-50/50">
                                <select class="border-slate-200 rounded-xl p-3 border bg-white" name="benefit_type[]">
                                    <option selected disabled>Type</option>
                                    <option value="Revocable">Revocable</option>
                                    <option value="Irrevocable">Irrevocable</option>
                                </select>
                                <input type="text" name="benefit_name[]" placeholder="Full Name" class="border-slate-200 rounded-xl p-3 border bg-white" required>
                                <input type="date" name="benefit_birthdate[]" class="border-slate-200 rounded-xl p-3 border bg-white" required>
                                <input type="text" name="benefit_relationship[]" placeholder="Relationship" class="border-slate-200 rounded-xl p-3 border bg-white" required>
                            </div>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Family Background</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <input type="text" name="father_lastname" placeholder="Father's Last Name" class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" name="father_firstname" placeholder="Father's First Name" class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" name="father_mi" placeholder="Father's M.I." class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" name="mother_lastname" placeholder="Mother's Last Name" class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" name="mother_firstname" placeholder="Mother's First Name" class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" name="mother_mi" placeholder="Mother's M.I." class="border-slate-200 rounded-xl p-3 border" required>
                            <div class="md:col-span-3 grid grid-cols-2 md:grid-cols-4 gap-4">
                                <input type="number" name="siblings_living" placeholder="Living Siblings" class="border-slate-200 rounded-xl p-3 border" required>
                                <input type="number" name="siblings_deceased" placeholder="Deceased Siblings" class="border-slate-200 rounded-xl p-3 border" required>
                                <input type="number" name="children_living" placeholder="Living Children" class="border-slate-200 rounded-xl p-3 border" required>
                                <input type="number" name="children_deceased" placeholder="Deceased Children" class="border-slate-200 rounded-xl p-3 border" required>
                            </div>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Medical & Family Health</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <textarea placeholder="Past Illnesses or Hospitalizations" name="past_illness" class="border-slate-200 rounded-xl p-3 border h-24" required></textarea>
                            <textarea placeholder="Current Medications" name="current_medication" class="border-slate-200 rounded-xl p-3 border h-24" required></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="number" name="father_living_age" placeholder="Father's Age" class="border-slate-200 rounded-xl p-3 border">
                            <input type="text" name="father_health" placeholder="Father's Condition" class="border-slate-200 rounded-xl p-3 border">
                            <input type="number" name="mother_living_age" placeholder="Mother's Age" class="border-slate-200 rounded-xl p-3 border">
                            <input type="text" name="mother_health" placeholder="Mother's Condition" class="border-slate-200 rounded-xl p-3 border">
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Physician Details</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="text" placeholder="Physician Name" name="physician_name" class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" placeholder="Contact Number" name="contact_number" class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" placeholder="Clinic Address" name="clinic_address" class="border-slate-200 rounded-xl p-3 border md:col-span-2" required>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-6 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Health Questions</h2>
                        </div>
                        <p class="text-slate-500 mb-8 italic">If you answer YES to any of the following, please provide details.</p>
                        
                        <div class="space-y-6">
                            <?php 
                            $questions = [
                                "q1" => "1. Do you drive a motorcycle? (State frequency/purpose)",
                                "q2" => "2. Engaged in racing, skydiving, or hazardous activities?",
                                "q3" => "3. Intend to ride aircraft other than commercial?",
                                "q4" => "4. Enlisted or intend to enlist in military/naval service?",
                                "q5" => "5. Pending application for life or accident insurance?",
                                "q6" => "6. Have you been declined or cancelled by other insurers?",
                                "q7" => "7. Family history of heart disease, stroke, or cancer?",
                                "q8" => "8. Are you an incumbent or planning to be an elected official?",
                                "q9" => "9. Weight gain/loss in last 12 months?",
                                "q10a" => "10A. Ever discharged from employment/military for physical reasons?",
                                "q10b" => "10B. Applied for or received disability benefits?",
                                "q11" => "11. History of alcohol excess or habit-forming drugs?",
                                "q12" => "12. Engaged in car racing or scuba diving?"
                            ];
                            foreach($questions as $key => $text): ?>
                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                <label class="block font-semibold text-slate-700 mb-3"><?php echo $text; ?></label>
                                <div class="flex flex-col md:flex-row gap-4">
                                    <select name="<?php echo $key; ?>_response" class="border-slate-200 rounded-xl p-3 border bg-white md:w-32">
                                        <option>No</option>
                                        <option>Yes</option>
                                    </select>
                                    <textarea name="<?php echo $key; ?>_details" placeholder="Please provide details here..." class="flex-1 border-slate-200 rounded-xl p-3 border bg-white h-20" disabled></textarea>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="step-content">
                        <div class="flex items-center mb-8 space-x-3">
                            <div class="w-2 h-8 bg-cyan-500 rounded-full"></div>
                            <h2 class="text-2xl font-bold text-slate-800">Final Details & Signature</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <input type="text" name="height" placeholder="Height (cm/in)" class="border-slate-200 rounded-xl p-3 border" required>
                            <input type="text" name="weight" placeholder="Weight (kg/lbs)" class="border-slate-200 rounded-xl p-3 border" required>
                            <select name="pregnant_question" class="border-slate-200 rounded-xl p-3 border">
                                <option value="" disabled selected>Are you currently pregnant?</option>
                                <option value="No">No</option>
                                <option value="Yes">Yes</option>
                            </select>
                            <select name="good_standing" class="border-slate-200 rounded-xl p-3 border">
                                <option value="" disabled selected>Good Standing?</option>
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                            </select>
                            
                            <div class="md:col-span-2 bg-cyan-50/50 p-8 rounded-3xl border-2 border-dashed border-cyan-200 text-center">
                                <label class="block text-cyan-800 font-bold mb-4 text-lg">Upload Digital Signature</label>
                                <input type="file" name="signature_file" accept="image/*" class="mx-auto block w-full max-w-xs text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-cyan-600 file:text-white hover:file:bg-cyan-700 cursor-pointer" required>
                                <div class="mt-6 inline-block bg-white p-4 rounded-2xl shadow-inner border border-slate-100">
                                    <img id="signaturePreview" class="max-h-32 min-w-[200px] object-contain" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" alt="Preview" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="step-content text-center py-12">
                        <div class="w-24 h-24 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-slate-800 mb-4">Verification Ready</h2>
                        <p class="text-slate-500 max-w-md mx-auto mb-10 text-lg">Please ensure all information provided is accurate. You can go back to any step to make corrections.</p>
                        <button type="submit" class="bg-cyan-600 text-white font-bold py-4 px-12 rounded-2xl hover:bg-cyan-700 hover:shadow-xl hover:shadow-cyan-200 transition-all transform hover:-translate-y-1">
                            Confirm & Submit Application
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center mt-12 pt-8 border-t border-slate-100">
                    <button type="button" id="prevBtn" class="flex items-center space-x-2 text-slate-400 font-bold px-6 py-3 rounded-xl hover:bg-slate-100 hover:text-slate-600 disabled:opacity-0 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        <span>Back</span>
                    </button>
                    <button type="button" id="nextBtn" class="bg-slate-800 text-white font-bold px-10 py-3 rounded-xl hover:bg-cyan-600 shadow-lg shadow-slate-200 hover:shadow-cyan-100 transition-all active:scale-95">
                        Continue
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    const steps = ["Profile", "Contact", "Work", "Plan", "Beneficiaries", "Family", "Health", "Doctor", "Questions", "Signature", "Finish"];
    const stepContents = document.querySelectorAll('.step-content');
    const stepHeaderContainer = document.querySelector('.grid');
    const stepTemplate = document.querySelector('#step-header-template');
    let currentStep = 0;

    // Initialize Stepper
    steps.forEach((label, idx) => {
        const clone = stepTemplate.content.cloneNode(true);
        clone.querySelector('.step-circle').textContent = idx + 1;
        clone.querySelector('.step-label').textContent = label;
        stepHeaderContainer.appendChild(clone);
    });

    const updateStep = () => {
        stepContents.forEach((el, i) => el.classList.toggle('active', i === currentStep));
        const circles = document.querySelectorAll('.step-circle');
        const labels = document.querySelectorAll('.step-label');
        
        circles.forEach((circle, i) => {
            circle.className = "step-circle w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300 ring-4 ring-white shadow-sm z-10";
            if (i === currentStep) {
                circle.classList.add('bg-cyan-600', 'text-white', 'scale-125');
                labels[i]?.classList.add('text-cyan-600');
                labels[i]?.classList.remove('text-slate-400');
            } else if (i < currentStep) {
                circle.classList.add('bg-cyan-100', 'text-cyan-600');
            } else {
                circle.classList.add('bg-slate-100', 'text-slate-400');
            }
        });

        document.getElementById('prevBtn').disabled = currentStep === 0;
        document.getElementById('nextBtn').textContent = currentStep === steps.length - 1 ? 'Go to Finish' : 'Continue';
        document.getElementById('nextBtn').style.display = currentStep === steps.length - 1 ? 'none' : 'block';
    };

    // Navigation Logic
    document.getElementById('nextBtn').addEventListener('click', () => {
        const currentInputs = stepContents[currentStep].querySelectorAll('input[required], select[required], textarea[required]');
        let allFilled = true;
        currentInputs.forEach(input => {
            if (!input.value.trim()) {
                allFilled = false;
                input.classList.add('border-red-500', 'bg-red-50');
            } else {
                input.classList.remove('border-red-500', 'bg-red-50');
            }
        });

        if (!allFilled) {
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Please complete the required fields.', confirmButtonColor: '#06b6d4' });
            return;
        }

        if (currentStep < steps.length - 1) {
            currentStep++;
            updateStep();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        if (currentStep > 0) {
            currentStep--;
            updateStep();
        }
    });

    // Form Submit with Confirmation
    document.getElementById('multiStepForm').addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Ready to submit?',
            text: "Please double check your information.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#06b6d4',
            confirmButtonText: 'Yes, Submit!'
        }).then((result) => {
            if (result.isConfirmed) this.submit();
        });
    });

    // Handle Question Logic
    document.querySelectorAll('select[name$="_response"]').forEach(select => {
        select.addEventListener('change', function() {
            const detailsField = document.querySelector(`[name="${this.name.replace('_response', '_details')}"]`);
            if (this.value === "Yes") {
                detailsField.disabled = false;
                detailsField.classList.remove('bg-slate-50');
                detailsField.focus();
            } else {
                detailsField.disabled = true;
                detailsField.classList.add('bg-slate-50');
                detailsField.value = '';
            }
        });
    });

    // Signature Preview
    document.querySelector('input[name="signature_file"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('signaturePreview').src = e.target.result;
            reader.readAsDataURL(file);
        }
    });

    // Age Calculation Logic
    document.getElementById('birthdate').addEventListener('change', function() {
        const birthdate = new Date(this.value);
        const today = new Date();
        let age = today.getFullYear() - birthdate.getFullYear();
        const m = today.getMonth() - birthdate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthdate.getDate())) age--;
        document.getElementById('age').value = age;
    });

    // Dynamic Beneficiaries
    function addBeneficiary() {
        const container = document.getElementById('beneficiaries-container');
        if (container.querySelectorAll('.beneficiary-group').length >= 5) {
            Swal.fire({ icon: 'info', title: 'Limit Reached', text: 'Max 5 beneficiaries allowed.' });
            return;
        }
        const group = document.createElement('div');
        group.className = "beneficiary-group grid grid-cols-1 md:grid-cols-4 gap-4 p-6 rounded-2xl border border-slate-100 bg-slate-50/50 mt-4";
        group.innerHTML = `
            <select class="border-slate-200 rounded-xl p-3 border bg-white" name="benefit_type[]">
                <option value="Revocable">Revocable</option>
                <option value="Irrevocable">Irrevocable</option>
            </select>
            <input type="text" name="benefit_name[]" placeholder="Full Name" class="border-slate-200 rounded-xl p-3 border bg-white">
            <input type="date" name="benefit_birthdate[]" class="border-slate-200 rounded-xl p-3 border bg-white">
            <input type="text" name="benefit_relationship[]" placeholder="Relationship" class="border-slate-200 rounded-xl p-3 border bg-white">
        `;
        container.appendChild(group);
    }

    updateStep();
</script>

<?php include '../../includes/footer.php'; ?>