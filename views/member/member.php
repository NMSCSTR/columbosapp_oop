<?php
    require_once '../../middleware/auth.php';
    authorize(['admin', 'member']);
    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/adminModel/userModel.php';
    include '../../models/adminModel/councilModel.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
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
    <?php include '../../partials/sidebar.php'?>
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
                        action="<?php echo BASE_URL ?>controllers/member/addMemberApplicant.php" class="p-6 space-y-6">
                        <!-- Step Content Containers -->
                        <div id="stepContents">
                            <!-- Step 1 -->
                            <div class="step-content active">
                                <h2 class="text-lg font-bold mb-4">Step 1: Personal Info</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" value="<?= $_SESSION['user_id'] ?>">
                                    <input type="text" placeholder="First Name" name="firstname"
                                        class="border rounded p-2">
                                    <input type="text" placeholder="Last Name" name="lastname"
                                        class="border rounded p-2">
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
                                    <input type="text" placeholder="Street" class="border rounded p-2">
                                    <input type="text" placeholder="Barangay" class="border rounded p-2">
                                    <input type="text" placeholder="City/Province" class="border rounded p-2">
                                    <input type="text" placeholder="Mobile Number" class="border rounded p-2">
                                    <input type="email" placeholder="Email Address" class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 3: Employment Details</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="Employer Name" class="border rounded p-2">
                                    <input type="text" placeholder="Employer Address" class="border rounded p-2">
                                    <input type="text" placeholder="Employer Email Address" class="border rounded p-2">
                                    <input type="text" placeholder="Employer Mobile Number" class="border rounded p-2">
                                    <input type="text" placeholder="Occupation" class="border rounded p-2">
                                    <input type="number" placeholder="Monthly Income" class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 4: Plan Information</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <select class="border rounded p-2">
                                        <option>Select Plan</option>
                                        <?php
                                            $fraternalBenefitsModel = new fraternalBenefitsModel($conn);
                                            $fraternalBenefits      = $fraternalBenefitsModel->getAllFraternalBenefits();

                                            if ($fraternalBenefits) {
                                            foreach ($fraternalBenefits as $benefit) {?>
                                        <option value="<?php echo $benefit['id'] ?>"><?php echo $benefit['name'] ?>
                                        </option>
                                        <?php
                                            }
                                            }
                                        ?>
                                    </select>
                                    <select class="border rounded p-2">
                                        <option selected disabled>Mode of payment</option>
                                        <option name="monthly">Monthly</option>
                                        <option name="semi-annually">Semi-Annually</option>
                                        <option name="quarterly">Quarterly</option>
                                    </select>
                                    <select class="border rounded p-2">
                                        <option selected disabled>Currency</option>
                                        <option name="PHP">PHP</option>
                                    </select>
                                    <select class="border rounded p-2">
                                        <option selected disabled>Select Council</option>
                                        <?php $councilModel = new CouncilModel($conn);
                                            $councils       = $councilModel->getAllCouncil();
                                            if ($councils) {
                                            foreach ($councils as $council) {?>
                                        <option value="<?php echo $council['council_id'] ?>">
                                            <?php echo $council['council_name'] ?>
                                        </option>
                                        <?php
                                                    }
                                                    }
                                                ?>
                                    </select>
                                    <input type="number" placeholder="Payment Amount" class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 5: Beneficiaries</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="Beneficiary Name" class="border rounded p-2">
                                    <input type="text" placeholder="Relationship to Proposed Assured"
                                        class="border rounded p-2">
                                    <input type="number" placeholder="Percentage (%)" class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 6 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 6: Family Background</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="Father's Name" class="border rounded p-2">
                                    <input type="text" placeholder="Mother's Name" class="border rounded p-2">
                                    <input type="text" placeholder="Spouse's Name" class="border rounded p-2">
                                    <input type="text" placeholder="Number of Children" class="border rounded p-2">
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
                                    <input type="text" placeholder="Physician Name" class="border rounded p-2">
                                    <input type="text" placeholder="Contact Number" class="border rounded p-2">
                                    <input type="text" placeholder="Clinic Address" class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 9 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 9: Health Questions</h2>
                                <p>If you answered YES to any of the following questions, please provide details to
                                    space provided below.</p>
                                <div class="grid grid-cols-1 gap-4">
                                    <label>1. Do you drive a motorcycle? If yes, please state how often and for what
                                        purpose.? <select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>2. Are you engaged in auto/motorboat racing, sky/scuba diving or other
                                        hazardous
                                        avocations? <select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>3. Do you intend to ride an aircraft other than as a passenger in a
                                        commercial
                                        passenger airline?<select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>4. Are you now or do you intend to be enlisted with the military, naval, or
                                        air
                                        force service other than as a reserve?
                                        <select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>5. Do you have any pending application for life insurance or accident
                                        insurance?
                                        <select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>6. Have you made an application for the life insurance, or for reinstatement
                                        policy with other insurance company/ies which was declined, postponed, cancelled
                                        or modified in terms of the plan, amount or rate? <select
                                            class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <!-- <label>Any disabilities? <input type="text" placeholder="If yes, specify"
                                            class="border rounded p-2 w-full"></label> -->
                                    <p>Please give
                                        details to the YES answers you indicated on item 1-6</p>
                                    <textarea id="message" rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Please give details here..."></textarea>

                                    <label>7. Have any of your parents/siblings, died/suffered from heart disease,
                                        stroke, high blood pressure, diabetes or cancer? <select
                                            class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>8.Are you an incumbent elected official, local or national or planning,
                                        contemplating to hold any elective position?<select
                                            class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>9.Have you lost/gained weight during the past 12 months? How many
                                        pounds/kilos? Why?<select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>10. A .Have you, for physical reason, ever been discharged from employment,
                                        active military or naval service?<select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>B.Have you applied for or received disability benefits or pension from any
                                        source?<select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>11.Have you used alcoholic beverages in excess, taken habit forming drugs or
                                        sought advice or treatment for alcoholism, drugs or other forms of
                                        addiction?<select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <label>12. Are you engaged in any hazardous avocation like car/motorcycle racing or
                                        scuba diving? How often?<select class="border rounded py-3 px-4">
                                            <option>No </option>
                                            <option>Yes </option>
                                        </select></label>
                                    <p>Please give
                                        details to the YES answers you indicated</p>
                                    <textarea id="message" rows="4"
                                        class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Please give details here..."></textarea>
                                </div>
                            </div>

                            <!-- Step 10 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 10: Personal Signature</h2>
                                <div class="grid grid-cols-1 gap-4">
                                    <label class="block">
                                        Upload Signature:
                                        <input type="file" accept="image/*" class="mt-2 border p-2 w-full">
                                    </label>
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
            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>