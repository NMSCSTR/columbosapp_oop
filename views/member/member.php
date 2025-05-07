<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';

$userModel = new UserModel($conn);
$user      = $userModel->getUserById($_SESSION['user_id']);
// var_dump($user);
?>
<style>
.step-content {
    display: none;
}

.step-content.active {
    display: block;
}

input.border-red-500,
select.border-red-500,
textarea.border-red-500 {
    border-width: 2px;
}
</style>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php' ?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">

            <h3 class="text-3xl text-center font-extrabold dark:text-white mb-8">Application form for
                <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?></h3>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <!-- Responsive grid layout -->
                <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden">
                    <!-- Stepper Header -->
                    <div
                        class="grid grid-cols-11 border-b border-cyan-200 text-center text-xs font-semibold text-cyan-700">
                        <template id="step-header-template">
                            <div class="p-2 border-r last:border-r-0 relative">
                                <div class="flex justify-center mb-2">
                                    <div
                                        class="step-circle w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm text-white">
                                    </div>
                                </div>
                                <h3 class="step-label"></h3>
                            </div>
                        </template>
                    </div>

                    <!-- Form -->
                    <form id="multiStepForm" method="POST"
                        action="<?php echo BASE_URL ?>controllers/member/addMemberApplicant.php"
                        enctype="multipart/form-data" class="p-6 space-y-6">
                        <!-- Step Content Containers -->
                        <div id="stepContents">
                            <!-- Step 1 -->
                            <div class="step-content active">
                                <h2 class="text-lg font-bold mb-4">Step 1: Personal Info</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="hidden" name="id" value="<?php echo $_SESSION['user_id'] ?>">
                                    <input type="text" placeholder="First Name"
                                        value="<?php echo htmlspecialchars($user['firstname']); ?>" name="firstname"
                                        class="border rounded p-2" readonly>
                                    <input type="text" placeholder="Last Name"
                                        value="<?php echo htmlspecialchars($user['lastname']); ?>" name="lastname"
                                        class="border rounded p-2" readonly>
                                    <input type="text" placeholder="Middle Name" name="middlename"
                                        class="border rounded p-2">
                                    <input type="date" placeholder="Birthdate" name="birthdate"
                                        class="border rounded p-2">
                                    <input type="number" placeholder="Age" name="age" class="border rounded p-2">
                                    <input type="text" placeholder="Birthplace" name="birthplace"
                                        class="border rounded p-2">
                                    <select class="border rounded p-2" name="gender">
                                        <option>Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                    <select class="border rounded p-2" name="marital_status">
                                        <option disabled>Marital Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                    </select>
                                    <input type="text" placeholder="TIN/SSS" name="tin_sss" class="border rounded p-2">
                                    <input type="text" placeholder="Nationality" class="border rounded p-2"
                                        name="nationality">
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 2: Contact Details</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="Street" name="street" class="border rounded p-2">
                                    <input type="text" placeholder="Barangay" name="barangay"
                                        class="border rounded p-2">
                                    <input type="text" placeholder="City/Province" name="city_province"
                                        class="border rounded p-2">
                                    <input type="text" placeholder="Mobile Number" name="mobile_number"
                                        class="border rounded p-2">
                                    <input type="email" placeholder="Email Address" name="email_address"
                                        class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 3: Employment Details</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="Occupation" name="occupation"
                                        class="border rounded p-2">
                                    <select class="border rounded p-2" name="employment_status">
                                        <option selected>Employment Status</option>
                                        <option value="employed">Employed</option>
                                        <option value="self_employed">Self Employed</option>
                                    </select>
                                    <input type="text"
                                        placeholder="Specific duties (e.g.Develops software applications.)"
                                        name="duties" class="border rounded p-2">
                                    <input type="text" placeholder="Employer (e.g. TechCorp Inc.)" name="employer"
                                        class="border rounded p-2">
                                    <input type="text" placeholder="Work (e.g. IT Department.)" name="work"
                                        class="border rounded p-2">
                                    <input type="text" placeholder="Nature of business (e.g.Information Technology)"
                                        name="nature_business" class="border rounded p-2">
                                    <input type="text" placeholder="Employer Email Address"
                                        name="employer_email_address" class="border rounded p-2">
                                    <input type="text" placeholder="Employer Mobile Number"
                                        name="employer_mobile_number" class="border rounded p-2">
                                    <input type="number" placeholder="Monthly Income" name=monthly_income"
                                        class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 4: Plan Information</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <select class="border rounded p-2" name="fraternal_benefits_id">
                                        <option>Select Plan</option>
                                        <?php
                                        $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
                                        $fraternalBenefits      = $fraternalBenefitsModel->getAllFraternalBenefits();

                                        if ($fraternalBenefits) {
                                            foreach ($fraternalBenefits as $benefit) { ?>
                                        <option value="<?php echo $benefit['id'] ?>"><?php echo $benefit['name'] ?>
                                        </option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <select class="border rounded p-2" name="payment_mode">
                                        <option selected disabled>Mode of payment</option>
                                        <option name="monthly">Monthly</option>
                                        <option name="semi-annually">Semi-Annually</option>
                                        <option name="quarterly">Quarterly</option>
                                    </select>
                                    <select class="border rounded p-2" name="currency">
                                        <option selected disabled>Currency</option>
                                        <option name="PHP">PHP</option>
                                    </select>
                                    <select class="border rounded p-2" name="council_id">
                                        <option selected disabled>Select Council</option>
                                        <?php $councilModel = new CouncilModel($conn);
                                        $councils                                                   = $councilModel->getAllCouncil();
                                        if ($councils) {
                                            foreach ($councils as $council) { ?>
                                        <option value="<?php echo $council['council_id'] ?>">
                                            <?php echo $council['council_name'] ?>
                                        </option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <input type="number" placeholder="Payment Amount" name="contribution_amount"
                                        class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 5: Beneficiaries</h2>

                                <!-- Beneficiaries Container -->
                                <div id="beneficiaries-container" class="space-y-6">
                                    <!-- First Beneficiary Group -->
                                    <div
                                        class="beneficiary-group grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded">
                                        <!-- <input type="text" name="benefit_type[]" placeholder="Type of Benefit"
                                            class="border rounded p-2 w-full" required> -->
                                        <select class="border rounded p-2" name="benefit_type[]">
                                            <option selected disabled>Type of Benefit</option>
                                            <option name="Revocable">Revocable</option>
                                            <option name="Irrevocable">Irrevocable</option>
                                        </select>
                                        <input type="text" name="benefit_name[]" placeholder="Beneficiary Name"
                                            class="border rounded p-2 w-full">
                                        <input type="date" name="benefit_birthdate[]" placeholder="Birthdate"
                                            class="border rounded p-2 w-full">
                                        <input type="text" name="benefit_relationship[]" placeholder="Relationship"
                                            class="border rounded p-2 w-full">
                                        <!-- <input type="number" name="benefit_percentage[]" placeholder="Percentage (%)"
                                            class="border rounded p-2 w-full" required> -->
                                    </div>
                                </div>

                                <!-- Add Beneficiary Button -->
                                <button type="button" onclick="addBeneficiary()"
                                    class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
                                    + Add Another Beneficiary
                                </button>
                            </div>

                            <!-- Step 6: Family Background -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 6: Family Background</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Father Information -->
                                    <input type="text" name="father_lastname" placeholder="Father's Last Name"
                                        class="border rounded p-2">
                                    <input type="text" name="father_firstname" placeholder="Father's First Name"
                                        class="border rounded p-2">
                                    <input type="text" name="father_mi" placeholder="Father's Middle Initial"
                                        class="border rounded p-2">

                                    <!-- Mother Information -->
                                    <input type="text" name="mother_lastname" placeholder="Mother's Last Name"
                                        class="border rounded p-2">
                                    <input type="text" name="mother_firstname" placeholder="Mother's First Name"
                                        class="border rounded p-2">
                                    <input type="text" name="mother_mi" placeholder="Mother's Middle Initial"
                                        class="border rounded p-2">

                                    <!-- Siblings Information -->
                                    <input type="number" name="siblings_living" placeholder="Number of Living Siblings"
                                        class="border rounded p-2">
                                    <input type="number" name="siblings_deceased"
                                        placeholder="Number of Deceased Siblings" class="border rounded p-2">

                                    <!-- Children Information -->
                                    <input type="number" name="children_living" placeholder="Number of Living Children"
                                        class="border rounded p-2">
                                    <input type="number" name="children_deceased"
                                        placeholder="Number of Deceased Children" class="border rounded p-2">
                                </div>
                            </div>


                            <!-- Step 7 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 7: Medical History</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <textarea placeholder="Past Illnesses or Hospitalizations"
                                        class="border rounded p-2"></textarea>
                                    <textarea placeholder="Current Medications" class="border rounded p-2"></textarea>
                                </div>
                            </div>

                            <!-- Step 8 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 8: Physician Details</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="Physician Name" name="physician_name"
                                        class="border rounded p-2">
                                    <input type="text" placeholder="Contact Number" name="contact_number"
                                        class="border rounded p-2">
                                    <input type="text" placeholder="Clinic Address" name="clinic_address"
                                        class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 9 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 9: Health Questions</h2>
                                <p>If you answered YES to any of the following questions, please provide details to
                                    space provided below.</p>
                                <div class="grid grid-cols-1 gap-4">
                                    <!-- Question 1 -->
                                    <label>1. Do you drive a motorcycle? If yes, please state how often and for what
                                        purpose.
                                        <select name="q1_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q1_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 2 -->
                                    <label>2. Are you engaged in auto/motorboat racing, sky/scuba diving, or other
                                        hazardous activities?
                                        <select name="q2_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q2_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 3 -->
                                    <label>3. Do you intend to ride an aircraft other than as a passenger in a
                                        commercial passenger airline?
                                        <select name="q3_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q3_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 4 -->
                                    <label>4. Are you now or do you intend to be enlisted with the military, naval, or
                                        air force service other than as a reserve?
                                        <select name="q4_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q4_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 5 -->
                                    <label>5. Do you have any pending application for life insurance or accident
                                        insurance?
                                        <select name="q5_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q5_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 6 -->
                                    <label>6. Have you made an application for life insurance or for reinstatement of a
                                        policy with other insurance company/ies which was declined, postponed,
                                        cancelled, or modified in terms of the plan, amount, or rate?
                                        <select name="q6_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q6_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 7 -->
                                    <label>7. Have any of your parents/siblings died or suffered from heart disease,
                                        stroke, high blood pressure, diabetes, or cancer?
                                        <select name="q7_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q7_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 8 -->
                                    <label>8. Are you an incumbent elected official, local or national, or planning or
                                        contemplating to hold any elective position?
                                        <select name="q8_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q8_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 9 -->
                                    <label>9. Have you lost/gained weight during the past 12 months? How many
                                        pounds/kilos? Why?
                                        <select name="q9_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q9_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 10A -->
                                    <label>10A. Have you, for physical reason, ever been discharged from employment,
                                        active military or naval service?
                                        <select name="q10a_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q10a_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 10B -->
                                    <label>10B. Have you applied for or received disability benefits or pension from any
                                        source?
                                        <select name="q10b_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q10b_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 11 -->
                                    <label>11. Have you used alcoholic beverages in excess, taken habit-forming drugs,
                                        or sought advice or treatment for alcoholism, drugs, or other forms of
                                        addiction?
                                        <select name="q11_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q11_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                    <!-- Question 12 -->
                                    <label>12. Are you engaged in any hazardous avocation like car/motorcycle racing or
                                        scuba diving? How often?
                                        <select name="q12_response" class="border rounded py-3 px-4">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select>
                                    </label>
                                    <textarea name="q12_details" placeholder="Details (if Yes)"
                                        class="border rounded p-2 w-full"></textarea>

                                </div>
                            </div>

                            <!-- Step 10: Personal and Membership Details -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 10: Personal and Membership Information</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Height -->
                                    <input type="text" name="height" placeholder="Height (cm/in)"
                                        class="border rounded p-2" required>

                                    <!-- Weight -->
                                    <input type="text" name="weight" placeholder="Weight (kg/lbs)"
                                        class="border rounded p-2" required>

                                    <!-- Pregnancy Question -->
                                    <select name="pregnant_question" class="border rounded p-2" required>
                                        <option value="" disabled selected>Are you currently pregnant?</option>
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>
                                    </select>

                                    <!-- Upload Signature -->
                                    <label class="block col-span-2">
                                        Upload Signature:
                                        <input type="file" name="signature_file" accept="image/*"
                                            class="mt-2 border p-2 w-full" required>
                                    </label>
                                </div>

                                <h3 class="text-md font-semibold mt-6 mb-2">Membership Details</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Council ID -->
                                    <select class="border rounded" name="council_id">
                                        <option selected disabled>Select Council</option>
                                        <?php $councilModel = new CouncilModel($conn);
                                        $councils                                                   = $councilModel->getAllCouncil();
                                        if ($councils) {
                                            foreach ($councils as $council) { ?>
                                        <option value="<?php echo $council['council_id'] ?>">
                                            <?php echo $council['council_name'] ?>
                                        </option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>

                                    <!-- First Degree Date -->
                                    <label>
                                        First Degree Date:
                                        <input type="date" name="first_degree_date" class="border rounded p-2 w-full"
                                            required>
                                    </label>

                                    <!-- Present Degree -->
                                    <input type="text" name="present_degree" placeholder="Present Degree"
                                        class="border rounded p-2" required>

                                    <!-- Good Standing -->
                                    <select name="good_standing" class="border rounded p-2" required>
                                        <option value="" disabled selected>Good Standing?</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>


                            <!-- Step 11 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 11: Confirmation</h2>
                                <p class="mb-4 text-gray-700">Please review all your details before submitting.</p>
                                <button type="submit"
                                    class="bg-cyan-600 text-white py-2 px-4 rounded hover:bg-cyan-700">
                                    Submit Application
                                </button>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between pt-4 border-t">
                            <button type="button" id="prevBtn" class="bg-gray-300 px-4 py-2 rounded text-sm"
                                disabled>Previous</button>
                            <button type="button" id="nextBtn"
                                class="bg-cyan-500 text-white px-4 py-2 rounded text-sm">Next</button>
                        </div>
                    </form>
                </div>

                <script>
                const steps = [
                    "Personal Info", "Contact", "Employment", "Plan", "Beneficiaries",
                    "Family", "Medical", "Physician", "Health", "Signature", "Confirm"
                ];

                const stepContents = document.querySelectorAll('.step-content');
                const stepHeaderContainer = document.querySelector('.grid');
                const stepTemplate = document.querySelector('#step-header-template');
                let currentStep = 0;

                // Render step headers
                steps.forEach((label, idx) => {
                    const clone = stepTemplate.content.cloneNode(true);
                    clone.querySelector('.step-circle').textContent = idx + 1;
                    clone.querySelector('.step-label').textContent = label;
                    clone.querySelector('.step-circle').classList.add(idx === 0 ? 'bg-cyan-500' :
                        'bg-cyan-300');
                    stepHeaderContainer.appendChild(clone);
                });

                // Update the UI for the current step
                const updateStep = () => {
                    stepContents.forEach((el, i) => el.classList.toggle('active', i === currentStep));
                    const circles = document.querySelectorAll('.step-circle');
                    circles.forEach((circle, i) => {
                        circle.classList.remove('bg-cyan-500', 'bg-cyan-300');
                        circle.classList.add(i === currentStep ? 'bg-cyan-500' : 'bg-cyan-300');
                    });
                    document.getElementById('prevBtn').disabled = currentStep === 0;
                    document.getElementById('nextBtn').textContent = currentStep === steps.length - 1 ? 'Finish' :
                        'Next';
                };

                // Next button logic with validation
                document.getElementById('nextBtn').addEventListener('click', () => {
                    // Validate required fields before going to next step
                    const currentInputs = stepContents[currentStep].querySelectorAll(
                        'input[required], select[required], textarea[required]');
                    let allFilled = true;
                    currentInputs.forEach(input => {
                        if (!input.value.trim()) {
                            allFilled = false;
                            input.classList.add('border-red-500');
                        } else {
                            input.classList.remove('border-red-500');
                        }
                    });

                    if (!allFilled) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Incomplete Step',
                            text: 'Please fill in all required fields before proceeding.',
                            confirmButtonColor: '#06b6d4'
                        });
                        return;
                    }

                    // Proceed to next step or submit form
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        updateStep();
                    } else {
                        document.getElementById('multiStepForm').submit();
                    }
                });

                // Previous button
                document.getElementById('prevBtn').addEventListener('click', () => {
                    if (currentStep > 0) {
                        currentStep--;
                        updateStep();
                    }
                });


                updateStep();
                </script>

                <!-- JavaScript for Dynamic Fields -->
                <script>
                function addBeneficiary() {
                    const container = document.getElementById('beneficiaries-container');
                    const group = document.createElement('div');
                    group.className =
                        "beneficiary-group grid grid-cols-1 md:grid-cols-2 gap-4 border p-4 rounded";

                    group.innerHTML = `
                                <select class="border rounded p-2" name="benefit_type[]">
                                    <option selected disabled>Type of Benefit</option>
                                    <option name="Revocable">Revocable</option>
                                    <option name="Irrevocable">Irrevocable</option>
                                </select>
                                <input type="text" name="benefit_name[]" placeholder="Beneficiary Name" class="border rounded p-2 w-full">
                                <input type="date" name="benefit_birthdate[]" placeholder="Birthdate" class="border rounded p-2 w-full" >
                                <input type="text" name="benefit_relationship[]" placeholder="Relationship" class="border rounded p-2 w-full">
                            `;

                    container.appendChild(group);
                }
                </script>
            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>