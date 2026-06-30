<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ລູກຄ້າ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ຈັດການຂໍ້ມູນລູກຄ້າ</p>
            </div>
            <div class="flex items-center gap-2">
                <select name="customer_type" onchange="window.location.href=this.value" class="px-3 py-2 border border-gray-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
                    <option value="<?= url('/admin/customers') ?>">ທຸກປະເພດ</option>
                    <option value="<?= url('/admin/customers?type=regular') ?>" <?= (($_GET['type'] ?? '') === 'regular') ? 'selected' : '' ?>>ທົ່ວໄປ</option>
                    <option value="<?= url('/admin/customers?type=wholesale') ?>" <?= (($_GET['type'] ?? '') === 'wholesale') ? 'selected' : '' ?>>ສົ່ງ</option>
                    <option value="<?= url('/admin/customers?type=vip') ?>" <?= (($_GET['type'] ?? '') === 'vip') ? 'selected' : '' ?>>VIP</option>
                </select>
                <a href="<?= url('/admin/customers/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                    <i class="fas fa-plus"></i>
                    <span>ເພີ່ມລູກຄ້າ</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/customers') ?>" class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex-1 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາຊື່ ຫຼື ເບີໂທ..." class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-50 text-sky-600 rounded-xl font-bold text-sm hover:bg-sky-100 transition-all">
                    <i class="fas fa-search"></i> ຄົ້ນຫາ
                </button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ເບີໂທ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ອີເມວ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ທີ່ຢູ່</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ວັນທີສ້າງ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($customers)): ?>
                        <tr>
                            <td colspan="6" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-users text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-gray-600">ຍັງບໍ່ມີລູກຄ້າ</p>
                                    <p class="text-sm text-gray-400 mt-1">ລູກຄ້າຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($customers as $c): ?>
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-2">
                                    <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                        <?= mb_substr($c['name'], 0, 1) ?>
                                    </div>
                                    <span class="font-medium text-gray-800"><?= htmlspecialchars($c['name']) ?></span>
                                </div>
                            </td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($c['phone'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($c['email'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-gray-600 max-w-[200px] truncate"><?= htmlspecialchars($c['address'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1">
                                    <button onclick="viewCustomer(<?= htmlspecialchars(json_encode($c), ENT_QUOTES) ?>)" class="icon-btn icon-btn-view" title="ເບິ່ງລາຍລະອຽດ">
                                        <i class="fas fa-eye text-xs"></i>
                                    </button>
                                    <a href="<?= url('/admin/customers/' . $c['id'] . '/edit') ?>" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <a href="<?= url('/admin/customers/' . $c['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
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

<div id="customerModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="closeModal(event)">
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 w-full max-w-lg" onclick="event.stopPropagation()">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                <i class="fas fa-user text-sm"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-gray-800" id="modalTitle">ລາຍລະອຽດລູກຄ້າ</h3>
            </div>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between py-2 border-b"><span class="text-sm text-gray-500">ຊື່</span><span class="text-sm font-bold text-gray-800" id="mName"></span></div>
            <div class="flex justify-between py-2 border-b"><span class="text-sm text-gray-500">ເບີໂທ</span><span class="text-sm font-bold text-gray-800" id="mPhone"></span></div>
            <div class="flex justify-between py-2 border-b"><span class="text-sm text-gray-500">ອີເມວ</span><span class="text-sm font-bold text-gray-800" id="mEmail"></span></div>
            <div class="flex justify-between py-2 border-b"><span class="text-sm text-gray-500">ທີ່ຢູ່</span><span class="text-sm font-bold text-gray-800" id="mAddress"></span></div>
            <div class="flex justify-between py-2"><span class="text-sm text-gray-500">ຫມາຍເຫດ</span><span class="text-sm font-bold text-gray-800" id="mNotes"></span></div>
        </div>
        <div class="mt-6 pt-4 border-t flex justify-end">
            <button onclick="document.getElementById('customerModal').classList.add('hidden')" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                <i class="fas fa-times"></i> ປິດ
            </button>
        </div>
    </div>
</div>

<script>
function viewCustomer(c) {
    document.getElementById('mName').textContent = c.name;
    document.getElementById('mPhone').textContent = c.phone || '-';
    document.getElementById('mEmail').textContent = c.email || '-';
    document.getElementById('mAddress').textContent = c.address || '-';
    document.getElementById('mNotes').textContent = c.notes || '-';
    document.getElementById('customerModal').classList.remove('hidden');
}

function closeModal(event) {
    if (event.target === event.currentTarget) {
        event.target.classList.add('hidden');
    }
}

function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບຂໍ້ມູນລູກຄ້ານີ້ແທ້ບໍ່?',
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
