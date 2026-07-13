<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ໂປຣໂມຊັ້ນ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ຈັດການຮູບພາບໂປຣໂມຊັ້ນ ແລະ ປ້າຍໂຄສະນາ</p>
            </div>
            <a href="<?= url('/admin/promotions/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                <i class="fas fa-plus"></i>
                <span>ເພີ່ມໂປຣໂມຊັ້ນ</span>
            </a>
        </div>

        <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/promotions') ?>" class="flex flex-col sm:flex-row gap-3 mb-6">
                <div class="flex-1 relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" placeholder="ຄົ້ນຫາຊື່ໂປຣໂມຊັ້ນ..." class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none text-sm">
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-sky-50 text-sky-600 rounded-xl font-bold text-sm hover:bg-sky-100 transition-all">
                    <i class="fas fa-search"></i> ຄົ້ນຫາ
                </button>
            </form>

            <?php $promotionIds = array_map('strval', array_column($promotions, 'id')); ?>
            <div x-data="promotionsBulkDelete()" class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-3 px-2 text-center" style="width:40px">
                                <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider" style="width:80px">ຮູບ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ຊື່ໂປຣໂມຊັ້ນ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລິ້ງ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລຳດັບ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($promotions)): ?>
                        <tr>
                            <td colspan="8" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-bullhorn text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີໂປຣໂມຊັ້ນ</p>
                                    <p class="text-sm text-muted-foreground mt-1">ໂປຣໂມຊັ້ນຈະສະແດງຢູ່ນີ້</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $i = ($page - 1) * 20; ?>
                        <?php foreach ($promotions as $p): $i++; ?>
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3 px-2 text-center">
                                <input type="checkbox" :value="<?= $p['id'] ?>" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </td>
                            <td class="py-3 px-2 text-muted-foreground text-sm text-center"><?= $i ?></td>
                            <td class="py-3 px-2">
                                <?php if (!empty($p['image'])): ?>
                                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['title'] ?? '') ?>" class="w-12 h-8 rounded-lg object-cover border border-border">
                                <?php else: ?>
                                <div class="w-12 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-300 text-xs"><i class="fas fa-image"></i></div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($p['title'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-foreground/70 max-w-[200px] truncate"><?= htmlspecialchars($p['link'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= (int)$p['sort_order'] ?></td>
                            <td class="py-3 px-2">
                                <?php if ((strtolower($p['status'] ?? 'active')) === 'active'): ?>
                                <span class="status-badge status-badge-green"><span class="dot"></span> ເປີດໃຊ້</span>
                                <?php else: ?>
                                <span class="status-badge status-badge-gray"><span class="dot"></span> ປິດໃຊ້</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1">
                                    <a href="<?= url('/admin/promotions/' . $p['id'] . '/edit') ?>" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                    <a href="<?= url('/admin/promotions/' . $p['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (!empty($promotions)): ?>
                <tfoot x-show="selected.length > 0">
                    <tr>
                        <td colspan="8" class="px-2 py-0">
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
            </div>

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
    </div>
</div>

<script>
function promotionsBulkDelete() {
    return {
        selected: [],
        allIds: <?= json_encode($promotionIds) ?>,
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
                    form.action = '<?= url('/admin/promotions/bulk-delete') ?>';
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

function confirmDelete(event, url) {
    event.preventDefault();
    Swal.fire({
        title: 'ທ່ານແນ່ໃຈບໍ່?',
        text: 'ຕ້ອງການລຶບໂປຣໂມຊັ້ນນີ້ແທ້ບໍ່?',
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
