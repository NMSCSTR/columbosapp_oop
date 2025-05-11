<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';
include '../../models/memberModel/memberApplicationModel.php';

$userModel = new UserModel($conn);
$user      = $userModel->getUserById($_SESSION['user_id']);
$fraternalCounselor = $userModel->getUserWhereRoleFraternalCounselor();


$applicationModel = new MemberApplicationModel($conn);
$applicantData = $applicationModel->fetchAllApplicantById($_SESSION['user_id']);

?>
<div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md">
    <h1 class="text-2xl font-bold mb-2"> KCFAPI PERSONAL INFORMATION SHEET</h1>
    <!-- <p class="italic text-sm mb-6">
      <strong>Directions:</strong> The information below will be used to fill out plan applications,
        Please fill out this sheet as completely
      and accurately as possible.
    </p> -->

    <!-- Applicant Information -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold border-b pb-1 mb-4">APPLICANT INFORMATION</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Firstname:</span> <?= $applicantData['firstname']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Lastname:</span> <?= $applicantData['lastname']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Middlename:</span> <?= $applicantData['middlename']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Age:</span> <?= $applicantData['age']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Birthdate:</span> <?= $applicantData['birthdate']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Birthplace:</span> <?= $applicantData['birthplace']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Gender:</span> <?= $applicantData['gender']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Marital Status:</span> <?= $applicantData['marital_status']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">TIN/SSS:</span> <?= $applicantData['tin_sss']?></h2>
            <h2 class="text-lg font-semibold  mb-4"><span class="text-red-500">Nationality:</span> <?= $applicantData['nationality']?></h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" placeholder="Street Address" class="input">
            <input type="text" placeholder="Apartment/Unit #" class="input">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <input type="text" placeholder="City" class="input">
            <input type="text" placeholder="State" class="input">
            <input type="text" placeholder="ZIP" class="input">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <input type="text" placeholder="Phone" class="input">
            <input type="email" placeholder="E-mail Address" class="input">
        </div>

        <div class="mb-2">
            <input type="text" placeholder="Social Security Number (Optional)" class="input w-full">
            <p class="text-xs italic text-red-500 mt-1">*Does not need to be included on this form, but should be
                memorized.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block mb-1 text-sm font-medium">Are you a citizen of the United States?</label>
                <div class="flex gap-4 items-center">
                    <label><input type="radio" name="usCitizen" /> YES</label>
                    <label><input type="radio" name="usCitizen" /> NO</label>
                </div>
                <input type="text" placeholder="If no, are you authorized to work in the U.S.? YES / NO"
                    class="input mt-2">
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">Have you ever been convicted of a felony?</label>
                <div class="flex gap-4 items-center">
                    <label><input type="radio" name="felony" /> YES</label>
                    <label><input type="radio" name="felony" /> NO</label>
                </div>
                <input type="text" placeholder="If so, when?" class="input mt-2">
            </div>
        </div>
    </div>

    <!-- Education Section -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold border-b pb-1 mb-4">EDUCATION</h2>

        <!-- High School -->
        <h3 class="text-md font-medium mb-2">High School</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-2">
            <input type="text" placeholder="From" class="input">
            <input type="text" placeholder="To" class="input">
            <input type="text" placeholder="Address" class="input col-span-2">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <input type="text" placeholder="City/State" class="input">
            <input type="text" placeholder="Degree" class="input">
            <label class="flex items-center gap-2">
                <input type="checkbox" /> Did you graduate?
            </label>
        </div>

        <!-- College/Tech School -->
        <h3 class="text-md font-medium mb-2">College/Technical School</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-2">
            <input type="text" placeholder="From" class="input">
            <input type="text" placeholder="To" class="input">
            <input type="text" placeholder="Address" class="input col-span-2">
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <input type="text" placeholder="City/State" class="input">
            <input type="text" placeholder="Degree" class="input">
            <label class="flex items-center gap-2">
                <input type="checkbox" /> Did you graduate?
            </label>
        </div>

        <input type="text" placeholder="Career/coursework" class="input w-full mb-2">
    </div>

    <!-- Achievements -->
    <div class="mb-6">
        <h2 class="text-lg font-semibold border-b pb-1 mb-4">ACHIEVEMENTS / AWARDS / HONORS / ORGANIZATIONS</h2>
        <p class="text-sm italic mb-2">
            Please list any achievements you have received and/or organizations and activities that you have
            participated in.
        </p>
        <textarea class="input w-full h-24 mb-2"></textarea>
        <textarea class="input w-full h-24 mb-2"></textarea>
        <textarea class="input w-full h-24 mb-2"></textarea>
    </div>

    <!-- Submit Button -->
    <div class="text-right">
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
            Submit
        </button>
    </div>
</div>

<style>
.input {
    @apply border border-gray-300 rounded px-3 py-2 text-sm;
}
</style>
<?php
include '../../includes/footer.php';
?>