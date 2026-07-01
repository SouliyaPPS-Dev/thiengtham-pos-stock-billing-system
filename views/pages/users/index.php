<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ພະນັກງານ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ຈັດການຜູ້ໃຊ້ງານລະບົບ</p>
            </div>
            <a href="<?= url('/users/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                <i class="fas fa-plus"></i>
                <span>ເພີ່ມພະນັກງານ</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow p-6 md:p-8">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່ຜູ້ໃຊ້</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່ເຕັມ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ບົດບາດ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ວັນທີສ້າງ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                        <tr>
                            <td colspan="7" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-users-cog text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-gray-600">ຍັງບໍ່ມີພະນັກງານ</p>
                                    <p class="text-sm text-gray-400 mt-1">ພະນັກງານຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($users as $u): $i++; ?>
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3 px-2 text-gray-400 text-sm text-center"><?= $i ?></td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                        <?= mb_substr($u['fullname'] ?? $u['username'], 0, 1) ?>
                                    </div>
                                    <span class="font-medium text-gray-800"><?= htmlspecialchars($u['username']) ?></span>
                                </div>
                            </td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($u['fullname'] ?? '-') ?></td>
                            <td class="py-3 px-2">
                                <?php if (($u['role'] ?? 'staff') === 'admin'): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-600 text-xs font-bold"><i class="fas fa-shield-alt text-[10px]"></i> Admin</span>
                                <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold"><i class="fas fa-user text-[10px]"></i> Staff</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2">
                                <?php if (($u['status'] ?? 'active') === 'active'): ?>
                                <span class="status-badge status-badge-green"><span class="dot"></span> ເປີດໃຊ້</span>
                                <?php else: ?>
                                <span class="status-badge status-badge-red"><span class="dot"></span> ປິດໃຊ້</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars(date('d/m/Y', strtotime($u['created_at']))) ?></td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1">
                                    <a href="<?= url('/users/' . $u['id'] . '/edit') ?>" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <a href="<?= url('/users/' . $u['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບພະນັກງານນີ້ແທ້ບໍ່?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'ລຶບ',
        cancelButtonText: 'ຍົກເລີກ',
        reverseButtons: true,
        customClass: { popup: 'rounded-3xl' }
    }).then((result) => {
        if (result.isConfirmed) window.location.href = url;
    });
}
</script>
