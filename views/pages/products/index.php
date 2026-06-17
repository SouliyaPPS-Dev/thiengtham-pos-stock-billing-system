<div class="p-4 md:p-6 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">ສິນຄ້າ</h1>
            <p class="text-sm text-gray-500">ຈັດການຂໍ້ມູນສິນຄ້າທັງໝົດ</p>
        </div>
        <a href="<?= url('/products/create') ?>" class="bg-primary text-white rounded-xl px-4 py-2 font-bold hover:opacity-90 inline-flex items-center gap-2">
            <i class="fas fa-plus"></i>
            <span>ເພີ່ມສິນຄ້າ</span>
        </a>
    </div>

    <div class="table-wrap">
        <form method="GET" action="<?= url('/products') ?>" class="flex flex-col sm:flex-row gap-3 mb-4">
            <div class="flex-1 relative">
                <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາສິນຄ້າ..." class="w-full pl-10 pr-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
            </div>
            <div>
                <select name="category_id" class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                    <option value="">ທຸກໝວດ</option>
                    <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (($_GET['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-primary text-white rounded-xl px-4 py-2.5 font-bold hover:opacity-90 text-sm">
                <i class="fas fa-filter"></i> ຄົ້ນຫາ
            </button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th>ຮູບ</th>
                        <th>ຊື່ສິນຄ້າ</th>
                        <th>SKU</th>
                        <th>ໝວດ</th>
                        <th>ລາຄາຂາຍ</th>
                        <th>ສະຕ໋ອກ</th>
                        <th>ສະຖານະ</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <p class="empty-state-title">ຍັງບໍ່ມີສິນຄ້າ</p>
                                <p class="empty-state-desc">ເພີ່ມສິນຄ້າຊະນິດທຳອິດ</p>
                            </div>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 overflow-hidden">
                                <?php if (!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>" class="h-full w-full object-cover" alt="">
                                <?php else: ?>
                                <i class="fas fa-box text-sm"></i>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($p['name']) ?></td>
                        <td><?= htmlspecialchars($p['sku'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                        <td><?= number_format($p['selling_price'], 0) ?> ກີບ</td>
                        <td>
                            <?php
                            $stock = (int)($p['stock'] ?? 0);
                            $color = $stock === 0 ? 'status-badge-red' : ($stock <= 10 ? 'status-badge-amber' : 'status-badge-green');
                            ?>
                            <span class="status-badge <?= $color ?>">
                                <i class="fas fa-cubes text-[10px]"></i>
                                <?= $stock ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($p['status'] === 'active'): ?>
                            <span class="status-badge status-badge-green"><span class="dot"></span><i class="fas fa-circle text-[6px]"></i> ເປີດໃຊ້</span>
                            <?php else: ?>
                            <span class="status-badge status-badge-gray"><span class="dot"></span><i class="fas fa-circle text-[6px]"></i> ປິດໃຊ້</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="<?= url('/products/' . $p['id'] . '/edit') ?>" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="<?= url('/products/' . $p['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
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
        text: 'ຕ້ອງການລຶບຂໍ້ມູນນີ້ແທ້ບໍ່? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້',
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
