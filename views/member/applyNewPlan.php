<?php
require_once '../../middleware/auth.php';
authorize(['member']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../models/memberModel/memberApplicationModel.php';

$applicationModel = new MemberApplicationModel($conn);
$applicant = $applicationModel->getApplicantByUserId($_SESSION['user_id']);
$plans = mysqli_query($conn, "SELECT * FROM fraternal_benefits"); // list of plans
?>

<form action="submitPlan.php" method="POST">
    <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
    <input type="hidden" name="applicant_id" value="<?php echo $applicant['applicant_id']; ?>">

    First Name: <input type="text" name="firstname" value="<?php echo $applicant['firstname']; ?>"><br>
    Last Name: <input type="text" name="lastname" value="<?php echo $applicant['lastname']; ?>"><br>
    <!-- add other fields similarly -->

    Select Plan:
    <select name="fraternal_benefits_id">
        <?php while($plan = mysqli_fetch_assoc($plans)): ?>
            <option value="<?php echo $plan['id']; ?>"><?php echo $plan['name']; ?></option>
        <?php endwhile; ?>
    </select><br>

    Contribution Amount: <input type="number" name="contribution_amount"><br>
    Payment Mode: <input type="text" name="payment_mode"><br>

    <button type="submit">Apply</button>
</form>
