<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';
    include '../../includes/alert2.php';

    if (! isset($_GET['id'])) {
    header("Location: fraternalBenefits.php");
    exit;
    }

    $plan_id    = intval($_GET['id']);
    $model      = new fraternalBenefitsModel($conn);
    $plan       = $model->getFraternalBenefitById($plan_id);
    $categories = $model->getRateCategoriesByPlan($plan_id);
    $raw_rates  = $model->getRatesByPlan($plan_id);

    $matrix = [];
    foreach ($raw_rates as $r) {
    $ageKey = $r['min_age'] . '-' . $r['max_age'];
    if (! isset($matrix[$ageKey])) {
        $matrix[$ageKey] = [
            'min'   => $r['min_age'],
            'max'   => $r['max_age'],
            'rates' => [],
        ];
    }
    $matrix[$ageKey]['rates'][$r['category_id']] = $r['rate'];
    }

    usort($matrix, function ($a, $b) {
    return $a['min'] <=> $b['min'];
    });

    $adb_category_id = null;
    foreach ($categories as $cat) {
    if ($cat['is_adb']) {
        $adb_category_id = $cat['id'];
        break;
    }
    }

    foreach ($matrix as &$row) {
    $row['adb_rate'] = 0;
    if ($adb_category_id && isset($row['rates'][$adb_category_id])) {
        $row['adb_rate'] = $row['rates'][$adb_category_id];
    }
    }
    unset($row);
