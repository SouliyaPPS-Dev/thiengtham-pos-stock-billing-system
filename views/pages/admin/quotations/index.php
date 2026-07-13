<div class="min-h-screen bg-background p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ໃບສະເໜີລາຄາ</h1>
                <p class="text-sm text-muted-foreground mt-0.5">ຈັດການໃບສະເໜີລາຄາຕາມແບບຟອມ Excel</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="<?= url('/admin/quotations/export/csv?search=' . urlencode($search)) ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-50 text-emerald-600 rounded-xl font-bold text-sm hover:bg-emerald-100 transition-all">
                    <i class="fas fa-file-csv"></i>
                    <span>Export CSV</span>
                </a>
                <a href="<?= url('/admin/quotations/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                    <i class="fas fa-plus"></i>
                    <span>ສ້າງໃບສະເໜີລາຄາ</span>
                </a>
            </div>
        </div>

        <div class="bg-card rounded-2xl border border-border shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/quotations') ?>" class="mb-6">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-muted-foreground group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="ຄົ້ນຫາເລກທີໃບ, ຊື່ລູກຄ້າທີ່ສະເໜີລາຄາ, ເລກອ້າງອີງ..." class="w-full pl-9 pr-4 py-2.5 border border-border rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-card text-sm placeholder:text-gray-300">
                </div>
            </form>

            <?php $quotationIds = array_map('strval', array_column($quotations, 'id')); ?>
            <div x-data="quotationsBulkDelete()" class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-border">
                            <th class="py-3 px-2 text-center" style="width:40px">
                                <input type="checkbox" @click="toggleAll" :checked="allSelected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ເລກທີໃບ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລູກຄ້າທີ່ສະເໜີລາຄາ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ລູກຄ້າ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ເລກອ້າງອີງ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ວັນທີ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider text-right">ຍອດລວມ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-muted-foreground text-xs uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($quotations)): ?>
                        <tr>
                            <td colspan="9" class="py-3 px-2">
                                <div class="flex flex-col items-center justify-center py-12 text-center">
                                    <div class="h-16 w-16 rounded-2xl bg-gray-50 flex items-center justify-center text-gray-300 mb-4">
                                        <i class="fas fa-file-invoice text-2xl"></i>
                                    </div>
                                    <p class="text-base font-bold text-foreground/70">ຍັງບໍ່ມີໃບສະເໜີລາຄາ</p>
                                    <p class="text-sm text-muted-foreground mt-1">ສ້າງໃບສະເໜີລາຄາໃໝ່ໄດ້ທີ່ປຸ່ມ "ສ້າງໃບສະເໜີລາຄາ"</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($quotations as $q): $i++; ?>
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-2 text-center">
                                <input type="checkbox" :value="<?= $q['id'] ?>" x-model="selected" class="rounded border-gray-300 text-primary focus:ring-primary cursor-pointer">
                            </td>
                            <td class="py-3 px-2 text-muted-foreground text-sm text-center"><?= $i ?></td>
                            <td class="py-3 px-2">
                                <a href="<?= url('/admin/quotations/' . $q['id']) ?>" class="font-bold text-primary hover:text-primary/80">
                                    #<?= htmlspecialchars($q['quotation_number']) ?>
                                </a>
                            </td>
                            <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($q['bid_customer_name'] ?: '-') ?></td>
                            <td class="py-3 px-2 font-medium text-foreground"><?= htmlspecialchars($q['customer_name'] ?? $q['customer_name_resolved'] ?? '-') ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= htmlspecialchars($q['ref_no'] ?: '-') ?></td>
                            <td class="py-3 px-2 text-foreground/70"><?= $q['date'] ? date('d/m/Y', strtotime($q['date'])) : '-' ?></td>
                            <td class="py-3 px-2 font-bold text-foreground text-right"><?= number_format($q['grand_total'], 0) ?> ກີບ</td>
                            <td class="py-3 px-2">
                                <?php $st = $q['status'] ?? 'Draft'; ?>
                                <?php if ($st === 'Sent'): ?>
                                <span class="status-badge status-badge-green"><i class="fas fa-paper-plane text-[9px]"></i> ສົ່ງແລ້ວ</span>
                                <?php elseif ($st === 'Approved'): ?>
                                <span class="status-badge" style="background:#ecfdf5;color:#059669;border-color:#a7f3d0"><i class="fas fa-check-circle text-[9px]"></i> ອະນຸມັດ</span>
                                <?php elseif ($st === 'Rejected'): ?>
                                <span class="status-badge status-badge-red"><i class="fas fa-times-circle text-[9px]"></i> ປະຕິເສດ</span>
                                <?php else: ?>
                                <span class="status-badge status-badge-gray"><i class="fas fa-file text-[9px]"></i> ຮ່າງ</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center gap-1.5">
                                    <a href="<?= url('/admin/quotations/' . $q['id']) ?>" class="icon-btn icon-btn-info" title="ເບິ່ງ"><i class="fas fa-eye text-xs"></i></a>
                                    <a href="<?= url('/admin/quotations/' . $q['id'] . '/print') ?>" target="_blank" class="icon-btn icon-btn-print" title="ພິມ"><i class="fas fa-print text-xs"></i></a>
                                    <a href="<?= url('/admin/quotations/' . $q['id'] . '/edit') ?>" class="icon-btn icon-btn-edit" title="ແກ້ໄຂ"><i class="fas fa-pen text-xs"></i></a>
                                    <a href="<?= url('/admin/quotations/' . $q['id'] . '/duplicate') ?>" onclick="return confirm('ສຳເນົາໃບສະເໜີລາຄານີ້?')" class="icon-btn icon-btn-edit" title="ສຳເນົາ"><i class="fas fa-copy text-xs"></i></a>
                                    <?php if ($st === 'Approved' && empty($q['converted_to_sale_id'])): ?>
                                    <a href="<?= url('/admin/quotations/' . $q['id'] . '/convert') ?>" onclick="return confirm('ປ່ຽນເປັນບິນຂາຍ?')" class="icon-btn icon-btn-print" title="ປ່ຽນເປັນບິນ" style="background:#ecfdf5;color:#059669"><i class="fas fa-exchange-alt text-xs"></i></a>
                                    <?php endif; ?>
                                    <a href="<?= url('/admin/quotations/' . $q['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ"><i class="fas fa-trash text-xs"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php if (!empty($quotations)): ?>
                <tfoot x-show="selected.length > 0">
                    <tr>
                        <td colspan="9" class="px-2 py-0">
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
                    <a href="<?= url('/admin/quotations?page=' . ($currentPage - 1) . '&search=' . urlencode($search)) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-foreground/70 text-xs font-bold hover:bg-gray-200 transition-all"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="<?= url('/admin/quotations?page=' . $i . '&search=' . urlencode($search)) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all <?= $i === $currentPage ? 'bg-primary text-white' : 'bg-gray-100 text-foreground/70 hover:bg-gray-200' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= url('/admin/quotations?page=' . ($currentPage + 1) . '&search=' . urlencode($search)) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-foreground/70 text-xs font-bold hover:bg-gray-200 transition-all"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function quotationsBulkDelete() {
    return {
        selected: [],
        allIds: <?= json_encode($quotationIds) ?>,
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
                    form.action = '<?= url('/admin/quotations/bulk-delete') ?>';
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
        text: 'ຕ້ອງການລຶບໃບສະເໜີລາຄານີ້ແທ້ບໍ່?',
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
