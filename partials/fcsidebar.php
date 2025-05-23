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
            <ul class="space-y-2">
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
                    <a href="#" @click.prevent="activeSection = 'council'; openSidebar = false"
                        class="flex items-center space-x-3 px-4 py-2 hover:bg-gray-100 rounded-lg transition-colors"
                        :class="{ 'bg-gray-100': activeSection === 'profile' }">
                    <svg class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
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
                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 22">
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