<div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-sky-50/30 p-4 md:p-8">
    <div class="max-w-7xl mx-auto space-y-6 animate-fade-in">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 tracking-tight">ໃບສະເໜີລາຄາ</h1>
                <p class="text-sm text-gray-500 mt-0.5">ຈັດການໃບສະເໜີລາຄາຕາມແບບຟອມ Excel</p>
            </div>
            <a href="<?= url('/admin/quotations/create') ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-sky-500 to-sky-600 text-white rounded-xl font-bold text-sm hover:from-sky-600 hover:to-sky-700 transition-all shadow-lg shadow-sky-200 active:scale-[0.97]">
                <i class="fas fa-plus"></i>
                <span>ສ້າງໃບສະເໜີລາຄາ</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 md:p-8">
            <form method="GET" action="<?= url('/admin/quotations') ?>" class="mb-6">
                <div class="relative group">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400 group-focus-within:text-primary transition-colors pointer-events-none">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="ຄົ້ນຫາເລກທີໃບ, ຊື່ຜູ້ສະໜອງ, ເລກອ້າງອີງ..." class="w-full pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all bg-white text-sm placeholder:text-gray-300">
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-center" style="width:48px">#</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ເລກທີໃບ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ແມ່ແບບ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ຜູ້ສະໜອງ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ເລກອ້າງອີງ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ວັນທີ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider text-right">ຍອດລວມ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider">ສະຖານະ</th>
                            <th class="py-3 px-2 font-bold text-gray-500 text-xs uppercase tracking-wider"></th>
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
                                    <p class="text-base font-bold text-gray-600">ຍັງບໍ່ມີໃບສະເໜີລາຄາ</p>
                                    <p class="text-sm text-gray-400 mt-1">ສ້າງໃບສະເໜີລາຄາໃໝ່ໄດ້ທີ່ປຸ່ມ "ສ້າງໃບສະເໜີລາຄາ"</p>
                                </div>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php $i = 0; ?>
                        <?php foreach ($quotations as $q): $i++; ?>
                        <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50/50 transition-colors">
                            <td class="py-3 px-2 text-gray-400 text-sm text-center"><?= $i ?></td>
                            <td class="py-3 px-2">
                                <a href="<?= url('/admin/quotations/' . $q['id']) ?>" class="font-bold text-primary hover:text-primary/80">
                                    #<?= htmlspecialchars($q['quotation_number']) ?>
                                </a>
                            </td>
                            <td class="py-3 px-2">
                                <?php $tpl = $templates[$q['company_template']] ?? null; ?>
                                <?php if ($tpl): ?>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold" style="background: <?= $tpl['logo_color'] ?>15; color: <?= $tpl['logo_color'] ?>">
                                    <?= htmlspecialchars($tpl['label']) ?>
                                </span>
                                <?php else: ?>
                                <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3 px-2 font-medium text-gray-800"><?= htmlspecialchars($q['supplier_name'] ?: '-') ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= htmlspecialchars($q['ref_no'] ?: '-') ?></td>
                            <td class="py-3 px-2 text-gray-600"><?= $q['date'] ? date('d/m/Y', strtotime($q['date'])) : '-' ?></td>
                            <td class="py-3 px-2 font-bold text-gray-800 text-right"><?= number_format($q['grand_total'], 0) ?> ກີບ</td>
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
                                    <a href="<?= url('/admin/quotations/' . $q['id'] . '/delete') ?>" onclick="confirmDelete(event, this.href)" class="icon-btn icon-btn-delete" title="ລຶບ"><i class="fas fa-trash text-xs"></i></a>
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
                    <a href="<?= url('/admin/quotations?page=' . ($currentPage - 1) . '&search=' . urlencode($search)) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold hover:bg-gray-200 transition-all"><i class="fas fa-chevron-left"></i></a>
                    <?php endif; ?>
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                    <a href="<?= url('/admin/quotations?page=' . $i . '&search=' . urlencode($search)) ?>" class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all <?= $i === $currentPage ? 'bg-primary text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                    <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= url('/admin/quotations?page=' . ($currentPage + 1) . '&search=' . urlencode($search)) ?>" class="px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 text-xs font-bold hover:bg-gray-200 transition-all"><i class="fas fa-chevron-right"></i></a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
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
