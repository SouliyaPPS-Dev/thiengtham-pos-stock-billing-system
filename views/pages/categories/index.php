<div class="p-4 md:p-6 space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">ໝວດສິນຄ້າ</h1>
        <p class="text-sm text-gray-500">ຈັດການໝວດສິນຄ້າ</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="table-wrap lg:col-span-1">
            <h2 class="font-bold text-gray-800 mb-4">ເພີ່ມໝວດໃໝ່</h2>
            <form action="<?= url('/categories/store') ?>" method="POST" class="space-y-4">
                <div class="space-y-1.5">
                    <label class="form-label">ຊື່ໝວດ <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="form-input" placeholder="ຊື່ໝວດສິນຄ້າ">
                </div>
                <div class="space-y-1.5">
                    <label class="form-label">ລາຍລະອຽດ</label>
                    <textarea name="description" rows="3" class="form-input" placeholder="ລາຍລະອຽດໝວດສິນຄ້າ"></textarea>
                </div>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i>
                    <span>ບັນທຶກ</span>
                </button>
            </form>
        </div>

        <div class="table-wrap lg:col-span-2">
            <h2 class="font-bold text-gray-800 mb-4">ລາຍການໝວດສິນຄ້າ</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left">
                            <th>ຊື່ໝວດ</th>
                            <th>ລາຍລະອຽດ</th>
                            <th>ຈຳນວນສິນຄ້າ</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                        <tr>
                        <td colspan="4">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-tags"></i>
                                </div>
                                <p class="empty-state-title">ຍັງບໍ່ມີໝວດສິນຄ້າ</p>
                                <p class="empty-state-desc">ໝວດສິນຄ້າຈະສະແດງຢູ່ນີ້</p>
                            </div>
                        </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($categories as $cat): ?>
                        <tr>
                            <td><?= htmlspecialchars($cat['name']) ?></td>
                            <td><?= htmlspecialchars($cat['description'] ?? '-') ?></td>
                            <td><?= (int)($cat['product_count'] ?? 0) ?></td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <button onclick="editCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name'], ENT_QUOTES) ?>', '<?= htmlspecialchars($cat['description'] ?? '', ENT_QUOTES) ?>')" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                        <i class="fas fa-pen text-xs"></i>
                                    </button>
                                    <a href="<?= url('/categories/' . $cat['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
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
                <label class="form-label">ຊື່ໝວດ <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="editName" required class="form-input">
            </div>
            <div class="space-y-1.5">
                <label class="form-label">ລາຍລະອຽດ</label>
                <textarea name="description" id="editDescription" rows="3" class="form-input"></textarea>
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save"></i> ບັນທຶກຂໍ້ມູນ
                </button>
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="btn-secondary">
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
