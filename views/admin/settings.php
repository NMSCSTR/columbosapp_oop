<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';

    // Get MySQL version safely
    $mysql_version = "Not available";
    if (isset($conn) && $conn instanceof mysqli) {
        $mysql_version = mysqli_get_server_info($conn);
    }
?>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-6 sm:ml-64">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Settings</h1>
                <p class="text-gray-600">Manage your application settings and preferences</p>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Settings -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Profile Settings</h2>
                        <form action="../../controllers/admin/updateProfile.php" method="POST" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                    <input type="text" name="firstname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo $_SESSION['firstname'] ?? ''; ?>">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                    <input type="text" name="lastname" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo $_SESSION['lastname'] ?? ''; ?>">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?php echo $_SESSION['email'] ?? ''; ?>">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Profile Changes
                            </button>
                        </form>
                    </div>

                    <!-- Security Settings -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Security Settings</h2>
                        <form action="../../controllers/admin/updatePassword.php" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                                <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" name="new_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" name="confirm_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Update Password
                            </button>
                        </form>
                    </div>

                    <!-- Email Settings -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Email Settings</h2>
                        <form action="../../controllers/admin/updateEmailSettings.php" method="POST" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                                <input type="text" name="smtp_host" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="smtp.gmail.com">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Username</label>
                                    <input type="text" name="smtp_username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Password</label>
                                    <input type="password" name="smtp_password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                                    <input type="number" name="smtp_port" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="587">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Encryption Type</label>
                                    <select name="smtp_encryption" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="tls">TLS</option>
                                        <option value="ssl">SSL</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Email Settings
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Right Sidebar Settings -->
                <div class="space-y-6">
                    <!-- Notification Preferences -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Notification Settings</h2>
                        <form action="../../controllers/admin/updateNotifications.php" method="POST" class="space-y-4">
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="checkbox" name="email_notifications" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Email Notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="application_updates" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">New Application Updates</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="payment_notifications" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">Payment Notifications</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="system_updates" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700">System Updates</span>
                                </label>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                Save Notification Preferences
                            </button>
                        </form>
                    </div>

                    <!-- Backup Settings -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Backup Settings</h2>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                <div>
                                    <h3 class="font-medium text-gray-800">Auto Backup</h3>
                                    <p class="text-sm text-gray-500">Daily at 03:00 AM</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                            <button onclick="manualBackup()" class="w-full px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Create Manual Backup
                            </button>
                            <div class="text-sm text-gray-500">
                                Last backup: Today, 03:00 AM
                            </div>
                        </div>
                    </div>

                    <!-- System Information -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">System Information</h2>
                        <div class="space-y-3">
                            <div>
                                <label class="text-sm text-gray-500">PHP Version</label>
                                <p class="font-medium text-gray-800"><?php echo phpversion(); ?></p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">MySQL Version</label>
                                <p class="font-medium text-gray-800"><?php echo htmlspecialchars($mysql_version); ?></p>
                            </div>
                            <div>
                                <label class="text-sm text-gray-500">Server Software</label>
                                <p class="font-medium text-gray-800"><?php echo htmlspecialchars($_SERVER['SERVER_SOFTWARE']); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function manualBackup() {
    if (confirm('Are you sure you want to create a manual backup?')) {
        // Add your backup logic here
        alert('Backup started. You will be notified when it\'s complete.');
    }
}
</script>

<?php
include '../../includes/footer.php';
?> 