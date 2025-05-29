<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/userModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';
?>

<!-- Main modal -->
<div id="crud-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Add new council
                </h3>
                <button type="button"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-toggle="crud-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Council
                            Number</label>
                        <input type="number" name="name" id="name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="Type council number" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="price"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="number" name="price" id="price"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            placeholder="$2999" required="">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Unit
                            Manager</label>
                        <select id="category"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select category</option>
                            <option value="TV">TV/Monitors</option>
                            <option value="PC">PC</option>
                            <option value="GA">Gaming/Console</option>
                            <option value="PH">Phones</option>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="category"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select
                            Fraternal</label>
                        <select id="category"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                            <option selected="">Select category</option>
                            <option value="TV">TV/Monitors</option>
                            <option value="PC">PC</option>
                            <option value="GA">Gaming/Console</option>
                            <option value="PH">Phones</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label for="description"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Product
                            Description</label>
                        <input datepicker id="default-datepicker" type="text"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Select date">
                    </div>
                </div>
                <button type="submit"
                    class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd"></path>
                    </svg>
                    Add new user
                </button>
            </form>
        </div>
    </div>
</div>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <?php
            $breadcrumbItems = [
                ['title' => 'Admin', 'url' => 'dashboard.php'],
                ['title' => 'User Management']
            ];
            renderBreadcrumb($breadcrumbItems);
            ?>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <section class="mt-4">
                    <!-- <div class="flex justify-end mb-2">
                        <button type="button" data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                            class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Add new user
                        </button>
                    </div> -->

                    <table id="myTable" class="stripe hover w-full" style="width:100%">
                        <thead class="bg-gray-800 text-white text-xs">
                            <tr>
                                <th scope="col" class="px-4 py-3">FIRSTNAME</th>
                                <th scope="col" class="px-4 py-3">LASTNAME</th>
                                <th scope="col" class="px-4 py-3">KCFAPI CODE</th>
                                <th scope="col" class="px-4 py-3">EMAIL</th>
                                <th scope="col" class="px-4 py-3">PHONE</th>
                                <th scope="col" class="px-4 py-3">ROLE</th>
                                <th scope="col" class="px-4 py-3">STATUS</th>
                                <th scope="col" class="px-4 py-3">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">

                            <?php
                            $userModel = new UserModel($conn);
                            $users = $userModel->getAllUser();
                            if ($users) {
                            foreach ($users as $user) {?>
                            <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="px-4 py-3"><?php echo $user['firstname']; ?></td>
                                <td class="px-4 py-3"><?php echo $user['lastname']; ?></td>
                                <td class="px-4 py-3"><?php echo $user['kcfapicode']; ?></td>
                                <td class="px-4 py-3"><?php echo $user['email']; ?></td>
                                <td class="px-4 py-3"><?php echo $user['phone_number']; ?></td>
                                <td class="px-4 py-3"><?php echo $user['role']; ?></td>
                                <td class="px-4 py-3" style="color: <?php echo $user['status'] === 'approved' ? 'green' : 'orange'; ?>">
                                    <?php echo htmlspecialchars($user['status']); ?>
                                </td>
                                <td>
                                    <div class="flex items-center space-x-2">
                                        <button class="view-user-btn text-blue-600 hover:text-blue-800"
                                            data-id="<?= $user['id'] ?>"
                                            data-firstname="<?= htmlspecialchars($user['firstname']) ?>"
                                            data-lastname="<?= htmlspecialchars($user['lastname']) ?>"
                                            data-email="<?= htmlspecialchars($user['email']) ?>"
                                            data-phone="<?= htmlspecialchars($user['phone_number']) ?>"
                                            data-kcfapi="<?= htmlspecialchars($user['kcfapicode']) ?>"
                                            data-role="<?= htmlspecialchars($user['role']) ?>"
                                            data-status="<?= htmlspecialchars($user['status']) ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>

                                        <button class="reset-password-btn text-yellow-600 hover:text-yellow-800"
                                            data-id="<?= $user['id'] ?>"
                                            data-name="<?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>"
                                            data-phone="<?= htmlspecialchars($user['phone_number']) ?>">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                            </svg>
                                        </button>

                                        <?php if ($user['status'] === 'pending' || $user['status'] === 'disabled'): ?>
                                            <button class="status-btn approve-btn bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-green-900 dark:text-green-300"
                                                data-id="<?= $user['id'] ?>"
                                                data-action="approve"
                                                data-name="<?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>">
                                                Approve
                                            </button>
                                        <?php elseif ($user['status'] === 'approved'): ?>
                                            <button class="status-btn disable-btn bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-red-900 dark:text-red-300"
                                                data-id="<?= $user['id'] ?>"
                                                data-action="disable"
                                                data-name="<?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?>">
                                                Disable
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php }
                                } else {
                                    echo "No councils found.";
                                }
                            ?>
                        </tbody>
                    </table>
                </section>
            </div>
    </main>
