<?php
// Add any PHP logic here if needed
?>

<div x-data="{ openSidebar: false, activeSection: 'dashboard' }" class="flex flex-col md:flex-row min-h-screen">
    <!-- Sidebar -->
    <aside class="bg-white shadow-xl transition-all duration-300 ease-in-out fixed md:static inset-y-0 left-0 z-50 w-20 md:w-64 border-r border-gray-200"
        :class="{ 'w-64': openSidebar, 'hidden md:block': !openSidebar }">
        
        <!-- Logo Section -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex items-center justify-center">
                <img src="https://www.kcfapi.com/wp-content/uploads/2022/10/kclogoshine_rs.jpg" alt="Logo" class="h-20 w-auto">
                <button @click="openSidebar = !openSidebar" 
                    class="md:hidden absolute right-4 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="p-4">
            <div class="space-y-1">
                <!-- Dashboard -->
                <a href="#" @click.prevent="activeSection = 'dashboard'; openSidebar = false"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="{ 'bg-green-50 text-green-600': activeSection === 'dashboard', 'hover:bg-gray-50': activeSection !== 'dashboard' }">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5 transition-colors duration-200"
                        :class="{ 'text-green-600': activeSection === 'dashboard', 'text-gray-500 group-hover:text-gray-600': activeSection !== 'dashboard' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0l-2-2m2 2V4a1 1 0 00-1-1h-3a1 1 0 00-1 1z" />
                    </svg>
                    <span class="ml-3 font-medium" :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">Dashboard</span>
                </a>

                <!-- Applications -->
                <a href="#" @click.prevent="activeSection = 'orders'; openSidebar = false"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="{ 'bg-green-50 text-green-600': activeSection === 'orders', 'hover:bg-gray-50': activeSection !== 'orders' }">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5 transition-colors duration-200"
                        :class="{ 'text-green-600': activeSection === 'orders', 'text-gray-500 group-hover:text-gray-600': activeSection !== 'orders' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                    <span class="ml-3 font-medium" :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">Applications</span>
                </a>

                <!-- Forms -->
                <a href="#" @click.prevent="activeSection = 'forms'; openSidebar = false"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="{ 'bg-green-50 text-green-600': activeSection === 'forms', 'hover:bg-gray-50': activeSection !== 'forms' }">
                    <svg class="h-5 w-5 transition-colors duration-200"
                        :class="{ 'text-green-600': activeSection === 'forms', 'text-gray-500 group-hover:text-gray-600': activeSection !== 'forms' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8v8a5 5 0 1 0 10 0V6.5a3.5 3.5 0 1 0-7 0V15a2 2 0 0 0 4 0V8" />
                    </svg>
                    <span class="ml-3 font-medium" :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">Forms</span>
                </a>

                <!-- Announcements -->
                <a href="#" @click.prevent="activeSection = 'announcement'; openSidebar = false"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="{ 'bg-green-50 text-green-600': activeSection === 'announcement', 'hover:bg-gray-50': activeSection !== 'announcement' }">
                    <svg class="h-5 w-5 transition-colors duration-200"
                        :class="{ 'text-green-600': activeSection === 'announcement', 'text-gray-500 group-hover:text-gray-600': activeSection !== 'announcement' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="ml-3 font-medium" :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">Announcements</span>
                </a>

                <!-- Councils -->
                <a href="#" @click.prevent="activeSection = 'council'; openSidebar = false"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="{ 'bg-green-50 text-green-600': activeSection === 'council', 'hover:bg-gray-50': activeSection !== 'council' }">
                    <svg class="h-5 w-5 transition-colors duration-200"
                        :class="{ 'text-green-600': activeSection === 'council', 'text-gray-500 group-hover:text-gray-600': activeSection !== 'council' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span class="ml-3 font-medium" :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">Councils</span>
                </a>

                <!-- Unit Managers Quota -->
                <a href="#" @click.prevent="activeSection = 'unitmanagers'; openSidebar = false"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="{ 'bg-green-50 text-green-600': activeSection === 'unitmanagers', 'hover:bg-gray-50': activeSection !== 'unitmanagers' }">
                    <svg class="h-5 w-5 transition-colors duration-200"
                        :class="{ 'text-green-600': activeSection === 'unitmanagers', 'text-gray-500 group-hover:text-gray-600': activeSection !== 'unitmanagers' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span class="ml-3 font-medium" :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">Unit Managers</span>
                </a>

                <!-- Fraternal Benefits -->
                <a href="#" @click.prevent="activeSection = 'fraternalbenefits'; openSidebar = false"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group"
                    :class="{ 'bg-green-50 text-green-600': activeSection === 'fraternalbenefits', 'hover:bg-gray-50': activeSection !== 'fraternalbenefits' }">
                    <svg class="h-5 w-5 transition-colors duration-200"
                        :class="{ 'text-green-600': activeSection === 'fraternalbenefits', 'text-gray-500 group-hover:text-gray-600': activeSection !== 'fraternalbenefits' }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="ml-3 font-medium" :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">Fraternal Benefits</span>
                </a>
            </div>

            <!-- Logout Section -->
            <div class="mt-8 pt-4 border-t border-gray-100">
                <a href="#" id="logoutBtn"
                    class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 group hover:bg-red-50">
                    <svg class="h-5 w-5 text-red-500 group-hover:text-red-600 transition-colors duration-200"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span class="ml-3 font-medium text-red-500 group-hover:text-red-600" 
                        :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }">
                        Logout
                    </span>
                </a>
            </div>
        </nav>
    </aside>