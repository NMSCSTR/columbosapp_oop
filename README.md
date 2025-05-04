:

🔑 Login Flow Summary

1. Login Form (pages/login.php)

Posts to: controllers/auth/login.php

Accepts: email, password, optional remember checkbox.

Displays input fields and pre-fills with PHP variables.

2. User Authorization Middleware (middleware/auth.php)

Function authorize() checks if a user is logged in and role is allowed.

Redirects unauthorized users to unauthorized.php.

3. Role-Based Redirect (middleware/role_redirect.php)

Redirects user to dashboard based on their role.

Assumes session is already started and role is set.

4. User Model (models/usersModel.php)

Can get user by email.

Can create and delete users.

Passwords are hashed using bcrypt.

council moda

                                    <button type="button" data-modal-target="crud-modal" data-modal-toggle="crud-modal"
                                        class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">
                                        <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewbox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path clip-rule="evenodd" fill-rule="evenodd"
                                                d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                                        </svg>
                                        Add new council
                                    </button>

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
                    Add new council
                </button>
            </form>
        </div>
    </div>
</div>




                               <table id="myTable" class="stripe hover w-full" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-3">Id</th>
                                            <th class="px-4 py-3">Council Number</th>
                                            <th class="px-4 py-3">Name</th>
                                            <th class="px-4 py-3">UM Id</th>
                                            <th class="px-4 py-3">FC Id</th>
                                            <th class="px-4 py-3">Date Established</th>
                                            <th class="px-4 py-3">Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $userModel = new CouncilModel($conn);
                                            $councils  = $userModel->getAllCouncil();

                                            if ($councils) {
                                                foreach ($councils as $council) {
                                                    echo "<tr class='border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700'>
                                                            <td class='px-4 py-3'>{$council['council_id']}</td>
                                                            <td class='px-4 py-3'>{$council['council_number']}</td>
                                                            <td class='px-4 py-3'>{$council['council_name']}</td>
                                                            <td class='px-4 py-3'>{$council['unit_manager_id']}</td>
                                                            <td class='px-4 py-3'>{$council['fraternal_counselor_id']}</td>
                                                            <td class='px-4 py-3'>{$council['date_established']}</td>
                                                            <td class='px-4 py-3'>{$council['date_created']}</td>
                                                        </tr>";
                                                }
                                            } else {
                                                echo "<tr><td colspan='7' class='px-4 py-3 text-center'>No councils found.</td></tr>";
                                            }
                                        ?>
                                    </tbody>
                                </table>




                                                                            <th scope="col" class="px-4 py-3">ID</th>
                                            <th scope="col" class="px-4 py-3">FIRSTNAME</th>
                                            <th scope="col" class="px-4 py-3">LASTNAME</th>
                                            <th scope="col" class="px-4 py-3">KCFAPI CODE</th>
                                            <th scope="col" class="px-4 py-3">EMAIL</th>
                                            <th scope="col" class="px-4 py-3">PHONE</th>
                                            <th scope="col" class="px-4 py-3">ROLE</th>
                                            <th scope="col" class="px-4 py-3">STATUS</th>
                                            <th scope="col" class="px-4 py-3">DATE CREATED</th>
                                            <!-- <th scope="col" class="px-4 py-3">Last Update</th> -->



                                            <?php
                                            $userModel = new UserModel($conn);

                                            $users = $userModel->getAllUser();
                                            if ($users) {
                                            foreach ($users as $user) {?>
                                        <tr
                                            class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="px-4 py-3"><?php echo $user['id'] ?></td>
                                            <td class="px-4 py-3"><?php echo $user['firstname']; ?></td>
                                            <td class="px-4 py-3"><?php echo $user['lastname']; ?></td>
                                            <td class="px-4 py-3"><?php echo $user['kcfapicode']; ?></td>
                                            <td class="px-4 py-3"><?php echo $user['email']; ?></td>
                                            <td class="px-4 py-3"><?php echo $user['phone_number']; ?></td>
                                            <td class="px-4 py-3"><?php echo $user['role']; ?></td>
                                            <td class="px-4 py-3"><?php echo $user['status']; ?></td>
                                            <td class="px-4 py-3"><?php echo $user['created_at']; ?></td>

                                        </tr>
                                        <?php }
                                            } else {
                                                echo "No councils found.";
                                            }
                                        ?>