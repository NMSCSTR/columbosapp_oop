<div x-data="{ openSidebar: false, activeSection: 'dashboard' }" class="flex flex-col md:flex-row min-h-screen">

    <!-- Sidebar -->
    <aside
        class="bg-white shadow-xl transition-all duration-300 ease-in-out fixed md:static inset-y-0 left-0 z-50 w-20 md:w-64 border-r border-gray-200"
        :class="{ 'w-64': openSidebar, 'hidden md:block': !openSidebar }">

        <!-- Logo / Header -->
        <div class="p-4 flex items-center justify-between border-b bg-gradient-to-r from-green-600 to-green-700">
            <div class="flex items-center space-x-3">
                <img src="https://www.kcfapi.com/wp-content/uploads/2022/10/kclogoshine_rs.jpg" class="w-8 h-8" alt="Logo">
                <h1
                    :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'opacity-0 hidden': !openSidebar && window.innerWidth < 768 }"
                    class="text-xl font-bold text-white transition-opacity duration-300">
                    Columbos
                </h1>
            </div>

            <button @click="openSidebar = !openSidebar"
                class="md:hidden p-2 rounded-lg hover:bg-green-600 text-white focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="py-6">
            <div class="px-4 mb-6"
                :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</p>
            </div>

            <ul class="space-y-2 px-2">

                <!-- Application -->
                <li>
                    <a href="member.php"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-green-50 group">
                        <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-3-3v6m-7 8h14a2 2 0 002-2V7l-5-5H5a2 2 0 00-2 2v16a2 2 0 002 2z" />
                        </svg>
                        <span
                            :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-600 font-medium group-hover:text-green-600">
                            Application
                        </span>
                    </a>
                </li>

                <!-- My Plan -->
                <li>
                    <a href="myApplication.php"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-green-50 group">
                        <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span
                            :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-600 font-medium group-hover:text-green-600">
                            My Plan
                        </span>
                    </a>
                </li>

                <!-- Transactions -->
                <li>
                    <a href="transactions.php"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-green-50 group">
                        <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1M5 5h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                        </svg>
                        <span
                            :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-600 font-medium group-hover:text-green-600">
                            Transaction
                        </span>
                    </a>
                </li>

                <!-- Form View -->
                <li>
                    <a href="applicant.php"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-green-50 group">
                        <svg class="h-6 w-6 text-gray-400 group-hover:text-green-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A9 9 0 1118.88 6.196M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span
                            :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-600 font-medium group-hover:text-green-600">
                            Form View
                        </span>
                    </a>
                </li>

                <!-- Divider -->
                <hr class="my-6 border-gray-200">

                <!-- Logout -->
                <li>
                    <a href="#" id="logoutBtn"
                        class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-red-50 group">
                        <svg class="h-6 w-6 text-red-400 group-hover:text-red-600"
                            xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 18 16" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 012 2v10a2 2 0 01-2 2h-3" />
                        </svg>
                        <span
                            :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }"
                            class="text-gray-600 font-medium group-hover:text-red-600">
                            Logout
                        </span>
                    </a>
                </li>

            </ul>
        </nav>
    </aside>

