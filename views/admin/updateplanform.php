<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $model   = new fraternalBenefitsModel($conn);
        $benefit = $model->getFraternalBenefitById($id);

        if (! $benefit) {
            die('Fraternal benefit not found.');
        }
    }
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="<?php echo BASE_URL?>views/admin/dashboard.php"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 me-2.5 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="<?php echo BASE_URL?>views/admin/fraternalbenefits.php"
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Fraternal Benefits</a>
                        </div>
                    </li>
                     <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                             <a href="<?php echo BASE_URL?>views/admin/moreplandetails.php?id=<?php echo $benefit['id']; ?>"
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Plan Details</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Update Plan</span>
                        </div>
                    </li>
                </ol>
            </nav>
            
            <section class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Update Fraternal Benefit Plan</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Modify the details of the plan below.</p>
                </div>

                <form class="p-6" method="POST"
                    action="<?php echo BASE_URL ?>controllers/adminController/updateFraternalBenefitsController.php"
                    enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?php echo $benefit['id']; ?>">

                    <div class="grid gap-6 mb-6 md:grid-cols-2">
                        <div>
                            <label for="type"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Plan Type</label>
                            <select id="type" name="type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required>
                                <option disabled>Select category</option>
                                <?php
                                    $types = ['Investment Plan', 'Retirement Plan', 'Educational Plan', 'Protection Plan', 'Term Plan'];
                                    foreach ($types as $typeOption) {
                                        $selected = ($benefit['type'] === $typeOption) ? 'selected' : '';
                                        echo "<option value=\"$typeOption\" $selected>$typeOption</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Plan Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="<?php echo htmlspecialchars($benefit['name']); ?>" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="about"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">About Plan</label>
                            <textarea name="about" id="about" rows="4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required><?php echo htmlspecialchars($benefit['about']); ?></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label for="benefits"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Plan Benefits</label>
                            <textarea name="benefits" id="benefits" rows="4"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                required><?php echo htmlspecialchars($benefit['benefits']); ?></textarea>
                        </div>

                        <div>
                            <label for="face_value"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Face Value (PHP)</label>
                            <input type="number" name="face_value" id="face_value"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="<?php echo htmlspecialchars($benefit['face_value']); ?>" required step="0.01">
                        </div>
                        
                        <div>
                            <label for="contribution_period"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contribution Period (Years)</label>
                            <input type="number" name="contribution_period" id="contribution_period"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="<?php echo htmlspecialchars($benefit['contribution_period']); ?>" required>
                        </div>

                        <div>
                            <label for="years_to_maturity"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Years to Maturity</label>
                            <input type="number" name="years_to_maturity" id="years_to_maturity"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="<?php echo htmlspecialchars($benefit['years_to_maturity']); ?>" required>
                        </div>

                        <div>
                            <label for="years_of_protection"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Years of Protection</label>
                            <input type="number" name="years_of_protection" id="years_of_protection"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                value="<?php echo htmlspecialchars($benefit['years_of_protection']); ?>" required>
                        </div>

                        <div class="md:col-span-2">
                            <label for="imageInput"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Plan Image</label>
                            <div class="flex items-center space-x-4">
                                <img id="imagePreview" src="<?php echo BASE_URL . $benefit['image']; ?>"
                                    alt="Current Plan Image" class="w-32 h-32 object-cover rounded-lg border border-gray-300 dark:border-gray-600" />
                                <div class="flex flex-col">
                                     <input type="file" id="imageInput" accept="image/*" name="image"
                                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, or GIF (MAX. 2MB). Leave blank to keep current image.</p>
                                     <input type="hidden" name="current_image" value="<?php echo $benefit['image']; ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                         <a href="<?php echo BASE_URL?>views/admin/moreplandetails.php?id=<?php echo $benefit['id']; ?>" type="button" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700 transition-colors duration-200">
                            Cancel
                        </a>
                        <button type="submit"
                            class="text-white inline-flex items-center bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 transition-colors duration-200">
                            <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Update Plan
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </main>
</div>

<script>
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');

imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        // If no file is selected, you might want to revert to the original image
        // For now, it keeps the new preview or old if no new one was loaded.
    }
});
</script>

<?php
include '../../includes/footer.php';
?>