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
                            <span
                                class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Transactions</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="p-4 rounded-lg dark:border-gray-700">
                <section>
                    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                        <div
                            class="relative isolate overflow-hidden bg-white px-6 py-20 text-center sm:px-16 sm:shadow-sm">
                            <p class="mx-auto max-w-2xl text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                                Search applicant to make payments
                            </p>
                            <form action="<?= BASE_URL?>controllers/adminController/searchController.php" method="get">
                                <label
                                    class="mx-auto mt-8 relative bg-white min-w-sm max-w-2xl flex flex-col md:flex-row items-center justify-center border py-2 px-2 rounded-2xl gap-2 shadow-2xl focus-within:border-gray-300"
                                    for="search-bar">

                                    <input id="search-bar" placeholder="your keyword here" name="q"
                                        class="px-6 py-2 w-full rounded-md flex-1 outline-none bg-white" required="">
                                    <button type="submit"
                                        class="w-full md:w-auto px-6 py-3 bg-black border-black text-white fill-white active:scale-95 duration-100 border will-change-transform overflow-hidden relative rounded-xl transition-all">
                                        <div class="flex items-center transition-all opacity-1">
                                            <span class="text-sm font-semibold whitespace-nowrap truncate mx-auto">
                                                Search
                                            </span>
                                        </div>
                                    </button>
                                </label>
                            </form>

                            <svg viewBox="0 0 1024 1024"
                                class="absolute left-1/2 top-1/2 -z-10 h-[64rem] w-[64rem] -translate-x-1/2 [mask-image:radial-gradient(closest-side,white,transparent)]"
                                aria-hidden="true">
                                <circle cx="512" cy="512" r="512" fill="url(#827591b1-ce8c-4110-b064-7cb85a0b1217)"
                                    fill-opacity="0.7">
                                </circle>
                                <defs>
                                    <radialGradient id="827591b1-ce8c-4110-b064-7cb85a0b1217">
                                        <stop stop-color="#3b82f6"></stop>
                                        <stop offset="1" stop-color="#1d4ed8"></stop>
                                    </radialGradient>
                                </defs>
                            </svg>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
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
});
</script>


<?php
include '../../includes/footer.php';
?>