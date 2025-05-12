<?php
require_once '../../middleware/auth.php';
authorize(['admin', 'fraternal-counselor']);
include '../../includes/config.php';
include '../../includes/db.php';
include '../../includes/header.php';
include '../../models/usersModel.php';
include '../../models/adminModel/councilModel.php';
include '../../models/adminModel/fraternalBenefitsModel.php';


?>
<?php include '../../partials/fcsidebar.php' ?>
<!-- Main Content -->
<main class="flex-1 p-4 md:p-6 overflow-y-auto">
    <!-- Mobile Menu Toggle -->
    <div class="md:hidden flex justify-between items-center mb-4">
        <h1 class="text-xl font-bold text-gray-800">COLUMBOS</h1>
        <button @click="openSidebar = !openSidebar" class="p-2 rounded-full hover:bg-gray-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
            </svg>
        </button>
    </div>

    <!-- Dashboard Section -->
    <div x-show="activeSection === 'dashboard'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">Welcome Back,  <?php echo $_SESSION['firstname'] . ' ' . $_SESSION['lastname'] ?>!</h1>
            <p class="text-gray-600">Here's what's happening with your account today.</p>
        </header>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Total Applicant</h2>
                <p class="text-2xl font-bold text-green-600">12</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Pending applications</h2>
                <p class="text-2xl font-bold text-yellow-600">3</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-md">
                <h2 class="text-lg font-semibold text-gray-700">Allocations</h2>
                <p class="text-2xl font-bold text-blue-600">$500.00</p>
            </div>
        </div>
    </div>

    <!-- Orders Section -->
    <div x-show="activeSection === 'orders'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">Your Orders</h1>
            <p class="text-gray-600">View and manage your recent orders.</p>
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order
                            ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">#ORD12345</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">2023-10-10</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600">Completed</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">$100.00</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">#ORD67890</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">2023-10-05</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-yellow-600">Pending</td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">$50.00</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Profile Section -->
    <div x-show="activeSection === 'profile'" class="space-y-6">
        <header>
            <h1 class="text-2xl font-bold text-gray-800">Your Profile</h1>
            <p class="text-gray-600">Update your personal information and settings.</p>
        </header>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <form class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" id="name"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="John Doe">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input type="email" id="email"
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm"
                        placeholder="john.doe@example.com">
                </div>
                <button type="submit"
                    class="w-full sm:w-auto inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
</main>
<?php
include '../../includes/footer.php';
?>