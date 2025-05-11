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
<div class="max-w-4xl mx-auto border border-black p-6 bg-white">
    <h1 class="text-xl font-bold text-center mb-2">KCFAPI PERSONAL INFORMATION SHEET</h1>
    <p class="text-sm mb-4 text-center">
        Information to be obtained from all new staff and passed onto line manager / supervisor.<br>
        <span class="font-bold">Please ensure all information is completed in full.</span>
    </p>

    <!-- Personal Details -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">APPLICANT INFORMATION</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Firstname: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['firstname']?>" /></label>
            <label>Lastname: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['lastname']?>" /></label>
            <label>Middlename: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['middlename']?>" /></label>
            <label>Age: <input type="text" class="border w-full mt-1" value="<?= $applicantData['age']?>" /></label>
            <label>Birthdate: <input type="text" class="border w-full mt-1"
                    value=" <?= $applicantData['birthdate']?>" /></label>
            <label>Birthplace: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['birthplace']?>" /></label>
            <label>Date of birth: <input type="date" class="border w-full mt-1" /></label>
            <label>Gender:
                <input type="text" class="border w-full mt-1" value="<?= $applicantData['gender']?>" />
            </label>
            <label>Marital Status: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['marital_status']?>" /></label>
            <label>TIN/SSS: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['tin_sss']?>" /></label>
            <label>Nationality: <input type="text" class="border w-full mt-1"
                    value=" <?= $applicantData['nationality']?>" /></label>
        </div>
    </div>

    <!-- Emergency Contact Details -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Contact Details:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Mobile Number: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mobile_number']?>" /></label>
            <label>Email Address: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['email_address']?>" /></label>
            <label>Barangay: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['barangay']?>" /></label>
            <label>City/Province: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['city_province']?>" /></label>
            <label class="col-span-2">Street:
                <input type="text" class="border w-full mt-1" value="<?= $applicantData['street']?>" />
            </label>
        </div>
    </div>

    <!-- Employment details -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Employment Details:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Occupation: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['occupation']?>" /></label>
            <label>Employment Status: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employment_status']?>" /></label>
            <label>Duties: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['duties']?>" /></label>
            <label>Employer: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employer']?>" /></label>
            <label>Nature of businness: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['nature_business']?>" /></label>
            <label>Employer Mobile Number: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employer_mobile_number']?>" /></label>
            <label>Employer Email Address: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employer_email_address']?>" /></label>
            <label>Monthlly Income: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['monthly_income']?>" /></label>
        </div>
    </div>

    <!-- Employment details -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Employment Details:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Occupation: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['occupation']?>" /></label>
            <label>Employment Status: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employment_status']?>" /></label>
            <label>Duties: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['duties']?>" /></label>
            <label>Employer: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employer']?>" /></label>
            <label>Nature of businness: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['nature_business']?>" /></label>
            <label>Employer Mobile Number: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employer_mobile_number']?>" /></label>
            <label>Employer Email Address: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['employer_email_address']?>" /></label>
            <label>Monthlly Income: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['monthly_income']?>" /></label>
        </div>
    </div>

    <!-- Plan details -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Plan Details:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Fraternal Benefits: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['fraternal_benefits_id']?>" /></label>
            <label>Council: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['council_id']?>" /></label>
            <label>Payment Mode: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['payment_mode']?>" /></label>
            <label>Contribution Amount: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['contribution_amount']?>" /></label>
            <label>Currency: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['currency']?>" /></label>
        </div>
    </div>
    <!-- Beneficiaries -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Beneficiary Details:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Beneficiaries Type: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['benefit_type']?>" /></label>
            <label>Beneficiary Name: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['benefit_name']?>" /></label>
            <label>Beneficiary Birthday: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['benefit_birthdate']?>" /></label>
            <label>Relationship: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['benefit_relationship']?>" /></label>
        </div>
    </div>
    <!-- Family Background -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Family Background Details:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Father lastname: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['father_lastname']?>" /></label>
            <label>Father firstname: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['father_firstname']?>" /></label>
            <label>Father MI: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['father_mi']?>" /></label>
            <label>Mother lastname: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mother_lastname']?>" /></label>
            <label>Mother firstname: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mother_firstname']?>" /></label>
            <label>Mother MI: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mother_mi']?>" /></label>
            <label>Siblings Living: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['siblings_living']?>" /></label>
            <label>Sibling Deceased: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['siblings_deceased']?>" /></label>
            <label>Children living: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['children_living']?>" /></label>
            <label>Children Deceased: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['children_deceased']?>" /></label>
        </div>
    </div>
    <!-- Medical and Family health history -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Medical history:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Past Illness: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['past_illness']?>" /></label>
            <label>Current Medication: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['current_medication']?>" /></label>
        </div>
    </div>
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1">Family health history:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Father living age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['father_living_age']?>" /></label>
            <label>Father health: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['father_health']?>" /></label>
            <label>Mother living age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mother_living_age']?>" /></label>
            <label>Mother health: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mother_health']?>" /></label>
            <label>Siblings living age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['siblings_living_age']?>" /></label>
            <label>Siblings health: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['siblings_health']?>" /></label>
            <label>Children living age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['children_living_age']?>" /></label>
            <label>Children health <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['children_health']?>" /></label>
            <label>Father death age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['father_death_age']?>" /></label>
            <label>Father cause of death: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['father_cause']?>" /></label>
            <label>Mother death age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mother_death_age']?>" /></label>
            <label>Mother cause of death: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['mother_cause']?>" /></label>
            <label>Sbiling death age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['siblings_death_age']?>" /></label>
            <label>Sibling cause of death: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['siblings_cause']?>" /></label>
            <label>Children death age: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['children_death_age']?>" /></label>
            <label>Children cause of death: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['children_cause']?>" /></label>
        </div>
    </div>

    <!--  Physician Details -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1"> Physician Details:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Physician Name: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['physician_name']?>" /></label>
            <label>Contact Number: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['contact_number']?>" /></label>
            <label>Address: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['physician_address']?>" /></label>
        </div>
    </div>

    <!--  Physician Details -->
    <div class="border border-black">
        <div class="bg-black text-white font-bold px-2 py-1"> To be continue:</div>
        <div class="grid grid-cols-2 gap-2 p-2 text-sm">
            <label>Physician Name: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['physician_name']?>" /></label>
            <label>Contact Number: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['contact_number']?>" /></label>
            <label>Address: <input type="text" class="border w-full mt-1"
                    value="<?= $applicantData['physician_address']?>" /></label>
        </div>
    </div>

    <p class="text-xs text-center">Page 1 of 2</p>
</div>




<?php
include '../../includes/footer.php';
?>