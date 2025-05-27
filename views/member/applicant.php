<?php
require_once '../../middleware/auth.php';
authorize(['member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/memberModel/memberApplicationModel.php';

$userModel = new UserModel($conn);
$user = $userModel->getUserById($_SESSION['user_id']);
$fraternalCounselor = $userModel->getUserWhereRoleFraternalCounselor();

$applicationModel = new MemberApplicationModel($conn);
$applicantData = $applicationModel->fetchAllApplicantById($_SESSION['user_id']);
?>

<div class="max-w-5xl mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-300">
    <h1 class="text-3xl font-extrabold text-center mb-6 uppercase tracking-wide text-gray-800">
        KCFAPI Personal Information Sheet
    </h1>
    <p class="text-center text-gray-600 mb-8 text-sm leading-relaxed">
        Information will be reviewed by the unit manager and fraternal counselor.<br>
        <span class="font-semibold text-red-600">Please ensure all information is completed in full.</span>
    </p>

    <form action="" method="post" class="space-y-8">
        <!-- Applicant Information -->
        <section class="border border-gray-700 rounded-md">
            <header class="bg-gray-900 text-white font-semibold px-4 py-2 rounded-t-md">
                Applicant Information
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-gray-700 text-sm">
                <label class="flex flex-col">
                    Firstname:
                    <input type="text" name="firstname" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['firstname']) ?>" />
                </label>
                <label class="flex flex-col">
                    Lastname:
                    <input type="text" name="lastname" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['lastname']) ?>" />
                </label>
                <label class="flex flex-col">
                    Middlename:
                    <input type="text" name="middlename" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['middlename']) ?>" />
                </label>
                <label class="flex flex-col">
                    Age:
                    <input type="number" name="age" min="0" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['age']) ?>" />
                </label>
                <label class="flex flex-col">
                    Birthdate:
                    <input type="date" name="birthdate" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['birthdate']) ?>" />
                </label>
                <label class="flex flex-col">
                    Birthplace:
                    <input type="text" name="birthplace" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['birthplace']) ?>" />
                </label>
                <label class="flex flex-col">
                    Gender:
                    <select name="gender" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400">
                        <option value="">Select gender</option>
                        <option value="Male" <?= $applicantData['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $applicantData['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                        <option value="Other" <?= $applicantData['gender'] === 'Other' ? 'selected' : '' ?>>Other</option>
                    </select>
                </label>
                <label class="flex flex-col">
                    Marital Status:
                    <input type="text" name="marital_status" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['marital_status']) ?>" />
                </label>
                <label class="flex flex-col">
                    TIN/SSS:
                    <input type="text" name="tin_sss" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['tin_sss']) ?>" />
                </label>
                <label class="flex flex-col">
                    Nationality:
                    <input type="text" name="nationality" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['nationality']) ?>" />
                </label>
            </div>
        </section>

        <!-- Contact Details -->
        <section class="border border-gray-700 rounded-md">
            <header class="bg-gray-900 text-white font-semibold px-4 py-2 rounded-t-md">
                Contact Details
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-gray-700 text-sm">
                <label class="flex flex-col">
                    Mobile Number:
                    <input type="tel" name="mobile_number" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['mobile_number']) ?>" />
                </label>
                <label class="flex flex-col">
                    Email Address:
                    <input type="email" name="email_address" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['email_address']) ?>" />
                </label>
                <label class="flex flex-col">
                    Barangay:
                    <input type="text" name="barangay" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['barangay']) ?>" />
                </label>
                <label class="flex flex-col">
                    City/Province:
                    <input type="text" name="city_province" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['city_province']) ?>" />
                </label>
                <label class="flex flex-col md:col-span-2">
                    Street:
                    <input type="text" name="street" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['street']) ?>" />
                </label>
            </div>
        </section>

        <!-- Employment Details -->
        <section class="border border-gray-700 rounded-md">
            <header class="bg-gray-900 text-white font-semibold px-4 py-2 rounded-t-md">
                Employment Details
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-gray-700 text-sm">
                <label class="flex flex-col">
                    Occupation:
                    <input type="text" name="occupation" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['occupation']) ?>" />
                </label>
                <label class="flex flex-col">
                    Employment Status:
                    <input type="text" name="employment_status" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['employment_status']) ?>" />
                </label>
                <label class="flex flex-col">
                    Duties:
                    <input type="text" name="duties" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['duties']) ?>" />
                </label>
                <label class="flex flex-col">
                    Employer:
                    <input type="text" name="employer" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['employer']) ?>" />
                </label>
                <label class="flex flex-col">
                    Nature of Business:
                    <input type="text" name="nature_business" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['nature_business']) ?>" />
                </label>
                <label class="flex flex-col">
                    Employer Mobile Number:
                    <input type="tel" name="employer_mobile_number" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['employer_mobile_number']) ?>" />
                </label>
                <label class="flex flex-col">
                    Employer Email Address:
                    <input type="email" name="employer_email_address" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['employer_email_address']) ?>" />
                </label>
                <label class="flex flex-col">
                    Monthly Income:
                    <input type="number" step="0.01" name="monthly_income" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['monthly_income']) ?>" />
                </label>
            </div>
        </section>

        <!-- Plan Details -->
        <section class="border border-gray-700 rounded-md">
            <header class="bg-gray-900 text-white font-semibold px-4 py-2 rounded-t-md">
                Plan Details
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-gray-700 text-sm">
                <label class="flex flex-col">
                    Fraternal Benefits ID:
                    <input type="text" name="fraternal_benefits_id" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['fraternal_benefits_id']) ?>" />
                </label>
                <label class="flex flex-col">
                    Council ID:
                    <input type="text" name="council_id" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['council_id']) ?>" />
                </label>
                <label class="flex flex-col">
                    Payment Mode:
                    <input type="text" name="payment_mode" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['payment_mode']) ?>" />
                </label>
                <label class="flex flex-col">
                    Contribution Amount:
                    <input type="number" step="0.01" name="contribution_amount" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['contribution_amount']) ?>" />
                </label>
                <label class="flex flex-col">
                    Currency:
                    <input type="text" name="currency" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['currency']) ?>" />
                </label>
            </div>
        </section>

        <!-- Beneficiary Details -->
        <section class="border border-gray-700 rounded-md">
            <header class="bg-gray-900 text-white font-semibold px-4 py-2 rounded-t-md">
                Beneficiary Details
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-gray-700 text-sm">
                <label class="flex flex-col">
                    Beneficiary Type:
                    <input type="text" name="benefit_type" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['benefit_type']) ?>" />
                </label>
                <label class="flex flex-col">
                    Beneficiary Name:
                    <input type="text" name="benefit_name" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['benefit_name']) ?>" />
                </label>
                <label class="flex flex-col">
                    Beneficiary Birthday:
                    <input type="date" name="benefit_birthdate" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['benefit_birthdate']) ?>" />
                </label>
                <label class="flex flex-col">
                    Relationship:
                    <input type="text" name="benefit_relationship" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['benefit_relationship']) ?>" />
                </label>
            </div>
        </section>

        <!-- Family Background -->
        <section class="border border-gray-700 rounded-md">
            <header class="bg-gray-900 text-white font-semibold px-4 py-2 rounded-t-md">
                Family Background Details
            </header>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 text-gray-700 text-sm">
                <label class="flex flex-col">
                    Father Lastname:
                    <input type="text" name="father_lastname" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['father_lastname']) ?>" />
                </label>
                <label class="flex flex-col">
                    Father Firstname:
                    <input type="text" name="father_firstname" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['father_firstname']) ?>" />
                </label>
                <label class="flex flex-col">
                    Father MI:
                    <input type="text" name="father_mi" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['father_mi']) ?>" />
                </label>
                <label class="flex flex-col">
                    Mother Lastname:
                    <input type="text" name="mother_lastname" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['mother_lastname']) ?>" />
                </label>
                <label class="flex flex-col">
                    Mother Firstname:
                    <input type="text" name="mother_firstname" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['mother_firstname']) ?>" />
                </label>
                <label class="flex flex-col">
                    Mother MI:
                    <input type="text" name="mother_mi" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['mother_mi']) ?>" />
                </label>
                <label class="flex flex-col">
                    Siblings Living:
                    <input type="number" name="siblings_living" min="0" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['siblings_living']) ?>" />
                </label>
                <label class="flex flex-col">
                    Siblings Deceased:
                    <input type="number" name="siblings_deceased" min="0" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['siblings_deceased']) ?>" />
                </label>
                <label class="flex flex-col">
                    Children Living:
                    <input type="number" name="children_living" min="0" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['children_living']) ?>" />
                </label>
                <label class="flex flex-col">
                    Children Deceased:
                    <input type="number" name="children_deceased" min="0" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400" value="<?= htmlspecialchars($applicantData['children_deceased']) ?>" />
                </label>
            </div>
        </section>

        <!-- Medical and Family Health History -->
        <section class="border border-gray-700 rounded-md">
            <header class="bg-gray-900 text-white font-semibold px-4 py-2 rounded-t-md">
                Medical and Family Health History
            </header>
            <div class="grid grid-cols-1 gap-6 p-6 text-gray-700 text-sm">
                <label class="flex flex-col">
                    Medical History:
                    <textarea name="medical_history" rows="3" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($applicantData['medical_history']) ?></textarea>
                </label>
                <label class="flex flex-col">
                    Family Health History:
                    <textarea name="family_health_history" rows="3" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($applicantData['family_health_history']) ?></textarea>
                </label>
                <label class="flex flex-col">
                    Other Details:
                    <textarea name="other_details" rows="3" class="mt-1 p-2 border rounded focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($applicantData['other_details']) ?></textarea>
                </label>
            </div>
        </section>

        <!-- Submit Button -->
        <div class="text-center">
            <button type="submit" class="px-8 py-3 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded shadow-lg transition duration-300">
                Save Changes
            </button>
        </div>
    </form>
</div>



<?php
include '../../includes/footer.php';
?>