?>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>

    <main class="flex-1 p-6 sm:ml-64">

        <div class="mb-6">
            <a href="fraternalBenefits.php" class="text-sm text-blue-600 hover:underline mb-2 inline-block">&larr; Back to Plans</a>
            <h1 class="text-2xl font-bold text-gray-900">Manage Rates: <span class="text-blue-600"><?php echo htmlspecialchars($plan['name']) ?></span></h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Step 1: Add Column</h3>
                    <form action="<?php echo BASE_URL ?>controllers/adminController/manageRatesController.php" method="POST">
                        <input type="hidden" name="action" value="add_category">
                        <input type="hidden" name="plan_id" value="<?php echo $plan_id ?>">

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Column Name</label>
                                <input type="text" name="name" placeholder="e.g., 50K - <100K" class="w-full rounded-md border-gray-300 text-sm" required>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Min Face Value</label>
                                    <input type="number" name="min_face" placeholder="0" class="w-full rounded-md border-gray-300 text-sm" step="0.01">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Max (Optional)</label>
                                    <input type="number" name="max_face" placeholder="Infinity" class="w-full rounded-md border-gray-300 text-sm" step="0.01">
                                </div>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_adb" id="is_adb" value="1" class="rounded text-blue-600 focus:ring-blue-500">
                                <label for="is_adb" class="ml-2 text-xs text-gray-700">Is this ADB?</label>
                            </div>
                            <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white text-sm py-2 rounded-md">Add Column</button>
                        </div>
                    </form>

                    <div class="mt-4 pt-4 border-t">
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Current Columns</h4>
                        <ul class="space-y-2">
                            <?php foreach ($categories as $cat): ?>
                                <li class="flex justify-between items-center text-sm bg-gray-50 p-2 rounded border border-gray-100">
                                    <span class="<?php echo $cat['is_adb'] ? 'text-orange-600 font-semibold' : 'text-gray-700' ?>">
                                        <?php echo htmlspecialchars($cat['name']) ?>
                                    </span>
                                    <form action="<?php echo BASE_URL ?>controllers/adminController/manageRatesController.php" method="POST" onsubmit="return confirm('Delete this column? All associated rates will be lost.');">
                                        <input type="hidden" name="action" value="delete_category">
                                        <input type="hidden" name="plan_id" value="<?php echo $plan_id ?>">
                                        <input type="hidden" name="category_id" value="<?php echo $cat['id'] ?>">
                                        <button type="submit" class="text-red-400 hover:text-red-600 font-bold px-2">&times;</button>
                                    </form>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Step 2: Add Rate</h3>
                    <form action="<?php echo BASE_URL ?>controllers/adminController/manageRatesController.php" method="POST">
                        <input type="hidden" name="action" value="add_rate">
                        <input type="hidden" name="plan_id" value="<?php echo $plan_id ?>">

                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Select Column</label>
                                <select name="category_id" class="w-full rounded-md border-gray-300 text-sm" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['id'] ?>"><?php echo htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Age From</label>
                                    <input type="number" name="min_age" placeholder="1" class="w-full rounded-md border-gray-300 text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Age To</label>
                                    <input type="number" name="max_age" placeholder="30" class="w-full rounded-md border-gray-300 text-sm" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Annual Rate</label>
                                <input type="number" name="rate" placeholder="0.00" step="0.01" class="w-full rounded-md border-gray-300 text-sm" required>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm py-2 rounded-md">Add Rate</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 overflow-hidden">

                    <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-lg mb-4 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div>
                            <h3 class="text-sm font-bold text-indigo-900">Contribution Simulator</h3>
                            <p class="text-xs text-indigo-700">Enter a Test Face Value. Factors: SA (0.52), Q (0.27)</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-semibold text-gray-700">Face Value: ₱</label>
                            <input type="number" id="previewFaceValue" value="1000000"
                                class="w-40 rounded-md border-gray-300 text-sm font-bold text-right focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <?php if (empty($categories)): ?>
                        <div class="text-center py-10 text-gray-500 bg-gray-50 rounded-lg border border-dashed">
                            <p>Please add columns (Step 1) to see the table.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs border-collapse border border-gray-300">
                                <thead>
                                    <tr class="bg-blue-900 text-white">
                                        <th class="border border-gray-400 px-2 py-3 text-left w-16">Age</th>
                                        <?php foreach ($categories as $cat): ?>
                                            <?php if (! $cat['is_adb']): ?>
                                                <th class="border border-gray-400 px-2 py-3 text-center bg-blue-800 border-r-2 border-r-white min-w-[300px]" colspan="4">
                                                    <?php echo htmlspecialchars($cat['name']) ?>
                                                    <div class="grid grid-cols-4 gap-1 mt-1 text-[10px] font-normal opacity-90">
                                                        <span>Rate</span>
                                                        <span class="text-green-200">Annual</span>
                                                        <span class="text-green-200">Semi-Annual</span>
                                                        <span class="text-green-200">Quarterly</span>
                                                    </div>
                                                </th>
                                            <?php else: ?>
                                                <th class="border border-gray-400 px-2 py-3 text-center whitespace-nowrap bg-orange-800 w-20">
                                                    <?php echo htmlspecialchars($cat['name']) ?>
                                                </th>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($matrix)): ?>
                                        <tr><td colspan="100%" class="text-center py-4">No rates yet.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($matrix as $row): ?>
                                            <tr class="hover:bg-blue-50 odd:bg-white even:bg-gray-50">
                                                <td class="border border-gray-300 px-2 py-2 font-bold text-gray-800 text-center">
                                                    <?php echo ($row['min'] == $row['max']) ? $row['min'] : $row['min'] . "-" . $row['max']; ?>
                                                </td>

                                                <?php foreach ($categories as $cat): ?>
                                                    <?php
                                                        $basic_rate = isset($row['rates'][$cat['id']]) ? $row['rates'][$cat['id']] : 0;
                                                        $adb_rate   = $row['adb_rate'];
                                                        $total_rate = $basic_rate + $adb_rate;
                                                    ?>

                                                    <?php if (! $cat['is_adb']): ?>
                                                        <td class="border border-gray-300 px-2 py-2 text-center text-gray-500">
                                                            <?php echo $basic_rate > 0 ? number_format($basic_rate, 2) : '-' ?>
                                                        </td>

                                                        <td class="border border-gray-300 px-2 py-2 text-center font-bold text-green-700 bg-green-50 annual-cell"
                                                            data-total-rate="<?php echo $total_rate ?>">...</td>

                                                        <td class="border border-gray-300 px-2 py-2 text-center font-medium text-gray-700 sa-cell">...</td>

                                                        <td class="border-r-2 border-gray-400 px-2 py-2 text-center font-medium text-gray-700 q-cell">...</td>

                                                    <?php else: ?>
                                                        <td class="border border-gray-300 px-2 py-2 text-center text-orange-700 bg-orange-50">
                                                            <?php echo $basic_rate > 0 ? number_format($basic_rate, 2) : '-' ?>
                                                        </td>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const faceValueInput = document.getElementById('previewFaceValue');
    const rows = document.querySelectorAll('tbody tr');

    function calculateContributions() {
        const faceValue = parseFloat(faceValueInput.value) || 0;

        rows.forEach(row => {

            const annualCells = row.querySelectorAll('.annual-cell');
            const saCells = row.querySelectorAll('.sa-cell');
            const qCells = row.querySelectorAll('.q-cell');

            annualCells.forEach((annualCell, index) => {
                const totalRate = parseFloat(annualCell.getAttribute('data-total-rate')) || 0;

                if (totalRate > 0) {
                    const annual = totalRate * (faceValue / 1000);

                    const semiAnnual = annual * 0.52;

                    const quarterly = annual * 0.27;

                    annualCell.textContent = formatMoney(annual);

                    if(saCells[index]) saCells[index].textContent = formatMoney(semiAnnual);
                    if(qCells[index]) qCells[index].textContent = formatMoney(quarterly);

                } else {
                    annualCell.textContent = '-';
                    if(saCells[index]) saCells[index].textContent = '-';
                    if(qCells[index]) qCells[index].textContent = '-';
                }
            });
        });
    }

    function formatMoney(amount) {
        return '₱' + amount.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    calculateContributions();
    faceValueInput.addEventListener('input', calculateContributions);
});
</script>

<?php include '../../includes/footer.php'; ?>