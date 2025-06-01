<div x-data="{ openSidebar: false, activeSection: 'dashboard' }" class="flex flex-col md:flex-row min-h-screen">
        <!-- Sidebar -->
        <aside class="bg-white shadow-xl transition-all duration-300 ease-in-out fixed md:static inset-y-0 left-0 z-50 w-20 md:w-64 border-r border-gray-200"
               :class="{ 'w-64': openSidebar, 'hidden md:block': !openSidebar }">
            
            <!-- Logo/Brand Header -->
            <div class="p-4 flex items-center justify-between border-b bg-gradient-to-r from-green-600 to-green-700">
                <div class="flex items-center space-x-3">
                    <img src="https://www.kcfapi.com/wp-content/uploads/2022/10/kclogoshine_rs.jpg" alt="Logo" class="w-8 h-8">
                    <h1 :class="{ 'opacity-100': openSidebar || window.innerWidth >= 768, 'opacity-0 hidden': !openSidebar && window.innerWidth < 768 }" 
                        class="text-xl font-bold transition-opacity duration-300">Columbos</h1>
                </div>
                <button @click="openSidebar = !openSidebar" 
                        class="md:hidden p-2 rounded-lg hover:bg-green-600 text-white focus:outline-none focus:ring-2 focus:ring-green-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>

            <!-- Navigation Menu -->
            <nav class="py-6">
                <div class="px-4 mb-6" :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Main Menu</p>
                </div>
                
                <ul class="space-y-2 px-2">
                    <li>
                        <a href="#" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-green-50 group">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-green-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                            </div>
                            <span :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }" 
                                  class="text-gray-600 font-medium group-hover:text-green-600 transition-all duration-200">Application</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="applicant.php" 
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-green-50 group"
                           :class="{ 'bg-green-50': activeSection === 'profile' }">
                            <div class="flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400 group-hover:text-green-600 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }" 
                                  class="text-gray-600 font-medium group-hover:text-green-600 transition-all duration-200">Form View</span>
                        </a>
                    </li>

                    <!-- Divider -->
                    <div class="my-6 px-4" :class="{ 'block': openSidebar || window.innerWidth >= 768, 'hidden': !openSidebar && window.innerWidth < 768 }">
                        <hr class="border-gray-200">
                    </div>

                    <li>
                        <a href="#" id="logoutBtn"
                           class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-all duration-200 hover:bg-red-50 group">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-red-400 group-hover:text-red-600 transition-colors duration-200"
                                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3" />
                                </svg>
                            </div>
                            <span :class="{ 'block opacity-100': openSidebar || window.innerWidth >= 768, 'hidden opacity-0': !openSidebar && window.innerWidth < 768 }" 
                                  class="text-gray-600 font-medium group-hover:text-red-600 transition-all duration-200">Logout</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>