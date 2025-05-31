<button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar"
    type="button"
    class="inline-flex items-center p-2 mt-2 ms-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
    <span class="sr-only">Open sidebar</span>
    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
        <path clip-rule="evenodd" fill-rule="evenodd"
            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
        </path>
    </svg>
</button>

<aside id="default-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 border-r border-gray-200 dark:border-gray-700">
        <!-- Logo and Brand -->
        <div class="flex items-center justify-center mb-8 mt-4">
            <img src="https://www.kcfapi.com/wp-content/uploads/2022/10/kclogoshine_rs.jpg" alt="Logo" class="h-20 w-auto">
            
        </div>

        <!-- Navigation Links -->
        <ul class="space-y-2 font-medium">
            <!-- Profile Section -->
            <li class="pb-4 mb-4 border-b border-gray-200 dark:border-gray-700">
                <a href="<?php echo BASE_URL?>views/admin/profile.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-blue-100 rounded-lg dark:bg-blue-900/50">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 ms-3">
                        <p class="text-sm font-semibold">Admin Profile</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">View and edit profile</p>
                    </div>
                </a>
            </li>

            <!-- Main Navigation -->
            <li>
                <a href="<?php echo BASE_URL?>views/admin/admin.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-indigo-100 rounded-lg dark:bg-indigo-900/50">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                    </div>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL?>views/admin/council.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900/50">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <span class="ms-3">Councils</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL?>views/admin/fraternalbenefits.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-green-100 rounded-lg dark:bg-green-900/50">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                        </svg>
                    </div>
                    <span class="ms-3">Fraternal Benefits</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL?>views/admin/users.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-yellow-100 rounded-lg dark:bg-yellow-900/50">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                    </div>
                    <span class="ms-3">Users</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL?>views/admin/forms.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-pink-100 rounded-lg dark:bg-pink-900/50">
                        <svg class="w-5 h-5 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/>
                        </svg>
                    </div>
                    <span class="ms-3">Forms</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL?>views/admin/announcements.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-orange-100 rounded-lg dark:bg-orange-900/50">
                        <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2zm-2 1H8v-6c0-2.48 1.51-4.5 4-4.5s4 2.02 4 4.5v6z"/>
                        </svg>
                    </div>
                    <span class="ms-3">Announcements</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL?>views/admin/application.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-teal-100 rounded-lg dark:bg-teal-900/50">
                        <svg class="w-5 h-5 text-teal-600 dark:text-teal-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                        </svg>
                    </div>
                    <span class="ms-3">Applications</span>
                </a>
            </li>

            <li>
                <a href="<?php echo BASE_URL?>views/admin/transactions.php"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-white/50 dark:hover:bg-gray-700 group transition-all duration-200">
                    <div class="p-2 bg-cyan-100 rounded-lg dark:bg-cyan-900/50">
                        <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.11 0 2-.9 2-2V5c0-1.1-.89-2-2-2zm-2 10h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                        </svg>
                    </div>
                    <span class="ms-3">Transactions</span>
                </a>
            </li>

            <!-- Logout Section -->
            <li class="pt-4 mt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="#" id="logoutBtn"
                    class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-red-50 dark:hover:bg-red-900/50 group transition-all duration-200">
                    <div class="p-2 bg-red-100 rounded-lg dark:bg-red-900/50">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </div>
                    <span class="ms-3 text-red-600 dark:text-red-400 font-medium">Sign Out</span>
                </a>
            </li>
        </ul>
    </div>
</aside>