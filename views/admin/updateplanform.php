<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/fraternalBenefitsModel.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $model = new fraternalBenefitsModel($conn);
    $benefit = $model->getFraternalBenefitById($id);

    if (!$benefit) {
        die('Fraternal benefit not found.');
    }
}
?>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    <!-- Main Content -->
    <main class="flex-1">
        <div class="p-4 sm:ml-64">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="#"
                            class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <a href="#"
                                class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Admin</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 9 4-4-4-4" />
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Fraternal
                                Benefits</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="p-4 rounded-lg dark:border-gray-700">
                <!-- Responsive grid layout -->

                <form class="p-4 md:p-5" method="POST"
                    action="<?php echo BASE_URL ?>controllers/adminController/updateFraternalBenefitsController.php"
                    enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?php echo $benefit['id']; ?>">

                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <div class="col-span-2">
                            <label for="type"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Type</label>
                            <select id="type" name="type"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
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

                        <div class="col-span-2">
                            <label for="name"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                            <input type="text" name="name" id="name"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                value="<?php echo htmlspecialchars($benefit['name']); ?>" required>
                        </div>

                        <div class="col-span-2">
                            <label for="about"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">About</label>
                            <textarea name="about" id="about"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required><?php echo htmlspecialchars($benefit['about']); ?></textarea>
                        </div>

                        <div class="col-span-2">
                            <label for="benefits"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Benefits</label>
                            <textarea name="benefits" id="benefits"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                required><?php echo htmlspecialchars($benefit['benefits']); ?></textarea>
                        </div>

                        <div class="col-span-2">
                            <label for="contribution_period"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Contribution
                                Period</label>
                            <input type="text" name="contribution_period" id="contribution_period"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5"
                                value="<?php echo htmlspecialchars($benefit['contribution_period']); ?>" required>
                        </div>

                        <div class="col-span-2">
                            <label for="image"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Upload New
                                Image</label>
                            <div class="max-w-md mx-auto rounded-lg overflow-hidden md:max-w-xl">
                                <div class="md:flex">
                                    <div class="w-full p-3">
                                        <div
                                            class="relative border-dotted h-48 rounded-lg border-dashed border-2 border-blue-700 bg-gray-100 flex justify-center items-center">

                                            <!-- Image Preview -->
                                            <img id="imagePreview" src="<?php echo BASE_URL . $benefit['image']; ?>"
                                                alt="Preview" class="absolute object-contain max-h-44 rounded-lg" />

                                            <!-- File Input -->
                                            <input type="file" id="imageInput" accept="image/*"
                                                class="h-full w-full opacity-0 cursor-pointer" name="image">
                                                <input type="hidden" name="current_image" value="<?php echo $benefit['image']; ?>">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 font-medium rounded-lg text-sm px-5 py-2.5">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        UPDATE PLAN
                    </button>
                </form>
            </div>
    </main>
</div>

<script>
const imageInput = document.getElementById('imageInput');
const imagePreview = document.getElementById('imagePreview');
const imagePlaceholder = document.getElementById('imagePlaceholder');

imageInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.classList.remove('hidden');
            imagePlaceholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    } else {
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
        imagePlaceholder.classList.remove('hidden');
    }
});
</script>

<?php
include '../../includes/footer.php';
?>