<?php
    require_once '../../middleware/auth.php';
    authorize(['admin', 'member']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../models/adminModel/userModel.php';
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
                <?php echo $_SESSION['firstname'] .' '. $_SESSION['lastname'] ?></h3>

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
                    <form id="multiStepForm" class="p-6 space-y-6">
                        <!-- Step Content Containers -->
                        <div id="stepContents">
                            <!-- Step 1 -->
                            <div class="step-content active">
                                <h2 class="text-lg font-bold mb-4">Step 1: Personal Info</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="First Name" class="border rounded p-2" required>
                                    <input type="text" placeholder="Last Name" class="border rounded p-2">
                                    <input type="text" placeholder="Middle Name" class="border rounded p-2">
                                    <input type="date" placeholder="Birthdate" class="border rounded p-2">
                                    <input type="text" placeholder="Birthplace" class="border rounded p-2">
                                    <select class="border rounded p-2">
                                        <option>Gender</option>
                                        <option>Male</option>
                                        <option>Female</option>
                                    </select>
                                    <select class="border rounded p-2">
                                        <option>Marital Status</option>
                                        <option>Single</option>
                                        <option>Married</option>
                                    </select>
                                    <input type="text" placeholder="TIN/SSS" class="border rounded p-2">
                                    <input type="text" placeholder="Nationality" class="border rounded p-2">
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
                                        <option>Plan A</option>
                                        <option>Plan B</option>
                                    </select>
                                    <input type="text" placeholder="Mode of Payment" class="border rounded p-2">
                                    <input type="number" placeholder="Payment Amount" class="border rounded p-2">
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="step-content">
                                <h2 class="text-lg font-bold mb-4">Step 5: Beneficiaries</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <input type="text" placeholder="Beneficiary Name" class="border rounded p-2">
                                    <input type="text" placeholder="Relationship" class="border rounded p-2">
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
                                <div class="grid grid-cols-1 gap-4">
                                    <label>Do you smoke? <select class="border rounded p-2">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select></label>
                                    <label>Do you drink alcohol? <select class="border rounded p-2">
                                            <option>No</option>
                                            <option>Yes</option>
                                        </select></label>
                                    <label>Any disabilities? <input type="text" placeholder="If yes, specify"
                                            class="border rounded p-2 w-full"></label>
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

                // Initial load
                updateStep();
                </script>
            </div>
    </main>
</div>

<?php
include '../../includes/footer.php';
?>