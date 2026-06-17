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

    <div class="bg-white rounded-2xl border p-4 md:p-6">
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
                    <tr class="border-b text-left">
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຮູບ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຊື່ສິນຄ້າ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">SKU</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ໝວດ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ລາຄາຂາຍ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ສະຕ໋ອກ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ສະຖານະ</th>
                        <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($products)): ?>
                    <tr>
                        <td colspan="8" class="py-12 text-center text-gray-400">
                            <i class="fas fa-box-open text-3xl mb-2 block"></i>
                            <span>ຍັງບໍ່ມີສິນຄ້າ</span>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($products as $p): ?>
                    <tr class="border-b last:border-0 hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-2">
                            <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 overflow-hidden">
                                <?php if (!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>" class="h-full w-full object-cover" alt="">
                                <?php else: ?>
                                <i class="fas fa-box text-sm"></i>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($p['name']) ?></td>
                        <td class="py-3 px-2 text-gray-500 text-xs"><?= htmlspecialchars($p['sku'] ?? '-') ?></td>
                        <td class="py-3 px-2 text-gray-500 text-xs"><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                        <td class="py-3 px-2 font-medium"><?= number_format($p['selling_price'], 0) ?> ກີບ</td>
                        <td class="py-3 px-2">
                            <?php
                            $stock = (int)($p['stock'] ?? 0);
                            $color = $stock === 0 ? 'text-red-600 bg-red-50' : ($stock <= 10 ? 'text-amber-600 bg-amber-50' : 'text-green-600 bg-green-50');
                            ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold <?= $color ?>">
                                <i class="fas fa-cubes text-[10px]"></i>
                                <?= $stock ?>
                            </span>
                        </td>
                        <td class="py-3 px-2">
                            <?php if ($p['status'] === 'active'): ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-50 text-green-600 text-xs font-bold"><i class="fas fa-circle text-[6px]"></i> ເປີດໃຊ້</span>
                            <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold"><i class="fas fa-circle text-[6px]"></i> ປິດໃຊ້</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-3 px-2">
                            <div class="flex items-center gap-1">
                                <a href="<?= url('/products/' . $p['id'] . '/edit') ?>" class="h-8 w-8 rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center" title="ແກ້ໄຂ">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                                <a href="<?= url('/products/' . $p['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="h-8 w-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center" title="ລຶບ">
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
