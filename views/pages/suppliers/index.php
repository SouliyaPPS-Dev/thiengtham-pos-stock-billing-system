<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ຜູ້ສະໜອງ</h1>
            <p class="text-sm text-gray-500">ຈັດການຂໍ້ມູນຜູ້ສະໜອງສິນຄ້າ</p>
        </div>
        <a href="<?= url('/suppliers/create') ?>" class="bg-primary text-white rounded-xl px-4 py-2 font-bold hover:opacity-90 inline-flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>ເພີ່ມຜູ້ສະໜອງ</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl border p-4 md:p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່ຜູ້ສະໜອງ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຜູ້ຕິດຕໍ່</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ເບີໂທ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ອີເມວ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ທີ່ຢູ່</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($suppliers)): ?>
                    <tr>
                        <td colspan="6" class="py-12 text-center text-gray-400">
                            <i class="fas fa-truck text-3xl mb-2 block"></i>
                            <span>ຍັງບໍ່ມີຜູ້ສະໜອງ</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($suppliers as $s): ?>
                    <tr class="border-b last:border-0 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($s['name']) ?></td>
                        <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($s['contact_person'] ?? '-') ?></td>
                        <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($s['phone'] ?? '-') ?></td>
                        <td class="py-3 px-2 text-gray-500 text-xs"><?= htmlspecialchars($s['email'] ?? '-') ?></td>
                        <td class="py-3 px-2 text-gray-500 text-xs max-w-[200px] truncate"><?= htmlspecialchars($s['address'] ?? '-') ?></td>
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-1">
                                <a href="<?= url('/suppliers/' . $s['id'] . '/edit') ?>" class="h-8 w-8 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center" title="ແກ້ໄຂ">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="<?= url('/suppliers/' . $s['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="h-8 w-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center" title="ລຶບ">
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

<script>
function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບຜູ້ສະໜອງນີ້ແທ້ບໍ່?',
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
