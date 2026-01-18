<?php
    require_once '../../middleware/auth.php';
    authorize(['admin']);
    include '../../includes/config.php';
    include '../../includes/db.php';
    include '../../includes/header.php';
    include '../../models/adminModel/announcementModel.php';
    include '../../includes/alert2.php';
    include '../../partials/breadcrumb.php';

    function getSemaphoreData($type = 'messages') {
        $apiKey = '5bf90b2585f02b48d22e01d79503e591'; 
        $ch = curl_init();
        
        $params = ['apikey' => $apiKey];
        if ($type === 'messages') {
            $params['limit'] = 100;
        }

        $endpoint = ($type === 'account') ? 'account' : 'messages';
        $url = "https://api.semaphore.co/api/v4/" . $endpoint . "?" . http_build_query($params);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($response, true);
        
        return $data ? $data : [];
    }

    $smsLogs = getSemaphoreData('messages');
    $accountInfo = getSemaphoreData('account');

    $model = new announcementModel($conn);
    $users = $model->getSelectableUsers();
    $announcements = $model->getAllAnnouncement();
?>
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.7);
        --glass-border: rgba(226, 232, 240, 0.8);
    }
    body { background-color: #f8fafc; }
    
    .announcement-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--glass-border);
    }
    .announcement-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6;
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    @keyframes fadeInSlide {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-list-item { animation: fadeInSlide 0.5s ease forwards; }
    
    .recipient-label {
        transition: all 0.2s ease;
    }
    .recipient-checkbox:checked + .recipient-label {
        background-color: #eff6ff;
        border-color: #3b82f6;
    }
</style>

<div class="flex flex-col md:flex-row min-h-screen">
    <?php include '../../partials/sidebar.php'?>
    
    <main class="flex-1 lg:ml-64 transition-all duration-300">
        <div class="p-6">
            <?php
                $breadcrumbItems = [
                    ['title' => 'Admin Dashboard', 'url' => 'dashboard.php'],
                    ['title' => 'Communications', 'url' => '#'],
                    ['title' => 'Announcements']
                ];
                renderBreadcrumb($breadcrumbItems);
            ?>

            <div class="container mx-auto mt-6">
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <div class="lg:col-span-8 space-y-6">
                        <div class="flex items-center justify-between bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                            <div>
                                <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Announcements</h1>
                                <p class="text-sm text-slate-500">Manage public updates and SMS broadcasts</p>
                            </div>
                            <button onclick="toggleSmsModal()" class="flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl hover:bg-slate-800 transition-all shadow-md active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <span class="font-semibold text-sm">SMS Logs</span>
                            </button>
                        </div>

                        <div class="space-y-4 custom-scrollbar max-h-[75vh] overflow-y-auto pr-2">
                            <?php if (empty($announcements)): ?>
                                <div class="bg-white rounded-2xl p-20 text-center border-2 border-dashed border-slate-200">
                                    <div class="bg-slate-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">No announcements published yet.</p>
                                </div>
                            <?php endif; ?>
                            
                            <?php foreach ($announcements as $index => $announcement): ?>
                            <div class="announcement-card bg-white rounded-2xl p-6 animate-list-item" style="animation-delay: <?= $index * 0.1 ?>s">
                                <div class="flex justify-between items-start">
                                    <div class="flex gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-slate-900 text-lg leading-tight"><?= htmlspecialchars($announcement['subject']) ?></h3>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 bg-slate-100 px-2 py-0.5 rounded">Admin</span>
                                                <span class="text-slate-300">•</span>
                                                <span class="text-xs text-slate-500 font-medium"><?= date("M d, Y • g:i A", strtotime($announcement['date_posted'])) ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="relative group">
                                        <button class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-lg transition-colors">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" /></svg>
                                        </button>
                                        <div class="hidden group-hover:block absolute right-0 w-40 bg-white shadow-xl border border-slate-100 rounded-xl py-2 z-20">
                                            <button class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 copy-announcement">Copy Text</button>
                                            <hr class="my-1 border-slate-50">
                                            <button class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 delete-announcement font-semibold" data-id="<?= $announcement['id'] ?>">Delete</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 text-slate-600 text-sm leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-100 italic">
                                    "<?= nl2br(htmlspecialchars($announcement['content'])) ?>"
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="lg:col-span-4">
                        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-200 p-8 sticky top-6">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <h2 class="text-xl font-black text-slate-800 tracking-tight">Compose</h2>
                            </div>

                            <form id="announcementForm" action="<?php echo BASE_URL ?>controllers/adminController/addAnnouncementController.php" method="POST" class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Subject</label>
                                    <input type="text" name="subject" id="subject" class="w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all text-sm font-medium" placeholder="e.g. System Maintenance" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Message Content</label>
                                    <textarea name="announcement" id="announcement" rows="5" class="w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-4 focus:ring-blue-100 focus:border-blue-500 transition-all text-sm resize-none" placeholder="Type your message here..." required></textarea>
                                    <div class="flex justify-between mt-2">
                                        <span class="text-[10px] text-slate-400 font-bold uppercase">Min 30 Chars</span>
                                        <span class="character-count text-[10px] font-black text-slate-400">0 CHARACTERS</span>
                                    </div>
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-3">
                                        <label class="text-xs font-bold text-slate-500 uppercase tracking-widest">Recipients</label>
                                        <div class="flex gap-3">
                                            <button type="button" onclick="selectAll()" class="text-blue-600 text-[10px] font-black uppercase hover:underline">Select All</button>
                                            <button type="button" onclick="deselectAll()" class="text-slate-400 text-[10px] font-black uppercase hover:underline">Clear</button>
                                        </div>
                                    </div>
                                    <div class="max-h-56 overflow-y-auto custom-scrollbar pr-1 grid grid-cols-1 gap-2">
                                        <?php if (!empty($users)): foreach ($users as $user): ?>
                                            <div class="relative">
                                                <input type="checkbox" name="recipients[]" id="user_<?= $user['id'] ?>" value="<?= $user['id'] ?>" class="hidden recipient-checkbox">
                                                <label for="user_<?= $user['id'] ?>" class="recipient-label flex items-center p-3 bg-white border border-slate-100 rounded-xl cursor-pointer hover:bg-slate-50 transition-all">
                                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-bold text-slate-500 mr-3 uppercase">
                                                        <?= substr($user['firstname'],0,1).substr($user['lastname'],0,1) ?>
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-800"><?= htmlspecialchars($user['firstname'] . ' ' . $user['lastname']) ?></p>
                                                        <p class="text-[10px] text-slate-400 font-mono"><?= htmlspecialchars($user['phone_number']) ?></p>
                                                    </div>
                                                </label>
                                            </div>
                                        <?php endforeach; else: ?>
                                            <div class="text-center py-6 bg-slate-50 rounded-xl">
                                                <p class="text-xs text-slate-400 font-medium">No active users found.</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-700 hover:shadow-xl hover:shadow-blue-200 transition-all active:scale-[0.98]">
                                    Publish Announcement
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div id="smsLogsModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity"></div>
    <div class="flex items-center justify-center min-h-screen p-4 relative">
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden flex flex-col border border-white/20">
            <div class="px-10 py-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">SMS Transmission Logs</h3>
                    <div class="flex items-center gap-6 mt-2">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Account Name: <span class="text-slate-800"><?= htmlspecialchars($accountInfo['account_name'] ?? 'Knights') ?></span></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Status: <span class="text-slate-800"><?= htmlspecialchars($accountInfo['status'] ?? 'Active') ?></span></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-tighter">Credits: <span class="text-green-600"><?= htmlspecialchars($accountInfo['credit_balance'] ?? '0') ?> PHP</span></span>
                        </div>
                    </div>
                </div>
                <button onclick="toggleSmsModal()" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-slate-800 hover:border-slate-400 transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="px-10 py-6 overflow-y-auto custom-scrollbar flex-1 bg-white">
                <div class="rounded-3xl border border-slate-100 overflow-hidden shadow-inner bg-slate-50/30">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100/50">
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Recipient</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Message</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if (!empty($smsLogs)) : foreach ($smsLogs as $sms) : ?>
                                <tr class="hover:bg-white transition-colors group">
                                    <td class="px-6 py-5">
                                        <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors"><?= htmlspecialchars($sms['number'] ?? $sms['recipient'] ?? 'N/A') ?></span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-xs text-slate-500 line-clamp-1 italic max-w-xs">"<?= htmlspecialchars($sms['message']) ?>"</p>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <?php 
                                            $s = strtolower($sms['status']);
                                            $cls = ($s == 'sent') ? 'bg-green-50 text-green-600 border-green-100' : (($s == 'pending') ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-red-50 text-red-600 border-red-100');
                                        ?>
                                        <span class="inline-block px-3 py-1 rounded-lg border text-[10px] font-black uppercase tracking-tighter <?= $cls ?>">
                                            <?= htmlspecialchars($sms['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-[11px] font-medium text-slate-400"><?= date("M d, Y • h:i A", strtotime($sms['created_at'])) ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; else : ?>
                                <tr><td colspan="4" class="px-6 py-20 text-center text-slate-400 font-medium">No SMS history recorded.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="px-10 py-6 bg-slate-50/50 border-t border-slate-100 text-right">
                <button onclick="toggleSmsModal()" class="px-8 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-slate-100 transition-all">Dismiss History</button>
            </div>
        </div>
    </div>
</div>

<script>
function selectAll() { document.querySelectorAll('input[name="recipients[]"]').forEach(cb => cb.checked = true); }
function deselectAll() { document.querySelectorAll('input[name="recipients[]"]').forEach(cb => cb.checked = false); }

function toggleSmsModal() {
    const m = document.getElementById('smsLogsModal');
    m.classList.toggle('hidden');
    document.body.classList.toggle('overflow-hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const area = document.getElementById('announcement');
    const cnt = document.querySelector('.character-count');
    
    area.addEventListener('input', function() {
        const len = this.value.length;
        cnt.textContent = `${len} CHARACTERS`;
        cnt.className = `character-count text-[10px] font-black ${len < 30 ? 'text-rose-400' : 'text-emerald-500'}`;
    });

    document.getElementById('announcementForm').addEventListener('submit', function(e) {
        e.preventDefault();
        if (area.value.length < 30) {
            Swal.fire({ title: 'Notice', text: 'Messages require a minimum of 30 characters.', icon: 'info', confirmButtonColor: '#2563eb' });
            return;
        }

        const fd = new FormData(this);
        fetch(this.action, { method: 'POST', body: fd })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ title: 'Broadcasted!', text: 'Your announcement is now live.', icon: 'success' }).then(() => window.location.reload());
            } else {
                Swal.fire({ title: 'Error', text: data.message || 'Transmission failed.', icon: 'error' });
            }
        })
        .catch(() => window.location.reload());
    });

    document.querySelectorAll('.delete-announcement').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Confirm Deletion',
                text: "This will permanently remove this broadcast.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Yes, Delete'
            }).then((res) => { if (res.isConfirmed) window.location.href = `../../controllers/adminController/deleteAnnouncement.php?id=${id}`; });
        });
    });

    document.querySelectorAll('.copy-announcement').forEach(btn => {
        btn.addEventListener('click', function() {
            const txt = this.closest('.announcement-card').querySelector('.italic').textContent.replace(/"/g, '').trim();
            navigator.clipboard.writeText(txt).then(() => {
                const old = this.innerText;
                this.innerText = 'Copied!';
                setTimeout(() => this.innerText = old, 1500);
            });
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>