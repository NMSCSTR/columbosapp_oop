<?php
require_once '../../middleware/auth.php';
authorize(['admin']);
require_once '../../models/adminModel/activityLogsModel.php';

$conn = mysqli_connect('localhost', 'root', '', 'columbos');

$logModel = new activityLogsModel($conn);
$logs = $logModel->getAllLogs();

mysqli_close($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-indigo': '#4f46e5', 
                        'primary-hover': '#4338ca', /
                        'dashboard-bg': '#f3f4f6', 
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f3f4f6;
        }
        .dataTables_wrapper { @apply p-4; }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            @apply px-3 py-1 mx-1 rounded-md border text-sm font-medium transition-all duration-200;
            @apply border-gray-300 bg-white text-gray-700;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            @apply bg-gray-100 text-gray-900 border-gray-400;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current, 
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            @apply bg-primary-indigo border-primary-indigo text-white shadow-md; 
        }
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            @apply border border-gray-300 p-2 rounded-lg text-sm text-gray-700 shadow-sm focus:ring-primary-indigo focus:border-primary-indigo;
        }
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_paginate {
            @apply pt-3 pb-3 px-0;
        }

        #activityLogTable thead th {
            @apply bg-primary-indigo text-white font-semibold;
            border-bottom: none;
        }
        #activityLogTable th, #activityLogTable td {
            @apply px-4 py-3;
        }
        .short-column {
             max-width: 150px; 
        }
        

        .badge-create { @apply bg-green-100 text-green-800 font-medium px-2 py-0.5 rounded-full text-xs; }
        .badge-update { @apply bg-blue-100 text-blue-800 font-medium px-2 py-0.5 rounded-full text-xs; }
        .badge-delete { @apply bg-red-100 text-red-800 font-medium px-2 py-0.5 rounded-full text-xs; }
        .badge-login { @apply bg-yellow-100 text-yellow-800 font-medium px-2 py-0.5 rounded-full text-xs; }
        .badge-other { @apply bg-gray-100 text-gray-800 font-medium px-2 py-0.5 rounded-full text-xs; }

        #logModal {
            position: fixed !important; 
            top: 0 !important;
            left: 0 !important;
            z-index: 5000 !important; 
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
        }
        .modal-active {
            opacity: 1 !important;
            pointer-events: auto !important;
        }

    </style>
