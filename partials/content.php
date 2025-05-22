<?php
    include '../../includes/db.php';
    include '../../models/memberModel/memberApplicationModel.php';
    $userModel        = new UserModel($conn);
    $applicationModel = new MemberApplicationModel($conn);
    $applicants       = $applicationModel->getAllApplicants();
    $roleCounts       = $userModel->countUsersGroupedByRole();
    $growthData       = $userModel->getUserGrowthPerMonth();
    $totals           = $applicationModel->calculateTotalAllocationsForAllApplicants();

    $allMonths    = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    $growthCounts = [];

    foreach ($allMonths as $month) {
        $growthCounts[] = $growthData[$month] ?? 0;
    }

    $stats = [
        ["title" => "All Users", "value" => array_sum($roleCounts), "icon" => "users"],
        ["title" => "Members", "value" => $roleCounts['member'] ?? 0, "icon" => "user"],
        ["title" => "Family Members", "value" => $roleCounts['family-member'] ?? 0, "icon" => "users"],
        ["title" => "Unit Managers", "value" => $roleCounts['unit-manager'] ?? 0, "icon" => "user-tie"],

        // New stats cards for totals
        ["title" => "Total Contributions", "value" => "₱" . number_format($totals['total_contribution'] ?? 0, 2), "icon" => "coins"],
        ["title" => "Total Insurance Cost", "value" => "₱" . number_format($totals['insurance_cost'] ?? 0, 2), "icon" => "shield-alt"],
        ["title" => "Total Admin Fee", "value" => "₱" . number_format($totals['admin_fee'] ?? 0, 2), "icon" => "cogs"],
        ["title" => "Total Savings Fund", "value" => "₱" . number_format($totals['savings_fund'] ?? 0, 2), "icon" => "piggy-bank"],
    ];
?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-4">
    <?php foreach ($stats as $s): ?>
        <div class="bg-yellow-600 p-6 rounded-2xl shadow flex items-center space-x-4">
            <div class="p-3 bg-blue-100 rounded-full">
                <i class="fas fa-<?php echo $s["icon"] ?> text-blue-500"></i>
            </div>
            <div>
                <h3 class="text-sm text-white"><?php echo $s["title"] ?></h3>
                <p class="text-xl font-bold text-gray-900"><?php echo $s["value"] ?></p>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- User Growth Chart -->
    <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-lg font-semibold mb-4">User Growth</h3>
        <canvas id="growthChart" height="100"></canvas>
    </div>

    <!-- Role Distribution Chart -->
    <div class="bg-white p-6 rounded-2xl shadow">
        <h3 class="text-lg font-semibold mb-4">User Role Distribution</h3>
        <canvas id="roleChart" height="100"></canvas>
    </div>
</div>


<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const growthCtx = document.getElementById('growthChart').getContext('2d');
new Chart(growthCtx, {
    type: 'bar',
    data: {
        labels:                <?php echo json_encode($allMonths) ?>,
        datasets: [{
            label: 'New Users',
            data:                  <?php echo json_encode($growthCounts) ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.7)'
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Role Chart Dynamic Data
const roleCtx = document.getElementById('roleChart').getContext('2d');
new Chart(roleCtx, {
    type: 'doughnut',
    data: {
        labels:                               <?php echo json_encode(array_keys($roleCounts)) ?>,
        datasets: [{
            label: 'Roles',
            data:                                   <?php echo json_encode(array_values($roleCounts)) ?>,
            backgroundColor: ['#3B82F6', '#10B981', '#FBBF24', '#EF4444', '#8B5CF6', '#F472B6', '#6EE7B7']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'right'
            }
        }
    }
});
</script>
