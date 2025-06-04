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

<style>
    .sticky-sidebar {
        position: sticky;
        top: 0;
        height: 100vh;
    }
    .plan-card {
        transition: all 0.3s ease;
        border: 1px solid rgba(229, 231, 235, 1);
    }
    .plan-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    .stat-value {
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .benefit-list li {
        position: relative;
        padding-left: 1.5rem;
        margin-bottom: 0.75rem;
    }
    .benefit-list li::before {
        content: "•";
        position: absolute;
        left: 0;
        color: #4F46E5;
        font-weight: bold;
    }
    .gradient-border {
        position: relative;
        border-radius: 0.75rem;
        background: linear-gradient(white, white) padding-box,
                    linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%) border-box;
        border: 2px solid transparent;
    }
    .nav-item {
        transition: all 0.3s ease;
    }
    .nav-item:hover {
        background-color: rgba(79, 70, 229, 0.1);
    }
    .nav-item.active {
        background-color: #4F46E5;
        color: white;
    }
</style>

<div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar Navigation -->
    <div class="hidden lg:flex lg:flex-col w-64 bg-white border-r border-gray-200">
        <div class="sticky-sidebar p-6">
            <div class="mb-8">
                <h2 class="text-xl font-bold text-gray-800">Plan Details</h2>
                <p class="text-sm text-gray-500 mt-1">View complete information</p>
            </div>
            
            <nav class="space-y-2">
                <a href="#overview" class="nav-item flex items-center px-4 py-3 text-sm font-medium rounded-lg active">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Overview
                </a>
                <a href="#details" class="nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Plan Details
                </a>
                <a href="#benefits" class="nav-item flex items-center px-4 py-3 text-sm font-medium text-gray-700 rounded-lg">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    Benefits
                </a>
            </nav>

            <div class="mt-auto pt-6">
                <a href="unitmanager.php" class="flex items-center px-4 py-3 text-sm font-medium text-gray-700 hover:text-gray-900 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Plans
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="flex-1 min-w-0">
        <!-- Header Section with Image -->
        <div class="relative">
            <!-- Large Image Section -->
            <div class="h-[500px] w-full relative overflow-hidden">
                <img src="<?php echo BASE_URL . $benefit['image']; ?>" 
                     alt="Plan Image" 
                     class="w-full h-full object-cover"
                     onerror="this.src='../../assets/img/default-plan.jpg'">
                <div class="absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-black opacity-90"></div>
            </div>

            <!-- Floating Content Card -->
            <div class="absolute bottom-0 left-0 right-0">
                <div class="container mx-auto px-8 pb-8">
                    <div class="bg-white/90 backdrop-blur-sm p-8 rounded-t-3xl">
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">
                            <?php echo htmlspecialchars($benefit['name']); ?>
                        </h1>
                        <p class="text-xl text-gray-700">
                            <?php echo nl2br(htmlspecialchars($benefit['type'])); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div class="px-8 py-8 max-w-7xl mx-auto">
            <!-- Overview Section -->
            <section id="overview" class="mb-12">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="plan-card bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-indigo-100 rounded-lg">
                                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500">Contribution Period</h3>
                        <p class="text-2xl font-bold stat-value mt-1"><?php echo htmlspecialchars($benefit['contribution_period']); ?> years</p>
                    </div>

                    <div class="plan-card bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500">Face Value</h3>
                        <p class="text-2xl font-bold stat-value mt-1">₱<?php echo number_format($benefit['face_value'],2); ?></p>
                    </div>

                    <div class="plan-card bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500">Years to Maturity</h3>
                        <p class="text-2xl font-bold stat-value mt-1"><?php echo $benefit['years_to_maturity']; ?> years</p>
                    </div>

                    <div class="plan-card bg-white rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-yellow-100 rounded-lg">
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-sm font-medium text-gray-500">Protection Period</h3>
                        <p class="text-2xl font-bold stat-value mt-1"><?php echo $benefit['years_of_protection']; ?> years</p>
                    </div>
                </div>
            </section>

            <!-- Details Section -->
            <section id="details" class="mb-12">
                <div class="gradient-border bg-white p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">About the Plan</h2>
                    <div class="prose max-w-none">
                        <p class="text-gray-600 leading-relaxed">
                            <?php echo nl2br(htmlspecialchars($benefit['about'])); ?>
                        </p>
                    </div>
                </div>
            </section>

            <!-- Benefits Section -->
            <section id="benefits" class="mb-12">
                <div class="gradient-border bg-white p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Key Benefits</h2>
                    <div class="prose max-w-none">
                        <div class="text-gray-600 leading-relaxed benefit-list">
                            <?php 
                                $benefits = explode("\n", $benefit['benefits']);
                                echo "<ul>";
                                foreach($benefits as $item) {
                                    if(trim($item) !== '') {
                                        echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                                    }
                                }
                                echo "</ul>";
                            ?>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Mobile Back Button -->
            <div class="lg:hidden flex justify-center mt-8">
                <a href="unitmanager.php" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Plans
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle smooth scrolling for navigation
    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const section = document.querySelector(this.getAttribute('href'));
            section.scrollIntoView({ behavior: 'smooth' });
            
            // Update active state
            document.querySelectorAll('.nav-item').forEach(item => item.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Handle scroll spy
    const sections = document.querySelectorAll('section');
    const navItems = document.querySelectorAll('.nav-item');

    window.addEventListener('scroll', () => {
        let current = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            if (window.pageYOffset >= sectionTop - 100) {
                current = section.getAttribute('id');
            }
        });

        navItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href').substring(1) === current) {
                item.classList.add('active');
            }
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>