</head>
<body>

    <div class="container mx-auto p-4 sm:p-8">
        <header class="mb-6 p-6 bg-white rounded-xl shadow-lg border border-gray-200">
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-indigo" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">System Activity Logs</h1>
                    <p class="text-gray-500 text-sm mt-0.5">All administrative actions.</p>
                </div>
            </div>
        </header>

        <div class="bg-white rounded-xl shadow-2xl border border-gray-200">
            
            <div class="overflow-x-auto p-4"> 
                <table id="activityLogTable" class="min-w-full divide-y divide-gray-200 border-separate" style="border-spacing: 0;">
                    <thead class="bg-primary-indigo rounded-t-xl">
                        <tr>
                            <th class="py-3 text-left text-xs font-semibold uppercase tracking-wider rounded-tl-xl short-column">ID</th>
                            <th class="py-3 text-left text-xs font-semibold uppercase tracking-wider">Timestamp</th>
                            <th class="py-3 text-left text-xs font-semibold uppercase tracking-wider">Admin</th>
                            <th class="py-3 text-left text-xs font-semibold uppercase tracking-wider short-column">Action Type</th>
                            <th class="py-3 text-left text-xs font-semibold uppercase tracking-wider short-column">Entity Type</th>
                            <th class="py-3 text-left text-xs font-semibold uppercase tracking-wider">Details</th>
                            <th class="py-3 text-left text-xs font-semibold uppercase tracking-wider rounded-tr-xl">Changes</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        <?php if (count($logs) > 0): ?>
                            <?php foreach ($logs as $log): ?>
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="py-3 text-sm font-medium text-gray-900 short-column"><?= htmlspecialchars($log['log_id']) ?></td>
                                    <td class="py-3 text-sm text-gray-500 min-w-[150px]"><?= date('M d, Y H:i:s', strtotime(htmlspecialchars($log['timestamp']))) ?></td>
                                    
                                    <td class="py-3 text-sm text-gray-700 font-medium short-column">
                                        <?php 
                                            $adminName = trim(htmlspecialchars(strtoupper($log['firstname'] . ' ' . $log['lastname'])));
                                            echo empty($adminName) ? '<span class="text-xs text-gray-400">ID: ' . htmlspecialchars($log['admin_id']) . '</span>' : $adminName;
                                        ?>
                                    </td>
                                    
                                    <td class="py-3 text-sm font-medium short-column">
                                        <?php 
                                            $actionType = htmlspecialchars($log['action_type']);
                                            $badgeClass = '';
                                            switch (strtolower($actionType)) {
                                                case 'create':
                                                case 'insert': $badgeClass = 'badge-create'; break;
                                                case 'update':
                                                case 'edit': $badgeClass = 'badge-update'; break;
                                                case 'delete':
                                                case 'remove': $badgeClass = 'badge-delete'; break;
                                                case 'login':
                                                case 'logout': $badgeClass = 'badge-login'; break;
                                                default: $badgeClass = 'badge-other'; break;
                                            }
                                        ?>
                                        <span class="<?= $badgeClass ?>"><?= $actionType ?></span>
                                    </td>
                                    
                                    <td class="py-3 text-sm text-gray-500 font-medium short-column"><?= htmlspecialchars(strtoupper($log['entity_type'])) ?></td>
                                    
                                    <td class="py-3 text-sm text-gray-600">
                                        <?php if (!empty($log['action_details'])): 
                                            $dataContentSafe = htmlspecialchars($log['action_details'], ENT_QUOTES, 'UTF-8');
                                        ?>
                                            <button 
                                                class="show-modal-btn text-xs font-semibold text-primary-indigo hover:text-primary-hover underline focus:outline-none"
                                                data-title="Action Details (ID: <?= htmlspecialchars($log['log_id']) ?>)"
                                                data-content='<?= $dataContentSafe ?>'
                                            >
                                                See Details
                                            </button>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                    
                                    <td class="py-3 text-sm text-gray-500">
                                        <?php 
                                            $old = htmlspecialchars($log['old_value']);
                                            $new = htmlspecialchars($log['new_value']);
                                            
                                            if (!empty($old) || !empty($new)):
                                                // Build the modal HTML content
                                                $changesContent = '';
                                                $changesContent .= '<p class="mb-2"><span class="font-semibold text-gray-800">Old Value:</span><br><code class="block bg-gray-100 p-2 rounded text-xs whitespace-pre-wrap">' . (empty($old) ? 'N/A' : $old) . '</code></p>';
                                                $changesContent .= '<p><span class="font-semibold text-gray-800">New Value:</span><br><code class="block bg-gray-100 p-2 rounded text-xs whitespace-pre-wrap">' . (empty($new) ? 'N/A' : $new) . '</code></p>';

                                                $dataContentSafe = htmlspecialchars($changesContent, ENT_QUOTES, 'UTF-8');
                                        ?>
                                            <button 
                                                class="show-modal-btn text-xs font-semibold text-primary-indigo hover:text-primary-hover underline focus:outline-none"
                                                data-title="Changes (ID: <?= htmlspecialchars($log['log_id']) ?>)"
                                                data-content='<?= $dataContentSafe ?>'
                                            >
                                                See Changes
                                            </button>
                                        <?php else: ?>
                                            N/A
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-sm text-gray-500">No activity logs found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
        
        <footer class="mt-8 text-center text-xs text-gray-400">
            
        </footer>
    </div>


    <div id="logModal" class="modal fixed w-full h-full top-0 left-0 flex items-center justify-center opacity-0 pointer-events-none z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
        
        <div class="modal-container bg-white w-11/12 md:max-w-xl mx-auto rounded-xl shadow-2xl z-50 overflow-y-auto">
            <div class="modal-content py-4 text-left px-6">
                <div class="flex justify-between items-center pb-3">
                    <p class="text-xl font-bold text-gray-800" id="modalTitle">Full Content</p>
                    <button class="modal-close cursor-pointer z-50 text-gray-500 hover:text-gray-700">
                        <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z"></path>
                        </svg>
                    </button>
                </div>

                <div class="my-5 p-4 bg-gray-50 rounded-lg max-h-96 overflow-y-auto border border-gray-200">
                    <div id="modalContent" class="text-sm text-gray-700 whitespace-pre-wrap break-words">
                        </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button class="modal-close px-4 py-2 bg-primary-indigo text-white font-semibold rounded-lg hover:bg-primary-hover transition duration-200">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.min.js"></script>
    
<script>
    $(document).ready(function() {

        var dataTable = $('#activityLogTable').DataTable({
            language: {
                search: "Filter Records:",
                lengthMenu: "Show _MENU_ logs per page",
                info: "Showing _START_ to _END_ of _TOTAL_ logs"
            },
            order: [[1, 'desc']], 
            responsive: true
        });

 
        const modal = $('#logModal');
        const modalOverlay = $('.modal-overlay');
        const modalCloseButtons = $('.modal-close');
        const modalContentDiv = $('#modalContent');
        const modalTitleDiv = $('#modalTitle');

        function toggleModal(title, content) {
            console.log("DEBUG: Attempting to show modal with title:", title); 
            modalTitleDiv.text(title);
 
            modalContentDiv.html(content); 
            modal.addClass('modal-active');
        }

        function hideModal() {
            modal.removeClass('modal-active');
            setTimeout(() => {
                modalContentDiv.empty();
                modalTitleDiv.text('');
            }, 300); 
        }


        $(document).on('click', '.show-modal-btn', function() {
            const $button = $(this);
            const title = $button.data('title');
            

            const escapedContent = $button.attr('data-content');
            
            if (!escapedContent) {
                console.error("DEBUG ERROR: data-content attribute is missing or empty.");
                toggleModal(title, "Content data not found.");
                return;
            }

            const content = $("<textarea/>").html(escapedContent).text();
            
            console.log("DEBUG: Content successfully processed. Calling toggleModal.");
            
            toggleModal(title, content);
        });


        modalOverlay.on('click', hideModal);
        modalCloseButtons.on('click', hideModal);

 
        $(document).on('keydown', function(event) {
            if (event.key === "Escape") {
                hideModal();
            }
        });

    });
</script>

</body>
</html>