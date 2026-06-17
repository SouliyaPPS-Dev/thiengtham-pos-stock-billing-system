<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ລູກຄ້າ</h1>
            <p class="text-sm text-gray-500">ຈັດການຂໍ້ມູນລູກຄ້າ</p>
        </div>
        <a href="<?= url('/customers/create') ?>" class="bg-primary text-white rounded-xl px-4 py-2 font-bold hover:opacity-90 inline-flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>ເພີ່ມລູກຄ້າ</span>
        </a>
    </div>

    <div class="table-wrap">
        <form method="GET" action="<?= url('/customers') ?>" class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາຊື່ ຫຼື ເບີໂທ..." class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
            </div>
            <button type="submit" class="bg-primary text-white rounded-xl px-4 py-2.5 font-bold hover:opacity-90 text-sm">
                <i class="fas fa-search"></i> ຄົ້ນຫາ
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left">
                        <th>ຊື່</th>
                        <th>ເບີໂທ</th>
                        <th>ອີເມວ</th>
                        <th>ຍອດຊື້ທັງໝົດ</th>
                        <th>ວັນທີສ້າງ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($customers)): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <p class="empty-state-title">ຍັງບໍ່ມີລູກຄ້າ</p>
                                <p class="empty-state-desc">ລູກຄ້າຈະສະແດງຢູ່ນີ້</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($customers as $c): ?>
                    <tr>
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-bold text-xs">
                                    <?= mb_substr($c['name'], 0, 1) ?>
                                </div>
                                <span class="font-medium text-gray-800"><?= htmlspecialchars($c['name']) ?></span>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($c['phone'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($c['email'] ?? '-') ?></td>
                        <td><?= number_format($c['total_purchases'] ?? 0, 0) ?> ກີບ</td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($c['created_at']))) ?></td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="<?= url('/customers/' . $c['id'] . '/edit') ?>" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="<?= url('/customers/' . $c['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
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
