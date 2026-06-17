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

    <div class="table-wrap">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th>ຊື່ຜູ້ສະໜອງ</th>
                        <th>ຜູ້ຕິດຕໍ່</th>
                        <th>ເບີໂທ</th>
                        <th>ອີເມວ</th>
                        <th>ທີ່ຢູ່</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($suppliers)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <p class="empty-state-title">ຍັງບໍ່ມີຜູ້ສະໜອງ</p>
                                <p class="empty-state-desc">ຜູ້ສະໜອງຈະສະແດງຢູ່ນີ້</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($suppliers as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s['name']) ?></td>
                        <td><?= htmlspecialchars($s['contact_person'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($s['phone'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($s['email'] ?? '-') ?></td>
                        <td class="max-w-[200px] truncate"><?= htmlspecialchars($s['address'] ?? '-') ?></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="<?= url('/suppliers/' . $s['id'] . '/edit') ?>" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="<?= url('/suppliers/' . $s['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
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
