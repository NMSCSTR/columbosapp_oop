<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/adminModel/announcementModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';

    $model = new announcementModel($conn);
    // $users = $model->getAllUserPhoneNumber();
    $users = $model->getSelectableUsers();
    $announcements = $model->getAllAnnouncement();
?>

<style>
    .announcement-card {
        transition: all 0.3s ease;
    }
    .announcement-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .form-input {
        transition: all 0.2s ease;
    }
    .form-input:focus {
        transform: scale(1.01);
    }
    .announcement-list {
        scrollbar-width: thin;
        scrollbar-color: #CBD5E0 #EDF2F7;
    }
    .announcement-list::-webkit-scrollbar {
        width: 8px;
    }
    .announcement-list::-webkit-scrollbar-track {
        background: #EDF2F7;
        border-radius: 4px;
    }
    .announcement-list::-webkit-scrollbar-thumb {
        background-color: #CBD5E0;
        border-radius: 4px;
        border: 2px solid #EDF2F7;
    }
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .slide-in {
        animation: slideIn 0.3s ease-out forwards;
    }
    .character-count {
        transition: all 0.2s ease;
    }
    .character-count.warning {
        color: #ED8936;
    }
    .character-count.error {
        color: #E53E3E;
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
                ['title' => 'Announcements']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>

            <div class="container mx-auto px-4 py-8">
                <!-- Two Column Layout -->
                <div class="flex flex-col lg:flex-row gap-8">
                    <!-- Left: Announcement List -->
                    <div class="lg:w-2/3 w-full">
                        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-2xl font-bold text-gray-900">Recent Announcements</h2>
                                <div class="text-sm text-gray-500"><?= count($announcements) ?> announcements</div>
                            </div>
                            <div class="announcement-list max-h-[600px] overflow-y-auto space-y-6 pr-2">
                                <?php if (empty($announcements)): ?>
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No announcements</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by creating a new announcement.</p>
                                </div>
                                <?php endif; ?>
                                
                                <?php foreach ($announcements as $index => $announcement): ?>
                                <div class="announcement-card bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md slide-in" style="animation-delay: <?= $index * 0.1 ?>s">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($announcement['subject']) ?></h3>
                                                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                                                        <span>Posted by Admin</span>
                                                        <span>•</span>
                                                        <time datetime="<?= $announcement['date_posted'] ?>">
                                                            <?= date("M d, Y h:i A", strtotime($announcement['date_posted'])) ?>
                                                        </time>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="relative">
                                                <button id="dropdownMenuIconButton<?= $index ?>"
                                                    data-dropdown-toggle="dropdownDots<?= $index ?>"
                                                    class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
                                                    <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </button>

                                                <div id="dropdownDots<?= $index ?>"
                                                    class="hidden absolute right-0 mt-2 w-44 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-10">
                                                    <button class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-200 copy-announcement">
                                                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                        </svg>
                                                        Copy
                                                    </button>
                                                    <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200 delete-announcement" 
                                                            data-id="<?= $announcement['id'] ?>">
                                                        <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                        Delete
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="prose prose-sm max-w-none text-gray-700">
                                            <?= nl2br(htmlspecialchars($announcement['content'])) ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Add Announcement Form -->
                    <div class="lg:w-1/3 w-full">
                        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 sticky top-4">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Create Announcement</h2>
                            
                            <form id="announcementForm" action="<?php echo BASE_URL ?>controllers/adminController/addAnnouncementController.php"
                                method="POST" class="space-y-6">
                                <div class="space-y-2">
                                    <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                                    <input type="text" name="subject" id="subject"
                                        class="form-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200"
                                        placeholder="Enter announcement subject" required>
                                </div>

                                <div class="space-y-2">
                                    <label for="announcement" class="block text-sm font-medium text-gray-700">Message</label>
                                    <textarea name="announcement" id="announcement"
                                        class="form-input block w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all duration-200 resize-none"
                                        rows="6"
                                        placeholder="Type your announcement message here..." required></textarea>
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-500">Min. 30 characters</span>
                                        <span class="character-count text-gray-500">0 characters</span>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <label class="block text-sm font-semibold text-gray-800">
                                        Send Announcement To
                                    </label>

                                    <div class="max-h-56 overflow-y-auto rounded-xl border border-gray-200 bg-gray-50 p-2 shadow-inner">
                                        <?php foreach ($users as $user): ?>
                                            <label
                                                class="group flex items-center justify-between gap-3 rounded-lg bg-white px-3 py-2 text-sm text-gray-700
                                                    border border-transparent hover:border-blue-200 hover:bg-blue-50
                                                    transition-all duration-150 cursor-pointer">

                                                <div class="flex items-center gap-3">
                                                    <input type="checkbox"
                                                        name="recipients[]"
                                                        value="<?= $user['id'] ?>"
                                                        class="h-4 w-4 rounded border-gray-300 text-blue-600
                                                                focus:ring-2 focus:ring-blue-500 focus:ring-offset-1">

                                                    <div class="leading-tight">
                                                        <p class="font-medium text-gray-800 group-hover:text-blue-700">
                                                            <?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>
                                                        </p>
                                                        <p class="text-xs text-gray-500">
                                                            <?= htmlspecialchars($user['phone_number']) ?>
                                                        </p>
                                                    </div>
                                                </div>

                                                <svg class="h-4 w-4 text-gray-300 group-hover:text-blue-500 transition"
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                    <p class="text-xs text-gray-500">
                                        Select one or more users to receive this announcement via SMS.
                                    </p>
                                </div>



                                <div class="pt-4">
                                    <button type="submit"
                                        class="w-full flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                        </svg>
                                        Publish Announcement
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const announcementForm = document.getElementById('announcementForm');
    const announcementTextarea = document.getElementById('announcement');
    const characterCount = document.querySelector('.character-count');
    
    // Update character count
    function updateCharacterCount() {
        const count = announcementTextarea.value.length;
        characterCount.textContent = `${count} characters`;
        
        if (count < 30) {
            characterCount.classList.remove('text-gray-500', 'text-green-500');
            characterCount.classList.add('text-red-500');
        } else {
            characterCount.classList.remove('text-gray-500', 'text-red-500');
            characterCount.classList.add('text-green-500');
        }
    }
    
    announcementTextarea.addEventListener('input', updateCharacterCount);
    
    // Form submission
    announcementForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (announcementTextarea.value.length < 30) {
            Swal.fire({
                title: 'Error!',
                text: 'Announcement must be at least 30 characters long.',
                icon: 'error',
                confirmButtonColor: '#3B82F6'
            });
            return;
        }
        
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
                    text: 'Announcement has been published.',
                    icon: 'success',
                    confirmButtonColor: '#3B82F6'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'Something went wrong.',
                    icon: 'error',
                    confirmButtonColor: '#3B82F6'
                });
            }
        })
        .catch(error => {
            Swal.fire({
                title: 'Success!',
                text: 'Announcement has been published.',
                icon: 'success',
                confirmButtonColor: '#3B82F6'
            });
        }).then(() => {
                    window.location.reload();
                });
    });
    
    // Delete announcement
    document.querySelectorAll('.delete-announcement').forEach(button => {
        button.addEventListener('click', function() {
            const announcementId = this.dataset.id;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `../../controllers/adminController/deleteAnnouncement.php?id=${announcementId}`;
                }
            });
        });
    });
    
    // Copy announcement
    document.querySelectorAll('.copy-announcement').forEach(button => {
        button.addEventListener('click', function() {
            const announcementCard = this.closest('.announcement-card');
            const content = announcementCard.querySelector('.prose').textContent.trim();
            
            navigator.clipboard.writeText(content).then(() => {
                // Show success message
                const originalText = this.innerHTML;
                this.innerHTML = `
                    <svg class="inline-block w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Copied!
                `;
                this.classList.add('text-green-600');
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.remove('text-green-600');
                }, 2000);
            });
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>