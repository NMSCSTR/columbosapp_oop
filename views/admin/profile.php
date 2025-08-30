<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/userModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';

    $userModel = new UserModel($conn);
    $user = $userModel->getUserById($_SESSION['user_id']);
    
?>

<!-- Add required CSS for animations -->
<style>
    .profile-card {
        transition: transform 0.3s ease-in-out;
    }
    .profile-card:hover {
        transform: translateY(-5px);
    }
    .input-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .input-group input {
        transition: border-color 0.3s ease;
    }
    .input-group input:focus + .input-highlight {
        width: 100%;
    }
    .input-highlight {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2px;
        width: 0;
        background-color: #2563eb;
        transition: width 0.3s ease;
    }
    .save-button {
        transition: all 0.3s ease;
    }
    .save-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .profile-avatar {
        position: relative;
        overflow: hidden;
    }
    .profile-avatar::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 50%;
        height: 100%;
        background: linear-gradient(
            to right,
            rgba(255, 255, 255, 0) 0%,
            rgba(255, 255, 255, 0.3) 100%
        );
        transform: skewX(-25deg);
        animation: shimmer 2s infinite;
    }
    @keyframes shimmer {
        100% {
            left: 100%;
        }
    }
</style>

<div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <?php include '../../partials/sidebar.php'?>
    
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <?php
            $breadcrumbItems = [
                ['title' => 'Admin', 'url' => 'dashboard.php'],
                ['title' => 'Profile']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>

            <div class="p-4">
                <div class="max-w-3xl mx-auto">
                    <!-- Profile Header -->
                    <div class="profile-card bg-white rounded-xl shadow-lg p-8 mb-8 border border-gray-100">
                        <div class="flex flex-col md:flex-row items-center md:space-x-8">
                            <div class="profile-avatar p-4 bg-gradient-to-r from-blue-100 to-blue-50 rounded-full mb-4 md:mb-0">
                                <svg class="w-24 h-24 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-center md:text-left">
                                <h2 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></h2>
                                <p class="text-gray-600 text-lg mb-4">Administrator</p>
                                <div class="flex flex-wrap gap-3 justify-center md:justify-start">
                                    <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($user['email']) ?>
                                    </span>
                                    <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                        <i class="fas fa-phone mr-2"></i><?= htmlspecialchars($user['phone_number']) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Form -->
                    <div class="profile-card bg-white rounded-xl shadow-lg p-8 border border-gray-100">
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 pb-2 border-b border-gray-200">Profile Settings</h3>
                        
                        <form id="profileForm" action="../../controllers/adminController/updateProfileController.php" method="POST" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="input-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                                    <input type="text" name="firstname" value="<?= htmlspecialchars($user['firstname']) ?>" 
                                        class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <div class="input-highlight"></div>
                                </div>
                                <div class="input-group">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                                    <input type="text" name="lastname" value="<?= htmlspecialchars($user['lastname']) ?>" 
                                        class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                    <div class="input-highlight"></div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" 
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <div class="input-highlight"></div>
                            </div>

                            <div class="input-group">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                                <input type="text" name="phone_number" value="<?= htmlspecialchars($user['phone_number']) ?>" 
                                    class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <div class="input-highlight"></div>
                            </div>

                            <div class="border-t border-gray-200 pt-8 mt-8">
                                <h4 class="text-xl font-bold text-gray-900 mb-6">Change Password</h4>
                                <div class="space-y-6">
                                    <div class="input-group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                                        <input type="password" name="current_password" 
                                            class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <div class="input-highlight"></div>
                                    </div>
                                    <div class="input-group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                                        <input type="password" name="new_password" 
                                            class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <div class="input-highlight"></div>
                                    </div>
                                    <div class="input-group">
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                                        <input type="password" name="confirm_password" 
                                            class="block w-full px-4 py-3 rounded-lg border-gray-300 bg-gray-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                        <div class="input-highlight"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-6">
                                <button type="submit" 
                                    class="save-button inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.getElementById('profileForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalContent = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Saving...
    `;
    submitButton.disabled = true;
    
    const formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#3085d6',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then(() => {
                if (data.reload) {
                    window.location.reload();
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error',
                confirmButtonColor: '#3085d6',
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: 'Error!',
            text: 'An unexpected error occurred.',
            icon: 'error',
            confirmButtonColor: '#3085d6',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    })
    .finally(() => {
        // Restore button state
        submitButton.innerHTML = originalContent;
        submitButton.disabled = false;
    });
});

// Add input animation
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.querySelector('.input-highlight').style.width = '100%';
    });
    
    input.addEventListener('blur', function() {
        if (!this.value) {
            this.parentElement.querySelector('.input-highlight').style.width = '0';
        }
    });
});
</script>

<?php include '../../includes/footer.php'; ?>