<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex items-center gap-4">
            <a href="<?= url('/admin') ?>" class="h-10 w-10 rounded-xl bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-all">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ໝວດສິນຄ້າ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ຈັດການໝວດສິນຄ້າ</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-tags text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ເພີ່ມໝວດໃໝ່</h2>
                            <p class="text-xs text-gray-400">ສ້າງໝວດສິນຄ້າໃໝ່</p>
                        </div>
                    </div>
                    <form action="<?= url('/admin/categories/store') ?>" method="POST" class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-tag text-[10px] text-violet-500"></i>
                                ຊື່ໝວດ <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm" placeholder="ຊື່ໝວດສິນຄ້າ">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                                <i class="fas fa-align-left text-[10px] text-violet-500"></i>
                                ລາຍລະອຽດ
                            </label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm resize-none" placeholder="ລາຍລະອຽດໝວດສິນຄ້າ"></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-500 to-violet-600 text-white rounded-xl font-bold text-sm hover:from-violet-600 hover:to-violet-700 transition-all shadow-lg shadow-violet-200 active:scale-[0.97]">
                            <i class="fas fa-save"></i>
                            ບັນທຶກ
                        </button>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
                        <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                            <i class="fas fa-list text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-extrabold text-gray-800">ລາຍການໝວດສິນຄ້າ</h2>
                            <p class="text-xs text-gray-400">ຈັດການໝວດສິນຄ້າທັງໝົດ</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່ໝວດ</th>
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຍລະອຽດ</th>
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຈຳນວນສິນຄ້າ</th>
                                    <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categories)): ?>
                                <tr>
                                    <td colspan="4" class="py-3 px-2">
                                        <div class="flex flex-col items-center justify-center py-12 text-center">
                                            <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                                <i class="fas fa-tags text-2xl"></i>
                                            </div>
                                            <p class="text-base font-bold text-gray-600">ຍັງບໍ່ມີໝວດສິນຄ້າ</p>
                                            <p class="text-sm text-gray-400 mt-1">ໝວດສິນຄ້າຈະສະແດງຢູ່ນີ້</p>
                                        </div>
                                    </td>
                                </tr>
                                <?php else: ?>
                                <?php foreach ($categories as $cat): ?>
                                <tr class="border-b border-gray-50 last:border-0">
                                    <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($cat['name']) ?></td>
                                    <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($cat['description'] ?? '-') ?></td>
                                    <td class="py-3 px-2 text-gray-600"><?= (int)($cat['product_count'] ?? 0) ?></td>
                                    <td class="py-3 px-2">
                                        <div class="flex items-center gap-1">
                                            <button onclick="editCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($cat['description'] ?? '', ENT_QUOTES) ?>')" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>
                                            <a href="<?= url('/admin/categories/' . $cat['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
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
    </div>
</div>

<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="closeEditModal(event)">
    <div class="bg-white rounded-2xl shadow-xl p-6 md:p-8 w-full max-w-md" onclick="event.stopPropagation()">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-50">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-violet-400 to-violet-500 flex items-center justify-center text-white shadow-lg shadow-violet-200">
                <i class="fas fa-pen text-sm"></i>
            </div>
            <div>
                <h3 class="text-base font-extrabold text-gray-800">ແກ້ໄຂໝວດສິນຄ້າ</h3>
                <p class="text-xs text-gray-400">ແກ້ໄຂຂໍ້ມູນໝວດສິນຄ້າ</p>
            </div>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                    <i class="fas fa-tag text-[10px] text-violet-500"></i>
                    ຊື່ໝວດ <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700 flex items-center gap-1.5">
                    <i class="fas fa-align-left text-[10px] text-violet-500"></i>
                    ລາຍລະອຽດ
                </label>
                <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-violet-500/20 focus:border-violet-500 outline-none text-sm resize-none"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-violet-500 to-violet-600 text-white rounded-xl font-bold text-sm hover:from-violet-600 hover:to-violet-700 transition-all shadow-lg shadow-violet-200 active:scale-[0.97]">
                    <i class="fas fa-save"></i> ບັນທຶກຂໍ້ມູນ
                </button>
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all">
                    <i class="fas fa-times"></i> ຍົກເລີກ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editCategory(id, name, description) {
    document.getElementById('editName').value = name;
    document.getElementById('editDescription').value = description;
    document.getElementById('editForm').action = '<?= url('/admin/categories') ?>/' + id + '/update';
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal(event) {
    if (event.target === event.currentTarget) {
        document.getElementById('editModal').classList.add('hidden');
    }
}

function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບໝວດສິນຄ້ານີ້ແທ້ບໍ່?',
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
