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
        [
            "title" => "All Users",
            "value" => array_sum($roleCounts),
            "icon" => "users",
            "color" => "blue",
            "increase" => "+12.5%",
            "period" => "vs last month"
        ],
        [
            "title" => "Total Contributions",
            "value" => "₱" . number_format($totals['total_contribution'] ?? 0, 2),
            "icon" => "coins",
            "color" => "green",
            "increase" => "+8.2%",
            "period" => "vs last month"
        ],
        [
            "title" => "Active Members",
            "value" => $roleCounts['member'] ?? 0,
            "icon" => "user-check",
            "color" => "purple",
            "increase" => "+5.7%",
            "period" => "vs last month"
        ],
        [
            "title" => "Total Savings Fund",
            "value" => "₱" . number_format($totals['savings_fund'] ?? 0, 2),
            "icon" => "piggy-bank",
            "color" => "yellow",
            "increase" => "+15.3%",
            "period" => "vs last month"
        ]
    ];

    $colors = [
        "blue" => ["bg" => "bg-blue-50", "text" => "text-blue-600", "icon" => "text-blue-500"],
        "green" => ["bg" => "bg-green-50", "text" => "text-green-600", "icon" => "text-green-500"],
        "purple" => ["bg" => "bg-purple-50", "text" => "text-purple-600", "icon" => "text-purple-500"],
        "yellow" => ["bg" => "bg-yellow-50", "text" => "text-yellow-600", "icon" => "text-yellow-500"]
    ];
?>

<!-- Stats Cards -->
<?php foreach ($stats as $index => $s): ?>
    <div class="stat-card bg-white rounded-xl p-6 shadow-sm" style="animation-delay: <?= $index * 0.1 ?>s">
        <div class="flex items-center justify-between mb-4">
            <div class="<?= $colors[$s["color"]]["bg"] ?> p-3 rounded-lg">
                <i class="fas fa-<?= $s["icon"] ?> <?= $colors[$s["color"]]["icon"] ?> text-lg"></i>
            </div>
            <span class="<?= $colors[$s["color"]]["text"] ?> text-sm font-medium"><?= $s["increase"] ?></span>
        </div>
        <h3 class="text-sm text-gray-500 font-medium mb-1"><?= $s["title"] ?></h3>
        <div class="flex items-center justify-between">
            <p class="text-2xl font-bold stat-value"><?= $s["value"] ?></p>
            <span class="text-xs text-gray-400"><?= $s["period"] ?></span>
        </div>
    </div>
<?php endforeach; ?>

<!-- Charts Section -->
<div class="col-span-full grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- User Growth Chart -->
    <div class="chart-card p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">User Growth</h3>
            <div class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm bg-blue-50 text-blue-600 rounded-lg">Monthly</button>
                <button class="px-3 py-1 text-sm text-gray-500 hover:bg-gray-50 rounded-lg">Weekly</button>
            </div>
        </div>
        <div style="height: 300px;">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <!-- Role Distribution Chart -->
    <div class="chart-card p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-800">User Role Distribution</h3>
            <button class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-download mr-1"></i> Export
            </button>
        </div>
        <div style="height: 300px;">
            <canvas id="roleChart"></canvas>
        </div>
    </div>
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js Defaults
Chart.defaults.font.family = "'Inter', 'system-ui', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.plugins.legend.position = 'bottom';

// Growth Chart
const growthCtx = document.getElementById('growthChart').getContext('2d');
new Chart(growthCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($allMonths) ?>,
        datasets: [{
            label: 'New Users',
            data: <?php echo json_encode($growthCounts) ?>,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#FFFFFF',
            pointBorderColor: '#3B82F6',
            pointBorderWidth: 2,
            pointRadius: 4,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        },
        plugins: {
            legend: {
                display: false
            }
        }
    }
});

// Role Distribution Chart
const roleCtx = document.getElementById('roleChart').getContext('2d');
new Chart(roleCtx, {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode(array_map('ucfirst', array_keys($roleCounts))) ?>,
        datasets: [{
            data: <?php echo json_encode(array_values($roleCounts)) ?>,
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',  // Blue
                'rgba(16, 185, 129, 0.8)',  // Green
                'rgba(139, 92, 246, 0.8)',  // Purple
                'rgba(245, 158, 11, 0.8)'   // Yellow
            ],
            borderColor: '#FFFFFF',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 20,
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            }
        },
        cutout: '75%'
    }
});
</script>
