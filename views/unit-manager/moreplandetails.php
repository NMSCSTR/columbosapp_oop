<?php
    require_once '../../middleware/auth.php';
    authorize(['admin', 'fraternal-counselor','unit-manager']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';


    if (!isset($_GET['id'])) {
        die("ID is required.");
    }
    
    $id = intval($_GET['id']);
    $model = new fraternalBenefitsModel($conn);
    $benefit = $model->getFraternalBenefitById($id);
    
    if (!$benefit) {
        die("No plan found.");
    }
?>

<div class="flex flex-col justify-center items-center min-h-screen">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden max-w-5xl w-full mx-4">
        <img src="<?php echo BASE_URL . $benefit['image']; ?>" alt="Plan Image" class="w-full h-64 object-cover">

        <div class="p-8"> <!-- slightly more padding for wider layout -->
            <h2 class="text-3xl font-bold text-gray-800 mb-4">
                <?php echo htmlspecialchars($benefit['name']); ?>
            </h2>
            <p class="text-gray-700 leading-tight mb-4 text-lg">
                <?php echo nl2br(htmlspecialchars($benefit['type'])); ?>
            </p>

            <div class="mb-4">
                <strong>About:</strong>
                <p class="text-gray-700 leading-tight mb-4">
                    <?php echo htmlspecialchars($benefit['about']); ?>
                </p>
                <strong>Benefits:</strong>
                <p class="text-gray-700 leading-tight mb-4">
                    <?php echo htmlspecialchars($benefit['benefits']); ?>
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <strong>Contribution Period:</strong>
                        <p class="text-gray-700 leading-tight">
                            <?php echo htmlspecialchars($benefit['contribution_period']); ?> years
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <strong>Face value:</strong>
                        <p class="text-gray-700 leading-tight">
                            PHP <?php echo number_format($benefit['face_value'],2); ?>
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <strong>Years to maturity:</strong>
                        <p class="text-gray-700 leading-tight">
                            <?php echo $benefit['years_to_maturity']; ?> years
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-md">
                        <strong>Years of protection:</strong>
                        <p class="text-gray-700 leading-tight">
                            <?php echo $benefit['years_of_protection']; ?> years
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex justify-between mt-6">
                <a href="unitmanager.php" class="text-blue-600 hover:underline">← Back to list</a>
            </div>
        </div>
    </div>
</div>


<?php
include '../../includes/footer.php';
?>