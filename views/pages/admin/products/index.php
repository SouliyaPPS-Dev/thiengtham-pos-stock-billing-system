<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ສິນຄ້າ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ຈັດການຂໍ້ມູນສິນຄ້າທັງໝົດ</p>
            </div>
            <div class="flex items-center gap-2">
                <div class="flex bg-gray-100 p-0.5 rounded-lg border border-border">
                    <button id="gridViewBtn" onclick="setView('grid')" class="px-3 py-1.5 rounded-md text-xs font-bold transition-all bg-card shadow-sm text-primary">
                        <i class="fas fa-th-large"></i>
                    </button>
                    <button id="tableViewBtn" onclick="setView('table')" class="px-3 py-1.5 rounded-md text-xs font-bold transition-all text-muted-foreground hover:text-foreground/70">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
                <a href="<?= url('/admin/products/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                    <i class="fas fa-plus"></i>
                    <span>ເພີ່ມສິນຄ້າ</span>
                </a>
            </div>
        </div>

        <?php $productIds = array_map('strval', array_column($products, 'id')); ?>
        <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/products') ?>" class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex-1 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາຊື່, SKU, ຫຼື barcode..." class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300">
                </div>
                <div>
                    <select name="category_id" class="w-full px-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
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
                    <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີສິນຄ້າ</p>
                    <p class="text-sm text-muted-foreground mt-1">ສິນຄ້າຈະສະແດງຢູ່ນີ້</p>
                </div>
                <?php else: ?>
                <?php foreach ($products as $p): ?>
                <div class="group relative bg-card rounded-xl border border-border hover:border-primary/20 hover:shadow-lg transition-all p-3 cursor-pointer" onclick="window.location.href='<?= url('/admin/products/' . $p['id']) ?>'">
                    <div class="h-24 w-full rounded-xl bg-gradient-to-br from-muted to-muted flex items-center justify-center text-gray-300 mb-3 overflow-hidden relative">
                        <?php if (!empty($p['image'])): ?>
                        <img src="<?= htmlspecialchars($p['image']) ?>" class="h-full w-full object-cover">
                        <?php else: ?>
                        <i class="fas fa-box text-2xl"></i>
                        <?php endif; ?>
                        <div class="absolute top-1.5 right-1.5 flex gap-1">
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md <?= $p['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-muted-foreground' ?>">
                                <?= $p['status'] === 'active' ? 'ເປີດ' : 'ປິດ' ?>
                            </span>
                        </div>
                    </div>
                    <h3 class="text-xs font-bold text-foreground truncate"><?= htmlspecialchars($p['name']) ?></h3>
                    <p class="text-[10px] text-muted-foreground mt-0.5"><?= htmlspecialchars($p['sku'] ?? '-') ?></p>
                    <div class="flex items-center justify-between mt-2">
                        <span class="text-xs font-bold text-primary"><?= number_format($p['selling_price'], 0) ?> ກີບ</span>
                        <?php
                        $stock = (int)($p['stock'] ?? 0);
                        $stockColor = $stock <= 0 ? 'text-red-600' : ($stock <= 10 ? 'text-amber-600' : 'text-green-600');
                        ?>
                        <span class="text-[10px] font-bold <?= $stockColor ?>"><?= $stock ?> ຊິ້ນ</span>
                    </div>
                    <div class="mt-2 flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity" onclick="event.stopPropagation()">
                        <a href="<?= url('/admin/products/' . $p['id']) ?>" class="flex-1 text-center py-1.5 bg-sky-100 text-sky-700 rounded-lg text-[10px] font-bold hover:bg-sky-500 hover:text-white transition-all shadow-sm shadow-sky-100">
                            <i class="fas fa-eye"></i> ເບິ່ງ
                        </a>
                        <a href="<?= url('/admin/products/' . $p['id'] . '/edit') ?>" class="flex-1 text-center py-1.5 bg-amber-100 text-amber-700 rounded-lg text-[10px] font-bold hover:bg-amber-500 hover:text-white transition-all shadow-sm shadow-amber-100">
                            <i class="fas fa-pen"></i> ແກ້ໄຂ
                        </a>
                        <a href="<?= url('/admin/products/' . $p['id'] . '/delete') ?>" onclick="event.stopPropagation(); confirmDelete(event, this.href)" class="flex-1 text-center py-1.5 bg-red-100 text-red-700 rounded-lg text-[10px] font-bold hover:bg-red-500 hover:text-white transition-all shadow-sm shadow-red-100">
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

            <div id="tableView" x-data="productsBulkDelete()" class="hidden overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-3 px-2 text-center" style="width:40px">
                                <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຮູບ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຊື່ສິນຄ້າ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">SKU</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ໝວດ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລາຄາຂາຍ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ສະຕ໋ອກ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                        <tr>
                            <td colspan="10" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-box-open text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີສິນຄ້າ</p>
                                    <p class="text-sm text-muted-foreground mt-1">ສິນຄ້າຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($products as $p): $i++; ?>
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3 px-2 text-center">
                                <input type="checkbox" :value="<?= $p['id'] ?>" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </td>
                            <td class="py-3 px-2 text-muted-foreground text-sm text-center"><?= $i ?></td>
                            <td class="py-3 px-2">
                                <div class="h-10 w-10 rounded-xl bg-gray-50 flex items-center justify-center text-muted-foreground overflow-hidden">
                                    <?php if (!empty($p['image'])): ?>
                                    <img src="<?= htmlspecialchars($p['image']) ?>" class="h-full w-full object-cover" alt="">
                                    <?php else: ?>
                                    <i class="fas fa-box text-sm"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($p['name']) ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= htmlspecialchars($p['sku'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= htmlspecialchars($p['category_name'] ?? '-') ?></td>
                            <td class="py-3 px-2 font-medium text-foreground"><?= number_format($p['selling_price'], 0) ?> ກີບ</td>
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
                                    <a href="<?= url('/admin/products/' . $p['id']) ?>" class="icon-btn icon-btn-info w-auto gap-1.5 px-3" title="ເບິ່ງລາຍລະອຽດ">
                                        <i class="fas fa-eye text-xs"></i>
                                        <span class="text-[10px] hidden lg:inline">ເບິ່ງ</span>
                                    </a>
                                    <a href="<?= url('/admin/products/' . $p['id'] . '/edit') ?>" class="icon-btn icon-btn-edit w-auto gap-1.5 px-3" title="ແກ້ໄຂ">
                                        <i class="fas fa-pen text-xs"></i>
                                        <span class="text-[10px] hidden lg:inline">ແກ້ໂຂ</span>
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
                <?php if (!empty($products)): ?>
                <tfoot x-show="selected.length > 0">
                    <tr>
                        <td colspan="10" class="px-2 py-0">
                            <div class="border border-red-200 bg-red-50/80 rounded-xl px-5 py-3 flex items-center justify-between transition-all">
                                <span class="text-sm font-bold text-red-700">
                                    <i class="fas fa-check-circle mr-1.5"></i>
                                    ເລືອກ <span x-text="selected.length" class="text-red-600 text-base"></span> ລາຍການ
                                </span>
                                <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-sm" style="background:#dc2626">
                                    <i class="fas fa-trash-alt"></i>
                                    ລຶບທັງໝົດ
                                </button>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>

            <!-- Mobile Bulk Action Bar -->
            <div x-show="selected.length > 0" class="fixed bottom-0 left-0 right-0 bg-card border-t shadow-2xl px-4 py-3 z-50 md:hidden">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-foreground/70">
                        ເລືອກ <span x-text="selected.length" class="text-primary font-black"></span> ລາຍການ
                    </span>
                    <button @click="confirmBulkDelete" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-black text-white transition-all shadow-lg" style="background:#dc2626">
                        <i class="fas fa-trash-alt"></i>
                        ລຶບທັງໝົດ
                    </button>
                </div>
            </div>
            </div>

            <?php if ($totalPages > 1): ?>
            <div class="mt-6 flex items-center justify-between">
                <p class="text-sm text-muted-foreground">ໜ້າທີ <?= $currentPage ?> ຈາກ <?= $totalPages ?></p>
                <div class="flex items-center gap-1">
                    <?php if ($currentPage > 1): ?>
                    <a href="<?= url('/admin/products?page=' . ($currentPage - 1) . '&search=' . urlencode($_GET['search'] ?? '') . '&category_id=' . urlencode($_GET['category_id'] ?? '')) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-foreground/70 text-xs font-bold hover:bg-gray-200 transition-all">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="<?= url('/admin/products?page=' . $i . '&search=' . urlencode($_GET['search'] ?? '') . '&category_id=' . urlencode($_GET['category_id'] ?? '')) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all <?= $i === $currentPage ? 'bg-primary text-white' : 'bg-gray-100 text-foreground/70 hover:bg-gray-200' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= url('/admin/products?page=' . ($currentPage + 1) . '&search=' . urlencode($_GET['search'] ?? '') . '&category_id=' . urlencode($_GET['category_id'] ?? '')) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-foreground/70 text-xs font-bold hover:bg-gray-200 transition-all">
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
function productsBulkDelete() {
    return {
        selected: [],
        allIds: <?= json_encode($productIds) ?>,
        get allSelected() {
            return this.allIds.length > 0 && this.selected.length === this.allIds.length;
        },
        toggleAll() {
            if (this.allSelected) {
                this.selected = [];
            } else {
                this.selected = [...this.allIds];
            }
        },
        confirmBulkDelete() {
            if (this.selected.length === 0) return;
            Swal.fire({
                title: 'ທ່ານແນ່ໃຈບໍ່?',
                text: 'ທ່ານຕ້ອງການລົບ ' + this.selected.length + ' ລາຍການນີ້ແທ້ບໍ່? ການກະທຳນີ້ບໍ່ສາມາດກັບຄືນໄດ້.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'ແມ່ນ, ລົບ',
                cancelButtonText: 'ຍົກເລີກ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= url('/admin/products/bulk-delete') ?>';
                    this.selected.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = id;
                        form.appendChild(input);
                    });
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    };
}

function setView(view) {
    const grid = document.getElementById('gridView');
    const table = document.getElementById('tableView');
    const gridBtn = document.getElementById('gridViewBtn');
    const tableBtn = document.getElementById('tableViewBtn');

    if (view === 'grid') {
        grid.classList.remove('hidden');
        table.classList.add('hidden');
        gridBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all bg-card shadow-sm text-primary';
        tableBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all text-muted-foreground hover:text-foreground/70';
        localStorage.setItem('productView', 'grid');
    } else {
        grid.classList.add('hidden');
        table.classList.remove('hidden');
        gridBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all text-muted-foreground hover:text-foreground/70';
        tableBtn.className = 'px-3 py-1.5 rounded-md text-xs font-bold transition-all bg-card shadow-sm text-primary';
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
