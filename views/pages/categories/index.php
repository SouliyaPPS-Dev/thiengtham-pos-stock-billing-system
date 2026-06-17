<div class="p-4 md:p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">ໝວດສິນຄ້າ</h1>
        <p class="text-sm text-gray-500">ຈັດການໝວດສິນຄ້າ</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-2xl border p-4 md:p-6 lg:col-span-1">
            <h2 class="font-bold text-gray-800 mb-4">ເພີ່ມໝວດໃໝ່</h2>
            <form action="<?= url('/categories/store') ?>" method="POST" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-700">ຊື່ໝວດ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ຊື່ໝວດສິນຄ້າ">
                </div>
                <div class="space-y-1.5">
                    <label class="text-sm font-bold text-gray-700">ລາຍລະອຽດ</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none" placeholder="ລາຍລະອຽດໝວດສິນຄ້າ"></textarea>
                </div>
                <button type="submit" class="bg-primary text-white rounded-xl px-4 py-2.5 font-bold hover:opacity-90 w-full inline-flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກ</span>
                </button>
            </form>
        </div>

        <div class="bg-white rounded-2xl border p-4 md:p-6 lg:col-span-2">
            <h2 class="font-bold text-gray-800 mb-4">ລາຍການໝວດສິນຄ້າ</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່ໝວດ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຍລະອຽດ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຈຳນວນສິນຄ້າ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                        <tr>
                            <td colspan="4" class="py-12 text-center text-gray-400">
                                <i class="fas fa-tags text-3xl mb-2 block"></i>
                                <span>ຍັງບໍ່ມີໝວດສິນຄ້າ</span>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                        <tr class="border-b last:border-0 hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($cat['name']) ?></td>
                            <td class="py-3 px-2 text-gray-500"><?= htmlspecialchars($cat['description'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-gray-500"><?= (int)($cat['product_count'] ?? 0) ?></td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1">
                                    <button onclick="editCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($cat['description'] ?? '', ENT_QUOTES) ?>')" class="h-8 w-8 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center" title="ແກ້ໄຂ">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <a href="<?= url('/categories/' . $cat['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="h-8 w-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center" title="ລຶບ">
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

<div id="editModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4" onclick="closeEditModal(event)">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md" onclick="event.stopPropagation()">
        <h3 class="font-bold text-gray-800 text-lg mb-4">ແກ້ໄຂໝວດສິນຄ້າ</h3>
        <form id="editForm" method="POST" class="space-y-4">
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ຊື່ໝວດ <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="editName" required class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none">
            </div>
            <div class="space-y-1.5">
                <label class="text-sm font-bold text-gray-700">ລາຍລະອຽດ</label>
                <textarea name="description" id="editDescription" rows="3" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="bg-primary text-white rounded-xl px-6 py-2.5 font-bold hover:opacity-90 inline-flex items-center gap-2">
                    <i class="fas fa-save"></i> ບັນທຶກຂໍ້ມູນ
                </button>
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="bg-gray-100 text-gray-600 rounded-xl px-6 py-2.5 font-bold hover:bg-gray-200 inline-flex items-center gap-2">
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
    document.getElementById('editForm').action = '<?= url('/categories') ?>/' + id + '/update';
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
