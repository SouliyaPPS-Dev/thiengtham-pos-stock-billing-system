<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ສິນຄ້າ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ຈັດການຂໍ້ມູນສິນຄ້າທັງໝົດ</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex bg-gray-100 p-0.5 rounded-lg border border-gray-200">
                    <button id="gridViewBtn" onclick="setView('grid')" class="px-3 py-1.5 rounded-md text-xs font-bold transition-all bg-white shadow-sm text-primary">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button id="tableViewBtn" onclick="setView('table')" class="px-3 py-1.5 rounded-md text-xs font-bold transition-all text-gray-400 hover:text-gray-600">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                <a href="<?= url('/admin/products/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                    <i class="fas fa-plus"></i>
                    <span>ເພີ່ມສິນຄ້າ</span>
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/products') ?>" class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex-1 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາຊື່, SKU, ຫຼື barcode..." class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300">
                </div>
                <div>
                    <select name="category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                        <option value="">ທຸກໝວດ</option>
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= (($_GET['category_id'] ?? '') == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-50 text-sky-600 rounded-xl font-bold text-sm hover:bg-sky-100 transition-all">
                    <i class="fas fa-search"></i> ຄົ້ນຫາ
                </button>
            </form>

            <div id="gridView" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <?php if (empty($products)): ?>
                <div class="col-span-full flex flex-col items-center justify-center py-16 text-center">
                    <div class="h-20 w-20 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                        <i class="fas fa-box-open text-3xl"></i>
                    </div>
                    <p class="text-base font-bold text-gray-600">ຍັງບໍ່ມີສິນຄ້າ</p>
                    <p class="text-sm text-gray-400 mt-1">ສິນຄ້າຈະສະແດງຢູ່ນີ້</p>
                </div>
                <?php else: ?>
                <?php foreach ($products as $p): ?>
                <div class="group relative bg-white rounded-xl border border-gray-100 hover:border-primary/20 hover:shadow-lg transition-all p-3">
                    <div class="h-24 w-full rounded-xl bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center text-gray-300 mb-3 overflow-hidden relative">
                        <?php if (!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                        <i class="fas fa-box text-2xl"></i>
                        <?php endif; ?>
                        <div class="absolute top-1.5 right-1.5 flex gap-1">
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md <?= $p['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>">
                                <?= $p['status'] === 'active' ? 'ເປີດ' : 'ປິດ' ?>
                            </span>
                        </div>
                    </div>
                    <h3 class="text-xs font-bold text-gray-800 truncate"><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="text-[10px] text-gray-400 mt-0.5"><?= htmlspecialchars($p['sku'] ?? '-') ?></p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs font-bold text-primary"><?= number_format($p['selling_price'], 0) ?> ກີບ</span>
                        <?php
                        $stock = (int)($p['stock'] ?? 0);
                        $stockColor = $stock <= 0 ? 'text-red-600' : ($stock <= 10 ? 'text-amber-600' : 'text-green-600');
                        ?>
                        <span class="text-[10px] font-bold <?= $stockColor ?>"><?= $stock ?> ຊິ້ນ</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="<?= url('/admin/products/' . $p['id'] . '/edit') ?>" class="flex-1 text-center py-1.5 bg-amber-100 text-amber-700 rounded-lg text-[10px] font-bold hover:bg-amber-500 hover:text-white transition-all shadow-sm shadow-amber-100">
                            <i class="fas fa-pen"></i> ແກ້ໄຂ
                        </a>
                        <a href="<?= url('/admin/products/' . $p['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="flex-1 text-center py-1.5 bg-red-100 text-red-700 rounded-lg text-[10px] font-bold hover:bg-red-500 hover:text-white transition-all shadow-sm shadow-red-100">
                            <i class="fas fa-trash"></i> ລຶບ
                        </a>
                    </div>
                    <div class="mt-2 w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <?php
                        $maxStock = max($p['min_stock'] * 3, 1);
                        $stockPercent = min(100, ($stock / $maxStock) * 100);
                        $barColor = $stock <= 0 ? 'bg-red-500' : ($stock <= ($p['min_stock'] ?? 10) ? 'bg-amber-500' : 'bg-green-500');
                        ?>
                        <div class="<?= $barColor ?> h-full rounded-full" style="width: <?= $stockPercent ?>%"></div>
                    </div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div id="tableView" class="hidden overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
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
                            <td colspan="8" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-box-open text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-gray-600">ຍັງບໍ່ມີສິນຄ້າ</p>
                                    <p class="text-sm text-gray-400 mt-1">ສິນຄ້າຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($products as $p): ?>
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3 px-2">
                                <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 overflow-hidden">
                                    <?php if (!empty($p['image'])): ?>
                                    <img src="<?= htmlspecialchars($p['image']) ?>" class="h-full w-full object-cover" alt="">
                                    <?php else: ?>
                                    <i class="fas fa-box text-sm"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($p['name']) ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($p['sku'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                            <td class="py-3 px-2 font-medium text-gray-800"><?= number_format($p['selling_price'], 0) ?> ກີບ</td>
                            <td class="py-3 px-2">
                                <?php
                                $stock = (int)($p['stock'] ?? 0);
                                $stockColor = $stock === 0 ? 'status-badge-red' : ($stock <= ($p['min_stock'] ?? 10) ? 'status-badge-amber' : 'status-badge-green');
                                ?>
                                <span class="status-badge <?= $stockColor ?>">
                                    <i class="fas fa-cubes text-[10px]"></i>
                                    <?= $stock ?>
                                </span>
                            </td>
                            <td class="py-3 px-2">
                                <?php if ($p['status'] === 'active'): ?>
                                <span class="status-badge status-badge-green"><i class="fas fa-circle text-[6px]"></i> ເປີດໃຊ້</span>
                                <?php else: ?>
                                <span class="status-badge status-badge-gray"><i class="fas fa-circle text-[6px]"></i> ປິດໃຊ້</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1.5">
                                    <a href="<?= url('/admin/products/' . $p['id'] . '/edit') ?>" class="icon-btn icon-btn-edit w-auto gap-1.5 px-3" title="ແກ້ໄຂ">
                                        <i class="fas fa-pen text-xs"></i>
                                        <span class="text-[10px] hidden lg:inline">ແກ້ໄຂ</span>
                                    </a>
                                    <a href="<?= url('/admin/products/' . $p['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete w-auto gap-1.5 px-3" title="ລຶບ">
                                        <i class="fas fa-trash text-xs"></i>
                                        <span class="text-[10px] hidden lg:inline">ລຶບ</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-gray-500">ໜ້າທີ <?= $currentPage ?> ຈາກ <?= $totalPages ?></p>
                <div class="flex items-center gap-1">
                    <?php if ($currentPage > 1): ?>
                    <a href="<?= url('/admin/products?page=' . ($currentPage - 1) . '&search=' . urlencode($_GET['search'] ?? '') . '&category_id=' . urlencode($_GET['category_id'] ?? '')) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold hover:bg-gray-200 transition-all">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="<?= url('/admin/products?page=' . $i . '&search=' . urlencode($_GET['search'] ?? '') . '&category_id=' . urlencode($_GET['category_id'] ?? '')) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all <?= $i === $currentPage ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= url('/admin/products?page=' . ($currentPage + 1) . '&search=' . urlencode($_GET['search'] ?? '') . '&category_id=' . urlencode($_GET['category_id'] ?? '')) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold hover:bg-gray-200 transition-all">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function setView(view) {
    const grid = document.getElementById('gridView');
    const table = document.getElementById('tableView');
    const gridBtn = document.getElementById('gridViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');

    if (view === 'grid') {
        grid.classList.remove('hidden');
        table.classList.add('hidden');
        gridBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all bg-white shadow-sm text-primary';
        tableBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all text-gray-400 hover:text-gray-600';
        localStorage.setItem('productView', 'grid');
    } else {
        grid.classList.add('hidden');
        table.classList.remove('hidden');
        gridBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all text-gray-400 hover:text-gray-600';
        tableBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all bg-white shadow-sm text-primary';
        localStorage.setItem('productView', 'table');
    }
}

(function() {
    const saved = localStorage.getItem('productView');
    if (saved === 'table') setView('table');
})();

function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບສິນຄ້ານີ້ແທ້ບໍ່? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້',
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