</div>

<!-- User Details Modal -->
<div id="userDetailsModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between">
                <h3 class="text-lg leading-6 font-medium text-gray-900">User Details</h3>
                <button id="closeUserModal" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="mt-4">
                <div class="user-info space-y-3">
                    <!-- User details will be populated here by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status button click handler
    document.querySelectorAll('.status-btn').forEach(button => {
        button.addEventListener('click', function() {
            const action = this.dataset.action;
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            
            let title, text, icon, confirmButtonText, confirmButtonColor;
            
            if (action === 'approve') {
                title = 'Approve User?';
                text = `Are you sure you want to approve ${userName}?`;
                icon = 'question';
                confirmButtonText = 'Yes, approve';
                confirmButtonColor = '#4CAF50';
            } else {
                title = 'Disable User?';
                text = `Are you sure you want to disable ${userName}?`;
                icon = 'warning';
                confirmButtonText = 'Yes, disable';
                confirmButtonColor = '#d33';
            }
            
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#3085d6',
                confirmButtonText: confirmButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `../../controllers/adminController/userStatusController.php?action=${action}&id=${userId}`;
                }
            });
        });
    });

    // View user details
    const modal = document.getElementById('userDetailsModal');
    const closeBtn = document.getElementById('closeUserModal');
    
    document.querySelectorAll('.view-user-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userData = this.dataset;
            const userInfo = document.querySelector('.user-info');
            
            userInfo.innerHTML = `
                <div class="grid grid-cols-3 gap-4 text-sm">
                    <div class="font-medium text-gray-500">Name:</div>
                    <div class="col-span-2">${userData.firstname} ${userData.lastname}</div>
                    
                    <div class="font-medium text-gray-500">Email:</div>
                    <div class="col-span-2">${userData.email}</div>
                    
                    <div class="font-medium text-gray-500">Phone:</div>
                    <div class="col-span-2">${userData.phone}</div>
                    
                    <div class="font-medium text-gray-500">KCFAPI Code:</div>
                    <div class="col-span-2">${userData.kcfapi}</div>
                    
                    <div class="font-medium text-gray-500">Role:</div>
                    <div class="col-span-2">${userData.role}</div>
                    
                    <div class="font-medium text-gray-500">Status:</div>
                    <div class="col-span-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full 
                            ${userData.status === 'approved' ? 'bg-green-100 text-green-800' : 
                            userData.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                            'bg-red-100 text-red-800'}">
                            ${userData.status}
                        </span>
                    </div>
                </div>
            `;
            
            modal.classList.remove('hidden');
        });
    });
    
    closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
    
    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Reset Password functionality
    document.querySelectorAll('.reset-password-btn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.dataset.id;
            const userName = this.dataset.name;
            const userPhone = this.dataset.phone;
            
            Swal.fire({
                title: 'Reset Password?',
                html: `Are you sure you want to reset the password for <br><strong>${userName}</strong>?<br><br>A new temporary password will be sent via SMS to:<br><strong>${userPhone}</strong>`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#fbbf24',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, reset password',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`../../controllers/adminController/resetPasswordController.php?id=${userId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.json()
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Request failed: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    if(result.value.success) {
                        Swal.fire({
                            title: 'Password Reset!',
                            text: 'A new temporary password has been sent via SMS.',
                            icon: 'success',
                            confirmButtonColor: '#3085d6'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: result.value.message || 'Failed to reset password.',
                            icon: 'error',
                            confirmButtonColor: '#3085d6'
                        });
                    }
                }
            });
        });
    });
});
</script>

<?php
include '../../includes/footer.php';
?>