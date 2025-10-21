<?php
    $conn = mysqli_connect('localhost', 'root', '', 'columbos');
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../models/adminModel/userModel.php';
?>

<!-- Add CSS for animations and dashboard styling -->
<style>
.dashboard-card {
    transition: all 0.3s ease;
}

.dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.stat-card {
    animation: fadeInUp 0.5s ease-out forwards;
    opacity: 0;
}

.chart-container {
    animation: fadeIn 0.8s ease-out forwards;
    opacity: 0;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.quick-action-btn {
    transition: all 0.2s ease;
}

.quick-action-btn:hover {
    transform: translateY(-2px);
}

.stat-value {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.chart-card {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}
</style>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-6 sm:ml-64">
            <!-- Welcome Section -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Welcome back, <?php echo ucfirst($_SESSION['role'])?></h1>
                        <p class="text-gray-600 mt-1"><?php echo date('l, F j, Y')?></p>
                    </div>
                    <!-- <div class="flex space-x-3">
                        <button onclick="window.location.href='new-application.php'" class="quick-action-btn flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Application
                        </button>
                        <button onclick="window.location.href='reports.php'" class="quick-action-btn flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Generate Report
                        </button>
                    </div> -->
                </div>
            </div>

            <!-- Content Section -->
            <div class="space-y-6">
                <!-- Quick Stats Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <?php include '../../partials/content.php'?>
                </div>

                <!-- Recent Activity Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Applications -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold text-gray-800">Recent Applications</h2>
                            <a href="applications.php" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
                        </div>
                        <div class="space-y-4">
                            <?php 
                            if(isset($applicants) && is_array($applicants)) {
                                $recentApplicants = array_slice($applicants, 0, 5);
                                foreach($recentApplicants as $applicant): 
                            ?>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800"><?= $applicant['applicant_name'] ?></h3>
                                    <p class="text-sm text-gray-500"><?= $applicant['plan_name'] ?></p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium rounded-full <?= 
                                    $applicant['application_status'] === 'Approved' ? 'bg-green-100 text-green-800' : 
                                    ($applicant['application_status'] === 'Rejected' ? 'bg-red-100 text-red-800' : 
                                    'bg-yellow-100 text-yellow-800') 
                                ?>">
                                    <?= $applicant['application_status'] ?>
                                </span>
                            </div>
                            <?php 
                                endforeach;
                            } 
                            ?>
                        </div>
                    </div>

                    <!-- Weather and Time -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Time</h2>
                        <div class="space-y-6">
                            <!-- Date and Time -->
                            <div class="bg-blue-50 rounded-lg p-4">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium text-blue-600">Local Time</span>
                                </div>
                                <div class="text-2xl font-bold text-gray-800 mb-1" id="current-time">00:00:00</div>
                                <div class="text-sm text-gray-600" id="current-date">Loading...</div>
                            </div>


                    <!-- Quick Actions -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <button onclick="window.location.href='users.php'" class="quick-action-btn flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <svg class="w-6 h-6 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Manage Users</span>
                            </button>
                            <button onclick="window.location.href='council.php'" class="quick-action-btn flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <svg class="w-6 h-6 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Manage Councils</span>
                            </button>
                            <button onclick="window.location.href='announcements.php'" class="quick-action-btn flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <svg class="w-6 h-6 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Announcements</span>
                            </button>
                            <button onclick="window.location.href='settings.php'" class="quick-action-btn flex flex-col items-center justify-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100">
                                <svg class="w-6 h-6 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Settings</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
// Function to update time
function updateDateTime() {
    const now = new Date();
    const timeElement = document.getElementById('current-time');
    const dateElement = document.getElementById('current-date');
    
    // Update time
    timeElement.textContent = now.toLocaleTimeString('en-US', { 
        hour12: false,
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
    
    // Update date
    dateElement.textContent = now.toLocaleDateString('en-US', { 
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

// Update time every second
setInterval(updateDateTime, 1000);
updateDateTime(); // Initial call

// Fetch weather data
async function fetchWeather() {
    try {
        // Get user's location
        navigator.geolocation.getCurrentPosition(async (position) => {
            const { latitude, longitude } = position.coords;
            
            // Replace 'YOUR_API_KEY' with your actual OpenWeatherMap API key
            const response = await fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${latitude}&lon=${longitude}&units=metric&appid=YOUR_API_KEY`);
            const data = await response.json();
            
            // Update weather information
            document.getElementById('weather-location').textContent = data.name;
            document.getElementById('weather-description').textContent = data.weather[0].description;
            document.getElementById('weather-temp').textContent = `${Math.round(data.main.temp)}°C`;
            document.getElementById('weather-humidity').textContent = `${data.main.humidity}%`;
            document.getElementById('weather-wind').textContent = `${Math.round(data.wind.speed)} km/h`;
            document.getElementById('weather-feels-like').textContent = `${Math.round(data.main.feels_like)}°C`;
        }, (error) => {
            console.error('Error getting location:', error);
            document.getElementById('weather-location').textContent = 'Location access denied';
        });
    } catch (error) {
        console.error('Error fetching weather:', error);
        document.getElementById('weather-location').textContent = 'Weather data unavailable';
    }
}

// Fetch weather data initially and every 30 minutes
fetchWeather();
setInterval(fetchWeather, 30 * 60 * 1000);

// Export Menu Toggle
function toggleExportMenu() {
    const menu = document.getElementById('exportMenu');
    menu.classList.toggle('hidden');

    // Close menu when clicking outside
    document.addEventListener('click', function closeMenu(e) {
        if (!e.target.closest('#exportMenu') && !e.target.closest('button')) {
            menu.classList.add('hidden');
            document.removeEventListener('click', closeMenu);
        }
    });
}

// Export to CSV
function exportToCSV() {
    const roleChart = Chart.getChart('roleChart');
    const labels = roleChart.data.labels;
    const data = roleChart.data.datasets[0].data;

    let csvContent = "Role,Count\n";
    labels.forEach((label, index) => {
        csvContent += `${label},${data[index]}\n`;
    });

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    
    link.setAttribute('href', url);
    link.setAttribute('download', 'role_distribution.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    // Close export menu
    document.getElementById('exportMenu').classList.add('hidden');
}

// Export to PDF
function exportToPDF() {
    const { jsPDF } = window.jspdf;
    const roleChart = Chart.getChart('roleChart');
    const canvas = document.getElementById('roleChart');
    
    // Create PDF
    const pdf = new jsPDF('landscape');
    
    // Add title
    pdf.setFontSize(16);
    pdf.text('User Role Distribution', 15, 15);
    
    // Add chart image
    const chartImage = canvas.toDataURL('image/png');
    pdf.addImage(chartImage, 'PNG', 15, 30, 260, 150);
    
    // Add table data
    pdf.setFontSize(12);
    let yPos = 200;
    
    // Table headers
    pdf.setFont(undefined, 'bold');
    pdf.text('Role', 15, yPos);
    pdf.text('Count', 80, yPos);
    
    // Table data
    pdf.setFont(undefined, 'normal');
    roleChart.data.labels.forEach((label, index) => {
        yPos += 10;
        pdf.text(label, 15, yPos);
        pdf.text(roleChart.data.datasets[0].data[index].toString(), 80, yPos);
    });
    
    // Add timestamp
    const timestamp = new Date().toLocaleString();
    pdf.setFontSize(10);
    pdf.setTextColor(128);
    pdf.text(`Generated on: ${timestamp}`, 15, pdf.internal.pageSize.height - 10);
    
    // Save PDF
    pdf.save('role_distribution.pdf');
    
    // Close export menu
    document.getElementById('exportMenu').classList.add('hidden');
}
</script>

<?php
include '../../includes/footer.php';
?>