<div x-data="{ openSidebar: false, activeSection: 'dashboard' }" class="flex flex-col md:flex-row min-h-screen">
    <!-- Sidebar -->
    <aside
        class="bg-white shadow-lg transition-all duration-300 ease-in-out fixed md:static inset-y-0 left-0 z-50 w-20 md:w-64"
        :class="{ 'w-64': openSidebar, 'hidden md:block': !openSidebar }">
        <div class="p-4 flex items-center justify-between border-b">
            <h1 :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'opacity-0 hidden': !openSidebar && window.innerWidth < 768 }"
                class="text-xl font-bold text-green-600 transition-opacity duration-300">COLUMBOS</h1>
            <button @click="openSidebar = !openSidebar" class="md:hidden p-2 rounded-full hover:bg-gray-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>
        <nav class="py-4">
            <ul class="space-y-2"><?php
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
                <li>
                    <a href="#" @click.prevent="activeSection = 'dashboard'; openSidebar = false"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'dashboard' }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0l-2-2m2 2V4a1 1 0 00-1-1h-3a1 1 0 00-1 1z" />
                        </svg>
                        <span
                            :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-700 transition-opacity duration-300">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="activeSection = 'orders'; openSidebar = false"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'orders' }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        <span
                            :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-700 transition-opacity duration-300">Applications</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="activeSection = 'forms'; openSidebar = false"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'profile' }">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 8v8a5 5 0 1 0 10 0V6.5a3.5 3.5 0 1 0-7 0V15a2 2 0 0 0 4 0V8" />
                        </svg>
                        <span
                            :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-700 transition-opacity duration-300">Forms</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="activeSection = 'announcement'; openSidebar = false"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'announcement' }">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M12 2c-1.1 0-2 .9-2 2v3h-3c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V9c0-1.1-.9-2-2-2h-3V4c0-1.1-.9-2-2-2Zm4 14H8v-2h8v2Zm-4-8H8V7h4v1Z" />
                        </svg>
                        <span
                            :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-700 transition-opacity duration-300">Announcements</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="activeSection = 'council'; openSidebar = false"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'profile' }">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 21">
                            <path
                                d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path
                                d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span
                            :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-700 transition-opacity duration-300">Councils</span>
                    </a>
                </li>
                <li>
                    <a href="#" @click.prevent="activeSection = 'fraternalbenefits'; openSidebar = false"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'profile' }">
                        <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 22 22">
                            <path
                                d="M10 2C5.58 2 2 5.58 2 10s3.58 8 8 8 8-3.58 8-8-3.58-8-8-8Zm0 14c-3.31 0-6-2.69-6-6s2.69-6 6-6 6 2.69 6 6-2.69 6-6 6Z" />
                        </svg>
                        <span
                            :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-700 transition-opacity duration-300">Fraternal Benefits</span>
                    </a>
                </li>
                <li>
                    <a href="#" id="logoutBtn"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'profile' }">
                        <svg class="shrink-0 w-5 h-5 text-red-600 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                        </svg>
                        <span
                            :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-700 transition-opacity duration-300">Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>