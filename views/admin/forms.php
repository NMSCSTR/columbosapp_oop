<?php
    session_start();
    include '../../includes/config.php';
    include '../../includes/header.php';
    include '../../includes/db.php';
    include '../../models/adminModel/councilModel.php';
    include '../../includes/alert2.php';
    include '../../models/adminModel/FormsModel.php';
    $formsModel = new FormsModel($conn);
    $files = $formsModel->viewAllForms();
?>

<!-- Import DataTables CSS and JS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


<!-- Main modal -->
<div id="authentication-modal" tabindex="-1" aria-hidden="true"
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                    Upload file here
                </h3>
                <button type="button"
                    class="end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="authentication-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5">
                <form method="POST" action="<?= BASE_URL?>controllers/adminController/formControllers.php"
                    enctype="multipart/form-data">
                    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            for="filename">Filename</label>
                        <input type="text" id="filename" name="filename"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                            required />
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            for="description">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="file_type">File
                            Type</label>
                        <input type="text" id="file_type" name="file_type"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" />
                    </div>
                    <div class="mb-6 pt-4">
                        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                            Upload File
                        </label>

                        <div class="mb-8">
                            <input type="file" name="file" id="file" class="sr-only" />
                            <label for="file"
                                class="relative flex min-h-[200px] items-center justify-center rounded-md border border-dashed border-[#e0e0e0] p-12 text-center">
                                <div>
                                    <span class="mb-2 block text-xl font-semibold text-[#07074D]">
                                        Drop files here
                                    </span>
                                    <span class="mb-2 block text-base font-medium text-[#6B7280]">
                                        Or
                                    </span>
                                    <span
                                        class="inline-flex rounded border border-[#e0e0e0] py-2 px-7 text-base font-medium text-[#07074D]">
                                        Browse
                                    </span>
                                </div>
                            </label>
                        </div>

                        <!-- File Preview -->
                        <div id="file-preview" class="hidden mb-5 rounded-md bg-[#F5F7FB] py-4 px-8">
                            <div class="flex items-center justify-between">
                                <span id="file-name" class="truncate pr-3 text-base font-medium text-[#07074D]"></span>
                                <button type="button" id="remove-file" class="text-[#07074D]">
                                    <svg width="10" height="10" viewBox="0 0 10 10" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.28 0.28C0.65 -0.09 1.25 -0.09 1.63 0.28L9.72 8.37C10.09 8.74 10.09 9.35 9.72 9.72C9.35 10.09 8.74 10.09 8.37 9.72L0.28 1.63C-0.09 1.26 -0.09 0.65 0.28 0.28Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M0.28 9.72C-0.09 9.35 -0.09 8.74 0.28 8.37L8.37 0.28C8.74 -0.09 9.35 -0.09 9.72 0.28C10.09 0.65 10.09 1.26 9.72 1.63L1.63 9.72C1.26 10.09 0.65 10.09 0.28 9.72Z"
                                            fill="currentColor" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="upload"
                            class="hover:shadow-form w-full rounded-md bg-[#6A64F1] py-3 px-8 text-center text-base font-semibold text-white outline-none">
                            Upload File
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>




<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php' ?>

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
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Forms</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <section class="bg-gray-50 p-5 rounded shadow">
                    <div class="flex justify-end mb-2">
                        <button data-modal-target="authentication-modal" data-modal-toggle="authentication-modal"
                            class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800"
                            type="button">
                            <svg class="h-3.5 w-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <path clip-rule="evenodd" fill-rule="evenodd"
                                    d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" />
                            </svg>
                            Upload forms
                        </button>
                    </div>

                    <table id="myTable" class="min-w-full divide-y divide-gray-200 shadow-sm rounded-lg overflow-hidden">
                        <thead class="bg-white text-gray-500">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">FILENAME</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">DESCRIPTION</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">TYPE</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">UPLOADED</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php while ($row = mysqli_fetch_assoc($files)) : ?>
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($row['filename']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['description']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($row['file_type']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date("F j, Y", strtotime($row['uploaded_on'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                                        <a href="#" class="text-green-600 hover:text-green-800 view-btn" data-filename="<?= htmlspecialchars($row['filename']) ?>" data-path="<?= BASE_URL ?>controllers/adminController/view_docx.php?path=uploads/forms/<?= basename($row['file_located']) ?>">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="<?= BASE_URL ?>controllers/adminController/formControllers.php?download=<?= $row['id'] ?>" class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-download"></i> Download
                                        </a>
                                        <a href="#" data-id="<?= $row['id'] ?>" class="text-red-600 hover:text-red-800 delete-btn">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </section>

            </div>
        </div>
    </main>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Delete functionality
    const deleteLinks = document.querySelectorAll('.delete-btn');

    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); 
            const fileId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This file will be deleted permanently.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `<?= BASE_URL ?>controllers/adminController/formControllers.php?delete=${fileId}`;
                }
            });
        });
    });

    // View functionality
    const viewButtons = document.querySelectorAll('.view-btn');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const path = this.getAttribute('data-path');
            const filename = this.getAttribute('data-filename');
            
            Swal.fire({
                title: filename,
                width: '80%',
                height: '80%',
                showCloseButton: true,
                showConfirmButton: false,
                html: `<iframe src="${path}" frameborder="0" style="width: 100%; height: 80vh;"></iframe>`,
                customClass: {
                    container: 'swal-height'
                }
            });
        });
    });
});
</script>

<style>
.swal-height {
    height: 90vh;
}

.swal2-popup {
    padding: 0;
}

.swal2-content {
    padding: 0;
    margin: 0;
}

.swal2-title {
    padding: 1rem;
    margin: 0;
    border-bottom: 1px solid #e2e8f0;
}
</style>


<script>
const fileInput = document.getElementById('file');
const previewBox = document.getElementById('file-preview');
const fileNameSpan = document.getElementById('file-name');
const removeBtn = document.getElementById('remove-file');

fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        fileNameSpan.textContent = file.name;
        previewBox.classList.remove('hidden');
    } else {
        fileNameSpan.textContent = '';
        previewBox.classList.add('hidden');
    }
});

removeBtn.addEventListener('click', function() {
    fileInput.value = '';
    fileNameSpan.textContent = '';
    previewBox.classList.add('hidden');
});
</script>


<?php
include '../../includes/footer.php';
?>