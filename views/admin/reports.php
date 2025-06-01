<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/councilModel.php';
    include '../../models/adminModel/userModel.php';
    include '../../models/memberModel/memberApplicationModel.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';

    // Initialize models
    $councilModel = new CouncilModel($conn);
    $userModel = new UserModel($conn);
    $applicationModel = new MemberApplicationModel($conn);
    $fraternalBenefitsModel = new fraternalBenefitsModel($conn);

    // Get data for reports
    $councils = $councilModel->getAllCouncil();
    $applications = $applicationModel->getAllApplicants();
    $fraternals = $fraternalBenefitsModel->getAllFraternalBenefits();

    // Calculate statistics
    $totalApplications = count($applications ?? []);
    $pendingApplications = 0;
    $approvedApplications = 0;
    $rejectedApplications = 0;

    if ($applications) {
        foreach ($applications as $app) {
            switch ($app['application_status']) {
                case 'Pending':
                    $pendingApplications++;
                    break;
                case 'Approved':
                    $approvedApplications++;
                    break;
                case 'Rejected':
                    $rejectedApplications++;
                    break;
            }
        }
    }
?>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-6 sm:ml-64">
            <!-- Header -->
            <div class="mb-8">
                <nav class="flex mb-5" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                        <li class="inline-flex items-center">
                            <a href="admin.php" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                                </svg>
                                Dashboard
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Reports</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-2xl font-bold text-gray-800">Reports Dashboard</h1>
                <p class="text-gray-600">Generate and export various reports</p>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Total Applications</h3>
                        <span class="text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $totalApplications; ?></p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Pending</h3>
                        <span class="text-yellow-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $pendingApplications; ?></p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Approved</h3>
                        <span class="text-green-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $approvedApplications; ?></p>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Rejected</h3>
                        <span class="text-red-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-3xl font-bold text-gray-900"><?php echo $rejectedApplications; ?></p>
                </div>
            </div>

            <!-- Report Types -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Applications Report -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Applications Report</h2>
                        <div class="flex space-x-2">
                            <button onclick="exportApplicationsCSV()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Export CSV
                            </button>
                            <button onclick="exportApplicationsPDF()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Export PDF
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Type</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($applications): ?>
                                    <?php foreach (array_slice($applications, 0, 5) as $application): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($application['applicant_name']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($application['plan_type']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    <?php
                                                        switch($application['application_status']) {
                                                            case 'Approved':
                                                                echo 'bg-green-100 text-green-800';
                                                                break;
                                                            case 'Rejected':
                                                                echo 'bg-red-100 text-red-800';
                                                                break;
                                                            default:
                                                                echo 'bg-yellow-100 text-yellow-800';
                                                        }
                                                    ?>">
                                                    <?php echo htmlspecialchars($application['application_status']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Council Performance -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-800">Council Performance</h2>
                        <div class="flex space-x-2">
                            <button onclick="exportCouncilCSV()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Export CSV
                            </button>
                            <button onclick="exportCouncilPDF()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                Export PDF
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Council</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Applications</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if ($councils): ?>
                                    <?php foreach ($councils as $council): 
                                        $councilApps = array_filter($applications ?? [], function($app) use ($council) {
                                            return $app['council_id'] == $council['council_id'];
                                        });
                                        $totalCouncilApps = count($councilApps);
                                        $approvedCouncilApps = count(array_filter($councilApps, function($app) {
                                            return $app['application_status'] == 'Approved';
                                        }));
                                        $successRate = $totalCouncilApps > 0 ? round(($approvedCouncilApps / $totalCouncilApps) * 100) : 0;
                                    ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($council['council_name']); ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $totalCouncilApps; ?></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $successRate; ?>%</td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Date Range Report Form -->
            <div class="mt-6">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-6">Generate Custom Report</h2>
                    <form id="customReportForm" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" name="start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="date" name="end_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                                <select name="report_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="applications">Applications</option>
                                    <option value="councils">Council Performance</option>
                                    <option value="plans">Plan Distribution</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-2">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Generate Report
                            </button>
                            <button type="button" onclick="exportCustomReport()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                                Export Report
                            </button>
                        </div>
                    </form>
                    
                    <!-- Custom Report Results -->
                    <div id="customReportResults" class="mt-6 hidden">
                        <div class="bg-white rounded-xl shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Report Results</h3>
                            <div id="reportContent" class="overflow-x-auto"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
// Export Applications to CSV
function exportApplicationsCSV() {
    const applications = <?php echo json_encode($applications ?? []); ?>;
    let csvContent = "Name,Plan Type,Status\n";
    
    applications.forEach(app => {
        csvContent += `${app.applicant_name},${app.plan_type},${app.application_status}\n`;
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'applications_report.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Export Applications to PDF
function exportApplicationsPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const applications = <?php echo json_encode($applications ?? []); ?>;
    
    // Add title
    doc.setFontSize(16);
    doc.text('Applications Report', 15, 15);
    
    // Add timestamp
    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 15, 25);
    
    // Add table headers
    doc.setFontSize(12);
    doc.text('Name', 15, 35);
    doc.text('Plan Type', 80, 35);
    doc.text('Status', 140, 35);
    
    // Add table data
    let yPos = 45;
    applications.forEach(app => {
        if (yPos > 270) {
            doc.addPage();
            yPos = 20;
        }
        doc.text(app.applicant_name.substring(0, 30), 15, yPos);
        doc.text(app.plan_type.substring(0, 25), 80, yPos);
        doc.text(app.application_status, 140, yPos);
        yPos += 10;
    });
    
    doc.save('applications_report.pdf');
}

// Export Council Performance to CSV
function exportCouncilCSV() {
    const councils = <?php echo json_encode($councils ?? []); ?>;
    const applications = <?php echo json_encode($applications ?? []); ?>;
    let csvContent = "Council Name,Total Applications,Success Rate\n";
    
    councils.forEach(council => {
        const councilApps = applications.filter(app => {
            return app.council_id && app.council_id == council.council_id;
        });
        const totalApps = councilApps.length;
        const approvedApps = councilApps.filter(app => app.application_status == 'Approved').length;
        const successRate = totalApps > 0 ? Math.round((approvedApps / totalApps) * 100) : 0;
        
        csvContent += `${council.council_name},${totalApps},${successRate}%\n`;
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'council_performance.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Export Council Performance to PDF
function exportCouncilPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    const councils = <?php echo json_encode($councils ?? []); ?>;
    const applications = <?php echo json_encode($applications ?? []); ?>;
    
    // Add title
    doc.setFontSize(16);
    doc.text('Council Performance Report', 15, 15);
    
    // Add timestamp
    doc.setFontSize(10);
    doc.text(`Generated on: ${new Date().toLocaleString()}`, 15, 25);
    
    // Add table headers
    doc.setFontSize(12);
    doc.text('Council Name', 15, 35);
    doc.text('Total Applications', 90, 35);
    doc.text('Success Rate', 140, 35);
    
    // Add table data
    let yPos = 45;
    councils.forEach(council => {
        if (yPos > 270) {
            doc.addPage();
            yPos = 20;
        }
        
        const councilApps = applications.filter(app => {
            return app.council_id && app.council_id == council.council_id;
        });
        const totalApps = councilApps.length;
        const approvedApps = councilApps.filter(app => app.application_status == 'Approved').length;
        const successRate = totalApps > 0 ? Math.round((approvedApps / totalApps) * 100) : 0;
        
        doc.text(council.council_name.substring(0, 35), 15, yPos);
        doc.text(totalApps.toString(), 90, yPos);
        doc.text(`${successRate}%`, 140, yPos);
        yPos += 10;
    });
    
    doc.save('council_performance.pdf');
}

// Store the current custom report data
let currentCustomReport = null;

// Handle custom report form submission
document.getElementById('customReportForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    try {
        const response = await fetch('../../controllers/admin/generateReport.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            currentCustomReport = data;
            displayCustomReport(data);
        } else {
            alert('Error generating report: ' + data.error);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('An error occurred while generating the report');
    }
});

// Display custom report results
function displayCustomReport(data) {
    const resultsDiv = document.getElementById('customReportResults');
    const contentDiv = document.getElementById('reportContent');
    resultsDiv.classList.remove('hidden');
    
    let html = '<table class="min-w-full divide-y divide-gray-200">';
    
    switch (data.type) {
        case 'applications':
            html += `
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Type</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
            `;
            
            data.data.forEach(app => {
                html += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${app.applicant_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${app.plan_type}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${
                                app.application_status === 'Approved' ? 'bg-green-100 text-green-800' :
                                app.application_status === 'Rejected' ? 'bg-red-100 text-red-800' :
                                'bg-yellow-100 text-yellow-800'
                            }">
                                ${app.application_status}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(app.created_at).toLocaleDateString()}</td>
                    </tr>
                `;
            });
            break;
            
        case 'councils':
            html += `
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Council Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Applications</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved Applications</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
            `;
            
            data.data.forEach(council => {
                html += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${council.council_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${council.total_applications}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${council.approved_applications}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${council.success_rate}%</td>
                    </tr>
                `;
            });
            break;
            
        case 'plans':
            html += `
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Type</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan Name</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Applications</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Success Rate</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
            `;
            
            data.data.forEach(plan => {
                html += `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${plan.type}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${plan.name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${plan.total_applications}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${plan.success_rate}%</td>
                    </tr>
                `;
            });
            break;
    }
    
    html += '</tbody></table>';
    contentDiv.innerHTML = html;
}

// Export custom report
function exportCustomReport() {
    if (!currentCustomReport) {
        alert('Please generate a report first');
        return;
    }
    
    const reportType = currentCustomReport.type;
    const data = currentCustomReport.data;
    const formData = new FormData(document.getElementById('customReportForm'));
    const startDate = formData.get('start_date');
    const endDate = formData.get('end_date');
    
    // Export as CSV
    let csvContent = '';
    switch (reportType) {
        case 'applications':
            csvContent = "Name,Plan Type,Status,Date\n";
            data.forEach(app => {
                csvContent += `${app.applicant_name},${app.plan_type},${app.application_status},${new Date(app.created_at).toLocaleDateString()}\n`;
            });
            break;
            
        case 'councils':
            csvContent = "Council Name,Total Applications,Approved Applications,Success Rate\n";
            data.forEach(council => {
                csvContent += `${council.council_name},${council.total_applications},${council.approved_applications},${council.success_rate}%\n`;
            });
            break;
            
        case 'plans':
            csvContent = "Plan Type,Plan Name,Total Applications,Success Rate\n";
            data.forEach(plan => {
                csvContent += `${plan.type},${plan.name},${plan.total_applications},${plan.success_rate}%\n`;
            });
            break;
    }
    
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', `${reportType}_report_${startDate}_to_${endDate}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<?php include '../../includes/footer.php'; ?> 